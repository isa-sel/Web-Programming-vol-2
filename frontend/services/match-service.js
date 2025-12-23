let MatchService = {
    init: function () {
        $("#addMatchForm").validate({
            submitHandler: function (form) {
                var match = Object.fromEntries(new FormData(form).entries());
                MatchService.addMatch(match);
                form.reset();
            },
        });

        $("#editMatchForm").validate({
            submitHandler: function (form) {
                var match = Object.fromEntries(new FormData(form).entries());
                MatchService.editMatch(match);
            },
        });

        MatchService.getAllMatches();
    },

    openAddModal: function() {
        $('#addMatchModal').modal('show');
    },

    addMatch: function (match) {
        $.blockUI({ message: '<h3>Zakazivanje...</h3>' });
        RestClient.post('matches', match, function(response) {
            toastr.success("Utakmica uspješno zakazana");
            $.unblockUI();
            MatchService.getAllMatches();
            MatchService.closeModal();
        }, function(response) {
            $.unblockUI();
            MatchService.closeModal();
            toastr.error(response.responseJSON?.error || "Greška prilikom zakazivanja utakmice");
        });
    },

    getAllMatches: function() {
        console.log("Fetching matches..."); // DEBUG
        
        RestClient.get("matches", function(data) {
            console.log(" Matches received:", data); // DEBUG

            // Ako API vraća {data:[...]} normalizuj
            const matches = Array.isArray(data) ? data : (data.data || []);

            const token = localStorage.getItem("user_token");
            const user = Utils.parseJwt(token)?.user;
            const isAdmin = user && user.role === Constants.ADMIN_ROLE;

            let columns = [
                { 
                    data: 'start_time', 
                    title: 'Datum i vrijeme',
                    render: function(data) {
                        return data ? new Date(data).toLocaleString('bs-BA') : 'TBD';
                    }
                },
                { 
                    data: 'home_participant_id', 
                    title: 'Domaćin',
                    render: function(data, type, row) {
                        return row.home_team_name || 'Domaćin ' + data;
                    }
                },
                { 
                    data: 'away_participant_id', 
                    title: 'Gost',
                    render: function(data, type, row) {
                        return row.away_team_name || 'Gost ' + data;
                    }
                },
                { 
                    data: 'venue_id', 
                    title: 'Dvorana',
                    render: function(data, type, row) {
                        return row.venue_name || 'TBD';
                    }
                }
            ];

            if (isAdmin) {
                columns.push({
                    title: 'Akcije',
                    render: function (data, type, row) {
                        return `<div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-info" onclick="MatchService.openEditModal(${row.id})" title="Izmijeni">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="MatchService.openConfirmationDialog(${row.id})" title="Obriši">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`;
                    }
                });
            }

          
            function updateMatchStats(matches) {
                let upcoming = 0;
                let finished = 0;
                let totalGoals = 0;
                const activeVenues = new Set();
                const now = new Date();

                matches.forEach(m => {
                    const date = new Date(m.start_time);
                    const isFinished = m.status === 'finished' || (m.home_score != null && m.away_score != null);
                    const isUpcoming = date > now && !isFinished;

                    if (isUpcoming) upcoming++;
                    if (isFinished) {
                        finished++;
                        totalGoals += (Number(m.home_score) || 0) + (Number(m.away_score) || 0);
                    }
                    if (m.venue_id) activeVenues.add(m.venue_id);
                });

                $("#stat-upcoming").text(upcoming);
                $("#stat-finished").text(finished);
                $("#stat-goals").text(totalGoals);
                $("#stat-venues").text(activeVenues.size);
            }

            // 
            updateMatchStats(matches);

            // 
            Utils.datatable('matches-table', columns, matches, 10);

        }, function (xhr) {
            console.error("Error fetching matches");
            console.error("Status Code:", xhr.status);
            console.error("Status Text:", xhr.statusText);
            console.error("Response Text:", xhr.responseText);
            console.error("Response JSON:", xhr.responseJSON);
            console.error("Full XHR:", xhr);
            
            let errorMsg = "Greška prilikom učitavanja utakmica";
            if (xhr.status === 404) errorMsg = "Endpoint /matches nije pronađen (404)";
            else if (xhr.status === 401) errorMsg = "Neautorizovan pristup (401)";
            else if (xhr.status === 500) errorMsg = "Greška na serveru (500): " + (xhr.responseText || "Unknown error");
            else if (xhr.status === 0) errorMsg = "Ne mogu se povezati sa serverom. Provjerite da li je backend pokrenut.";
            
            toastr.error(errorMsg);
        });
    },

    getMatchById: function(id) {
        $.blockUI({ message: '<h3>Učitavanje...</h3>' });
        RestClient.get('matches/' + id, function (data) {
            $('#editMatchDateTime').val(data.start_time);
            $('#editHomeParticipant').val(data.home_participant_id);
            $('#editAwayParticipant').val(data.away_participant_id);
            $('#editMatchVenue').val(data.venue_id);
            $('#editMatchNotes').val(data.notes);
            $('#editMatchId').val(data.id);
            $.unblockUI();
        }, function (xhr) {
            $.unblockUI();
            toastr.error("Greška prilikom učitavanja utakmice");
        });
    },

    openEditModal: function(id) {
        $('#editMatchModal').modal('show');
        MatchService.getMatchById(id);
    },

    closeModal: function() {
        $('#editMatchModal').modal('hide');
        $("#deleteMatchModal").modal("hide");
        $('#addMatchModal').modal('hide');
    },

    editMatch: function(match) {
        $.blockUI({ message: '<h3>Ažuriranje...</h3>' });
        RestClient.put('matches/' + match.id, match, function (data) {
            $.unblockUI();
            toastr.success("Utakmica uspješno ažurirana");
            MatchService.closeModal();
            MatchService.getAllMatches();
        }, function (xhr) {
            $.unblockUI();
            toastr.error("Greška prilikom ažuriranja utakmice");
        });
    },

    openConfirmationDialog: function (id) {
        $("#deleteMatchModal").modal("show");
        $("#delete-match-body").html("Da li ste sigurni da želite obrisati ovu utakmicu?");
        $("#delete_match_id").val(id);
    },

    deleteMatch: function () {
        const id = $("#delete_match_id").val();
        $.blockUI({ message: '<h3>Brisanje...</h3>' });
        RestClient.delete('matches/' + id, null, function(response) {
            $.unblockUI();
            MatchService.closeModal();
            toastr.success("Utakmica uspješno obrisana");
            MatchService.getAllMatches();
        }, function(response) {
            $.unblockUI();
            MatchService.closeModal();
            toastr.error(response.responseJSON?.error || "Greška prilikom brisanja utakmice");
        });
    }
}

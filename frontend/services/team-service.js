let TeamService = {
    init: function () {
        $("#addTeamForm").validate({
            submitHandler: function (form) {
                var team = Object.fromEntries(new FormData(form).entries());
                TeamService.addTeam(team);
                form.reset();
            },
        });

        $("#editTeamForm").validate({
            submitHandler: function (form) {
                var team = Object.fromEntries(new FormData(form).entries());
                TeamService.editTeam(team);
            },
        });

        TeamService.getAllTeams();
    },

    openAddModal: function() {
        $('#addTeamModal').modal('show');
    },

    addTeam: function (team) {
        $.blockUI({ message: '<h3>Processing...</h3>' });
        RestClient.post('teams', team, function(response) {
            toastr.success("Tim uspješno dodat");
            $.unblockUI();
            TeamService.getAllTeams();
            TeamService.closeModal();
        }, function(response) {
            $.unblockUI();
            TeamService.closeModal();
            toastr.error(response.responseJSON?.error || "Greška prilikom dodavanja tima");
        });
    },

    getAllTeams: function() {
    RestClient.get("teams", function(data) {
        const token = localStorage.getItem("user_token");
        const user = Utils.parseJwt(token)?.user;
        const isAdmin = user && user.role === Constants.ADMIN_ROLE;
        
        // Ukupan broj timova
        $("#total-teams").text(data.length);
        
        // Broj seniorskih timova (age_category_id = 1)
        const seniorTeams = data.filter(team => team.age_category_id == 1).length;
        $("#senior-teams").text(seniorTeams);
        
        // Broj juniorskih timova (age_category_id = 2)
        const juniorTeams = data.filter(team => team.age_category_id == 2).length;
        $("#junior-teams").text(juniorTeams);
        
        // Broj ženskih timova (gender_id = 2)
        const femaleTeams = data.filter(team => team.gender_id == 2).length;
        $("#female-teams").text(femaleTeams);

        let columns = [
            { data: 'name', title: 'Naziv tima' },
            { data: 'location', title: 'Lokacija' },
            { data: 'founded_year', title: 'Osnovan' },
            { 
                data: 'age_category_id', 
                title: 'Kategorija',
                render: function(data, type, row) {
                    const categories = {1: 'Seniori', 2: 'Juniori', 3: 'Kadeti'};
                    return categories[data] || 'N/A';
                }
            },
            { 
                data: 'gender_id', 
                title: 'Pol',
                render: function(data, type, row) {
                    return data == 1 ? 'Muški' : 'Ženski';
                }
            }
        ];

        if (isAdmin) {
            columns.push({
                title: 'Akcije',
                render: function (data, type, row) {
                    return `<div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-info" onclick="TeamService.openEditModal(${row.id})" title="Izmijeni">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="TeamService.openConfirmationDialog(${row.id}, '${row.name}')" title="Obriši">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`;
                }
            });
        }

        Utils.datatable('teams-table', columns, data, 10);
    }, function (xhr, status, error) {
        console.error('Error fetching teams:', error);
        toastr.error("Greška prilikom učitavanja timova");
    });
},

    getTeamById: function(id) {
        $.blockUI({ message: '<h3>Učitavanje...</h3>' });
        RestClient.get('teams/' + id, function (data) {
            $('#editTeamName').val(data.name);
            $('#editTeamLocation').val(data.location);
            $('#editFoundedYear').val(data.founded_year);
            $('#editAgeCategory').val(data.age_category_id);
            $('#editGenderCategory').val(data.gender_id);
            $('#editTeamId').val(data.id);
            $.unblockUI();
        }, function (xhr) {
            $.unblockUI();
            toastr.error("Greška prilikom učitavanja tima");
        });
    },

    openEditModal: function(id) {
        $('#editTeamModal').modal('show');
        TeamService.getTeamById(id);
    },

    closeModal: function() {
        $('#editTeamModal').modal('hide');
        $("#deleteTeamModal").modal("hide");
        $('#addTeamModal').modal('hide');
    },

    editTeam: function(team) {
        $.blockUI({ message: '<h3>Ažuriranje...</h3>' });
        RestClient.put('teams/' + team.id, team, function (data) {
            $.unblockUI();
            toastr.success("Tim uspješno ažuriran");
            TeamService.closeModal();
            TeamService.getAllTeams();
        }, function (xhr) {
            $.unblockUI();
            toastr.error("Greška prilikom ažuriranja tima");
        });
    },

    openConfirmationDialog: function (id, name) {
        $("#deleteTeamModal").modal("show");
        $("#delete-team-body").html("Da li želite obrisati tim: <strong>" + name + "</strong>?");
        $("#delete_team_id").val(id);
    },

    deleteTeam: function () {
        const id = $("#delete_team_id").val();
        $.blockUI({ message: '<h3>Brisanje...</h3>' });
        RestClient.delete('teams/' + id, null, function(response) {
            $.unblockUI();
            TeamService.closeModal();
            toastr.success("Tim uspješno obrisan");
            TeamService.getAllTeams();
        }, function(response) {
            $.unblockUI();
            TeamService.closeModal();
            toastr.error(response.responseJSON?.error || "Greška prilikom brisanja tima");
        });
    }
}
let PlayerService = {
    init: function () {
        $("#addPlayerForm").validate({
            submitHandler: function (form) {
                var player = Object.fromEntries(new FormData(form).entries());
                PlayerService.addPlayer(player);
                form.reset();
            },
        });

        $("#editPlayerForm").validate({
            submitHandler: function (form) {
                var player = Object.fromEntries(new FormData(form).entries());
                PlayerService.editPlayer(player);
            },
        });

        PlayerService.getAllPlayers();
    },

    openAddModal: function() {
        $('#addPlayerModal').modal('show');
    },

    addPlayer: function (player) {
        $.blockUI({ message: '<h3>Dodavanje...</h3>' });
        RestClient.post('players', player, function(response) {
            toastr.success("Igrač uspješno dodat");
            $.unblockUI();
            PlayerService.getAllPlayers();
            PlayerService.closeModal();
        }, function(response) {
            $.unblockUI();
            PlayerService.closeModal();
            toastr.error(response.responseJSON?.error || "Greška prilikom dodavanja igrača");
        });
    },

    getAllPlayers: function() {
    RestClient.get("players", function(data) {
        const token = localStorage.getItem("user_token");
        const user = Utils.parseJwt(token)?.user;
        const isAdmin = user && user.role === Constants.ADMIN_ROLE;

        // Update statistics
        $("#total-players").text(data.length);
        
        const malePlayers = data.filter(player => player.gender_id == 1).length;
        $("#male-players").text(malePlayers);
        
        const femalePlayers = data.filter(player => player.gender_id == 2).length;
        $("#female-players").text(femalePlayers);
        
        // Calculate average age
        if (data.length > 0) {
            const today = new Date();
            const ages = data.filter(p => p.birthday).map(player => {
                const birthDate = new Date(player.birthday);
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            });
            
            const avgAge = ages.length > 0 ? (ages.reduce((a, b) => a + b, 0) / ages.length).toFixed(1) : 0;
            $("#average-age").text(avgAge);
        } else {
            $("#average-age").text("-");
        }

        let columns = [
            { data: 'name', title: 'Ime i prezime' },
            { 
                data: 'birthday', 
                title: 'Datum rođenja',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('bs-BA') : 'N/A';
                }
            },
            { data: 'position', title: 'Pozicija' },
            { data: 'nationality', title: 'Nacionalnost' },
            { 
                data: 'height', 
                title: 'Visina',
                render: function(data) {
                    return data ? data + ' cm' : 'N/A';
                }
            }
        ];

        if (isAdmin) {
            columns.push({
                title: 'Akcije',
                render: function (data, type, row) {
                    return `<div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-info" onclick="PlayerService.openEditModal(${row.id})" title="Izmijeni">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="PlayerService.openConfirmationDialog(${row.id}, '${row.name}')" title="Obriši">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`;
                }
            });
        }

        Utils.datatable('players-table', columns, data, 10);
    }, function (xhr) {
        console.error('Error fetching players');
        toastr.error("Greška prilikom učitavanja igrača");
    });
},

    getPlayerById: function(id) {
        $.blockUI({ message: '<h3>Učitavanje...</h3>' });
        RestClient.get('players/' + id, function (data) {
            $('#editPlayerName').val(data.name);
            $('#editPlayerBirthday').val(data.birthday);
            $('#editPlayerPosition').val(data.position);
            $('#editPlayerNationality').val(data.nationality);
            $('#editPlayerHeight').val(data.height);
            $('#editPlayerWeight').val(data.weight);
            $('#editPlayerGender').val(data.gender_id);
            $('#editPlayerHand').val(data.hand);
            $('#editPlayerId').val(data.id);
            $.unblockUI();
        }, function (xhr) {
            $.unblockUI();
            toastr.error("Greška prilikom učitavanja igrača");
        });
    },

    openEditModal: function(id) {
        $('#editPlayerModal').modal('show');
        PlayerService.getPlayerById(id);
    },

    closeModal: function() {
        $('#editPlayerModal').modal('hide');
        $("#deletePlayerModal").modal("hide");
        $('#addPlayerModal').modal('hide');
    },

    editPlayer: function(player) {
        $.blockUI({ message: '<h3>Ažuriranje...</h3>' });
        RestClient.put('players/' + player.id, player, function (data) {
            $.unblockUI();
            toastr.success("Igrač uspješno ažuriran");
            PlayerService.closeModal();
            PlayerService.getAllPlayers();
        }, function (xhr) {
            $.unblockUI();
            toastr.error("Greška prilikom ažuriranja igrača");
        });
    },

    openConfirmationDialog: function (id, name) {
        $("#deletePlayerModal").modal("show");
        $("#delete-player-body").html("Da li želite obrisati igrača: <strong>" + name + "</strong>?");
        $("#delete_player_id").val(id);
    },

    deletePlayer: function () {
        const id = $("#delete_player_id").val();
        $.blockUI({ message: '<h3>Brisanje...</h3>' });
        RestClient.delete('players/' + id, null, function(response) {
            $.unblockUI();
            PlayerService.closeModal();
            toastr.success("Igrač uspješno obrisan");
            PlayerService.getAllPlayers();
        }, function(response) {
            $.unblockUI();
            PlayerService.closeModal();
            toastr.error(response.responseJSON?.error || "Greška prilikom brisanja igrača");
        });
    }
}
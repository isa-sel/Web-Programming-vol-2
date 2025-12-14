let VenueService = {
  init: function () {
    $("#addVenueForm").validate({
      submitHandler: function (form) {
        var venue = Object.fromEntries(new FormData(form).entries());
        VenueService.addVenue(venue);
        form.reset();
      },
    });

    $("#editVenueForm").validate({
      submitHandler: function (form) {
        var venue = Object.fromEntries(new FormData(form).entries());
        VenueService.editVenue(venue);
      },
    });

    VenueService.getAllVenues(); // povlači i venues i matches pa puni sve
  },

  openAddModal: function() {
    $('#addVenueModal').modal('show');
  },

  addVenue: function (venue) {
    $.blockUI({ message: '<h3>Dodavanje...</h3>' });
    RestClient.post('venues', venue, function(response) {
      toastr.success("Dvorana uspješno dodana");
      $.unblockUI();
      VenueService.getAllVenues();
      VenueService.closeModal();
    }, function(response) {
      $.unblockUI();
      VenueService.closeModal();
      toastr.error(response.responseJSON?.error || "Greška prilikom dodavanja dvorane");
    });
  },

  // --- GLAVNI LOADER: venues + matches (za kartice) ---
  getAllVenues: function() {
    // Učitaj venues i matches paralelno
    Promise.all([
      new Promise((resolve, reject) => RestClient.get("venues", resolve, reject)),
      new Promise((resolve, reject) => RestClient.get("matches", resolve, reject))
    ]).then(([venuesPayload, matchesPayload]) => {

      const venues = Array.isArray(venuesPayload) ? venuesPayload : (venuesPayload?.data || []);
      const matches = Array.isArray(matchesPayload) ? matchesPayload : (matchesPayload?.data || []);

      // 1) Kartice
      this.updateVenueCards(venues, matches);

      // 2) Tabela
      const token = localStorage.getItem("user_token");
      const user = Utils.parseJwt(token)?.user;
      const isAdmin = user && user.role === Constants.ADMIN_ROLE;

      let columns = [
        { data: 'name', title: 'Naziv dvorane' },
        { data: 'city', title: 'Grad' },
        { data: 'address', title: 'Adresa' }
      ];

      if (isAdmin) {
        columns.push({
          title: 'Akcije',
          render: function (data, type, row) {
            return `<div class="btn-group btn-group-sm">
                <button class="btn btn-outline-info" onclick="VenueService.openEditModal(${row.id})" title="Izmijeni">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-outline-danger" onclick="VenueService.openConfirmationDialog(${row.id}, '${row.name?.replace(/'/g,"&#39;")}')" title="Obriši">
                  <i class="fas fa-trash"></i>
                </button>
              </div>`;
          }
        });
      }

      Utils.datatable('venues-table', columns, venues, 10);

    }).catch((xhr) => {
      console.error('Error fetching venues/matches', xhr);
      toastr.error("Greška prilikom učitavanja dvorana ili utakmica");
    });
  },

  // --- Kartice: total, capacity, planned, cities ---
  updateVenueCards: function(venues, matches) {
    // Ukupno dvorana
    $("#total-venues").text(venues.length || 0);

    // Ukupni kapacitet (ako postoji venue.capacity; ako ne, prikaži '–')
    let capacityKnown = false;
    const totalCapacity = venues.reduce((sum, v) => {
      const c = parseInt(v.capacity, 10);
      if (!isNaN(c)) { capacityKnown = true; return sum + c; }
      return sum;
    }, 0);
    $("#total-capacity").text(capacityKnown ? totalCapacity.toLocaleString('bs-BA') : '–');

    // Planirane utakmice = mečevi u budućnosti (po start_time) bez obzira na dvoranu
    const now = new Date();
    const planned = matches.filter(m => {
      const ts = m.start_time ? new Date(m.start_time) : null;
      return (ts instanceof Date) && !isNaN(ts) && ts > now;
    }).length;
    $("#planned-matches").text(planned || 0);

    // Gradova = jedinstveni gradovi među dvoranama
    const citySet = new Set(venues.map(v => (v.city || '').trim()).filter(Boolean));
    $("#total-cities").text(citySet.size || 0);
  },

  getVenueById: function(id) {
    $.blockUI({ message: '<h3>Učitavanje...</h3>' });
    RestClient.get('venues/' + id, function (data) {
      $('#editVenueName').val(data.name);
      $('#editVenueCity').val(data.city);
      $('#editVenueAddress').val(data.address);
      $('#editVenueNotes').val(data.notes);
      $('#editVenueId').val(data.id);
      $.unblockUI();
    }, function (xhr) {
      $.unblockUI();
      toastr.error("Greška prilikom učitavanja dvorane");
    });
  },

  openEditModal: function(id) {
    $('#editVenueModal').modal('show');
    VenueService.getVenueById(id);
  },

  closeModal: function() {
    $('#editVenueModal').modal('hide');
    $("#deleteVenueModal").modal("hide");
    $('#addVenueModal').modal('hide');
  },

  editVenue: function(venue) {
    $.blockUI({ message: '<h3>Ažuriranje...</h3>' });
    RestClient.put('venues/' + venue.id, venue, function (data) {
      $.unblockUI();
      toastr.success("Dvorana uspješno ažurirana");
      VenueService.closeModal();
      VenueService.getAllVenues();
    }, function (xhr) {
      $.unblockUI();
      toastr.error("Greška prilikom ažuriranja dvorane");
    });
  },

  openConfirmationDialog: function (id, name) {
    $("#deleteVenueModal").modal("show");
    $("#delete-venue-body").html("Da li želite obrisati dvoranu: <strong>" + (name || '') + "</strong>?");
    $("#delete_venue_id").val(id);
  },

  deleteVenue: function () {
    const id = $("#delete_venue_id").val();
    $.blockUI({ message: '<h3>Brisanje...</h3>' });
    RestClient.delete('venues/' + id, null, function(response) {
      $.unblockUI();
      VenueService.closeModal();
      toastr.success("Dvorana uspješno obrisana");
      VenueService.getAllVenues();
    }, function(response) {
      $.unblockUI();
      VenueService.closeModal();
      toastr.error(response.responseJSON?.error || "Greška prilikom brisanja dvorane");
    });
  }
}

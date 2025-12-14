var UserService = {
    logout: function() {
        // Clear all stored data
        localStorage.clear();
        sessionStorage.clear();
        
        // Update navigation immediately
        $("#auth-nav").show();
        $("#user-nav").hide();
        $("#user-greeting").text("");
        
        // Hide all admin buttons immediately
        $("#admin-add-team-btn").empty();
        $("#admin-add-player-btn").empty();
        $("#admin-add-match-btn").empty();
        $("#admin-add-venue-btn").empty();
        
        // Show success message
        toastr.success("Uspješno ste se odjavili");
        
        // Force page reload to login
        setTimeout(function() {
            window.location.href = window.location.origin + window.location.pathname + "#login";
            location.reload();
        }, 500);
    },
    
    updateNavigation: function() {
        const token = localStorage.getItem("user_token");
        if (token) {
            const user = Utils.parseJwt(token)?.user;
            if (user) {
                $("#auth-nav").hide();
                $("#user-nav").show();
                $("#user-greeting").text("Dobrodošli, " + user.username);
            }
        } else {
            $("#auth-nav").show();
            $("#user-nav").hide();
            $("#user-greeting").text("");
        }
    },
    
    checkAdminButtons: function() {
        const token = localStorage.getItem("user_token");
        const user = Utils.parseJwt(token)?.user;
        const isAdmin = user && user.role === Constants.ADMIN_ROLE;
        
        // Clear all admin buttons first
        $("#admin-add-team-btn").empty();
        $("#admin-add-player-btn").empty();
        $("#admin-add-match-btn").empty();
        $("#admin-add-venue-btn").empty();
        
        // Show admin buttons only if user is admin
        if (isAdmin) {
            // Check which page we're on and show appropriate button
            const hash = window.location.hash;
            
            if (hash.includes('teams')) {
                $("#admin-add-team-btn").html('<button class="btn btn-info" onclick="TeamService.openAddModal()"><i class="fas fa-plus-circle mr-2"></i> Dodaj novi tim</button>');
            } else if (hash.includes('players')) {
                $("#admin-add-player-btn").html('<button class="btn btn-info" onclick="PlayerService.openAddModal()"><i class="fas fa-plus-circle mr-2"></i> Dodaj novog igrača</button>');
            } else if (hash.includes('matches')) {
                $("#admin-add-match-btn").html('<button class="btn btn-info" onclick="MatchService.openAddModal()"><i class="fas fa-plus-circle mr-2"></i> Zakaži novu utakmicu</button>');
            } else if (hash.includes('venues')) {
                $("#admin-add-venue-btn").html('<button class="btn btn-info" onclick="VenueService.openAddModal()"><i class="fas fa-plus-circle mr-2"></i> Dodaj novu dvoranu</button>');
            }
        }
    }
};
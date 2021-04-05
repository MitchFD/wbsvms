// initianlize tooltip 
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

// change dropdown collapse icon on button click from main gray card
    // for all colllapse cards
    $('.acc_collapse_cards').click(function() {
        // $('#actLogs_collapseIconToggle').toggle('1000');
        $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
    });
    // for system roles card collapse icon
        $('#listUserRoles_collapseBtnToggle').click(function() {
            // $('#actLogs_collapseIconToggle').toggle('1000');
            $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
        });
    // for user registration form
        $('#createUser_collapseBtnToggle').click(function() {
            // $('#actLogs_collapseIconToggle').toggle('1000');
            $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
        });
    // for syste role registration form
    $('#createSystemRole_collapseBtnToggle').click(function() {
        // $('#actLogs_collapseIconToggle').toggle('1000');
        $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
    });
// change dropdown collapse icon on button click from main gray card end 
    $('.custom2_btn_collapse').click(function() {
        $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
    });
// change dropdown icon on user profile - modal

// change dropdown icon on user profile - modal end

// active main gray card collpase on page refresh
    // code here
// active main gray card collpase on page refresh end

// active pill on page refresh
    $(document).ready(function() {
        if (location.hash) {
            $("a[href='" + location.hash + "']").tab("show");
        }
        $(document.body).on("click", "a[data-toggle='pill']", function(event) {
            location.hash = this.getAttribute("href");
        });
    });
    $(window).on("popstate", function() {
        var anchor = location.hash || $("a[data-toggle='pill']").first().attr("href");
        $("a[href='" + anchor + "']").tab("show");
    });
// active pill on page refresh end


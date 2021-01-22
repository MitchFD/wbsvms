// initianlize tooltip 
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

// change dropdown collapse icon on button click from main gray card
    // for registered users card collapse icon
        $('#listRegUsers_collapseBtnToggle').click(function() {
            // $('#actLogs_collapseIconToggle').toggle('1000');
            $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
            // $('#listRegUsers_subtitleTxt').text($('#listRegUsers_subtitleTxt').text() == '13 Registered Users Found.' ? 'Click me to view all registered Users...' : '13 Registered Users Found.');
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


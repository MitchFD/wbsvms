// initianlize tooltip 
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    // tooltip fro modals
        $('.modal').on('show.bs.modal', function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

// toggle icon class on button click
    $('.custom2_btn_collapse').click(function() {
        $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
    });
    $('.cust_btn_smcircle3').click(function() {
        $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
    });
    // on modals
    $('.modal').on('show.bs.modal', function () {
        $('.custom2_btn_collapse').click(function() {
            $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
        });
        $('.custom3_btn_collapse').click(function() {
            $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
        });
        $('.cust_btn_smcircle3').click(function() {
            $("i", this).toggleClass("nc-minimal-up nc-minimal-down");
        });
    });
    
// change dropdown collapse icon on button click from main gray card
    // for all colllapse cards
    $('.acc_collapse_cards').click(function() {
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


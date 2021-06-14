// initianlize tooltip 
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    // tooltip fro modals
        $('.modal').on('show.bs.modal', function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

// disable content scroll on modal
    $('.modal').on('show.bs.modal', function () {
        $("html").removeClass("perfect-scrollbar-on");
    }).on('hidden.bs.modal', function () {
        $("html").addClass("perfect-scrollbar-on");
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
        $('.custom4_btn_collapse').click(function() {
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

// active tab on page refresh
    // $(document).ready(function() {
    //     if (location.hash) {
    //         $("a[href='" + location.hash + "']").tab("show");
    //     }
    //     $(document.body).on("click", "a[data-toggle='tab']", function(event) {
    //         location.hash = this.getAttribute("href");
    //     });
    // });
    // $(window).on("popstate", function() {
    //     var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
    //     $("a[href='" + anchor + "']").tab("show");
    // });
// active tab on page refresh end

// retain accordions states on page reload
    // MAIN GRAY CARD ACCORDIONS
    $(document).ready(function(){
        $(".gCardAccordions .gCardAccordions_collapse").on('shown.bs.collapse', function (){
            var active_gCards = $(this).attr('id');
            var gCards_hideAccordions= localStorage.gCards_hideAccordions === undefined ? new Array() : JSON.parse(localStorage.gCards_hideAccordions);
            var elementAIndex=$.inArray(active_gCards,gCards_hideAccordions);
            if (elementAIndex!==-1) //check the array
            {        
                gCards_hideAccordions.splice(elementAIndex,1); //remove item from array
            }
            localStorage.gCards_hideAccordions=JSON.stringify(gCards_hideAccordions); //save array on localStorage
        });
        $(".gCardAccordions .gCardAccordions_collapse").on('hidden.bs.collapse', function (){
            var active_gCards = $(this).attr('id');
            var gCards_hideAccordions= localStorage.gCards_hideAccordions === undefined ? new Array() : JSON.parse(localStorage.gCards_hideAccordions);
            if ($.inArray(active_gCards,gCards_hideAccordions)==-1) //check that the element is not in the array
                gCards_hideAccordions.push(active_gCards);
            localStorage.gCards_hideAccordions=JSON.stringify(gCards_hideAccordions);
        });
        var gCards_hideAccordions=localStorage.gCards_hideAccordions === undefined ? new Array() : JSON.parse(localStorage.gCards_hideAccordions); //get all gCards_hideAccordions
        for (var i in gCards_hideAccordions){ //<-- gCards_hideAccordions is the name of the cookie
            if ($("#"+gCards_hideAccordions[i]).hasClass('gCardAccordions_collapse')) // check if this is a gCards_hideAccordions
            {
                $("#"+gCards_hideAccordions[i]).collapse("hide");
            }
        }
    });
    // MAIN GRAY CARD ACCORDIONS END

    // VIOLATION CARDS ACCORDIONS
    // "show" is default
    $(document).ready(function(){
        $(".violaAccordions .violaAccordions_collapse").on('shown.bs.collapse', function (){
            var active_vCards = $(this).attr('id');
            var violaCards_hideAccordions= localStorage.violaCards_hideAccordions === undefined ? new Array() : JSON.parse(localStorage.violaCards_hideAccordions);
            var elementBIndex=$.inArray(active_vCards,violaCards_hideAccordions);
            if (elementBIndex!==-1) //check the array
            {        
                violaCards_hideAccordions.splice(elementBIndex,1); //remove item from array
            }
            localStorage.violaCards_hideAccordions=JSON.stringify(violaCards_hideAccordions); //save array on localStorage
        });
        $(".violaAccordions .violaAccordions_collapse").on('hidden.bs.collapse', function (){
            var active_vCards = $(this).attr('id');
            var violaCards_hideAccordions= localStorage.violaCards_hideAccordions === undefined ? new Array() : JSON.parse(localStorage.violaCards_hideAccordions);
            if ($.inArray(active_vCards,violaCards_hideAccordions)==-1) //check that the element is not in the array
                violaCards_hideAccordions.push(active_vCards);
            localStorage.violaCards_hideAccordions=JSON.stringify(violaCards_hideAccordions);
        });
        var violaCards_hideAccordions=localStorage.violaCards_hideAccordions === undefined ? new Array() : JSON.parse(localStorage.violaCards_hideAccordions); //get all violaCards_hideAccordions
        for (var i in violaCards_hideAccordions){ //<-- violaCards_hideAccordions is the name of the cookie
            if ($("#"+violaCards_hideAccordions[i]).hasClass('violaAccordions_collapse')) // check if this is a violaCards_hideAccordions
            {
                $("#"+violaCards_hideAccordions[i]).collapse("hide");
            }
        }
    });
    // "hide" is default
    $(document).ready(function(){
        $(".hidden_violaAccordions .hidden_violaAccordions_collapse").on('shown.bs.collapse', function (){
            var inactive_vCards = $(this).attr('id');
            var hidden_violaCards_showAccordions= localStorage.hidden_violaCards_showAccordions === undefined ? new Array() : JSON.parse(localStorage.hidden_violaCards_showAccordions);
            if ($.inArray(inactive_vCards,hidden_violaCards_showAccordions)==-1) //check that the element is not in the array
                hidden_violaCards_showAccordions.push(inactive_vCards);
            localStorage.hidden_violaCards_showAccordions=JSON.stringify(hidden_violaCards_showAccordions);
        });
        $(".hidden_violaAccordions .hidden_violaAccordions_collapse").on('hidden.bs.collapse', function (){
            var inactive_vCards = $(this).attr('id');
            var hidden_violaCards_showAccordions= localStorage.hidden_violaCards_showAccordions === undefined ? new Array() : JSON.parse(localStorage.hidden_violaCards_showAccordions);
            var elementCIndex=$.inArray(inactive_vCards,hidden_violaCards_showAccordions);
            if (elementCIndex!==-1) //check the array
            {        
                hidden_violaCards_showAccordions.splice(elementCIndex,1); //remove item from array
            }
            localStorage.hidden_violaCards_showAccordions=JSON.stringify(hidden_violaCards_showAccordions); //save array on localStorage
        });
        var hidden_violaCards_showAccordions=localStorage.hidden_violaCards_showAccordions === undefined ? new Array() : JSON.parse(localStorage.hidden_violaCards_showAccordions); //get all hidden_violaCards_showAccordions
        for (var i in hidden_violaCards_showAccordions){ //<-- hidden_violaCards_showAccordions is the name of the cookie
            if ($("#"+hidden_violaCards_showAccordions[i]).hasClass('hidden_violaAccordions_collapse')) // check if this is a hidden_violaCards_showAccordions
            {
                $("#"+hidden_violaCards_showAccordions[i]).collapse("show");
            }
        }
    });
    // VIOLATION CARDS ACCORDIONS END
// retain accordions states on page reload end
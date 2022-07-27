$(document).ready(function () {

    window.onload = (event) => {
        function checkWidth_side() {
            if ($(window).width() < 500) {
                $('#myDIV_new').addClass('new_style');
                $('.side-menu').addClass('side_menu_visible');
                $('.resClass').removeClass('mr20 ml20');
            } else if ($(window).width() > 500) {
                $('.side-menu').addClass('side_menu_none');
                $('#myDIV_new').removeClass('new_style');
                $('.side-menu').removeClass('side_menu_visible');
                $('.resClass').addClass('mr20 ml20');

            } else {
                $('#myDIV_new').removeClass('new_style');
            }
        }
        $(window).resize(checkWidth_side);
        $(window).resize(checkWidth);
        if ($(window).width() < 500) {
            $('.table-toggle').removeClass('fixed-scroll-table');
            $('.table-toggle1').removeClass('table form-horizontal no-border');
            $('.table_step').removeClass('table editview-table no-border');

        }
        $(window).resize(checkWidth_side);
        if ($(window).width() < 500) {
            $('#myDIV_new').addClass('new_style');
            $('.side-menu').addClass('side_menu_visible');
            $('.resClass').removeClass('mr20 ml20');
        } else if ($(window).width() > 500) {
            $('.side-menu').addClass('side_menu_none');
            $('#myDIV_new').removeClass('new_style');
            $('.side-menu').removeClass('side_menu_visible');
            $('.resClass').addClass('mr20 ml20');
        }

        var img = document.getElementById("footer-logo");
        var cmp = document.getElementById("footer-main-company");
        var main = document.getElementById(cmp.href);
        var main_site_url = document.getElementById("joforce_site_url").value;
        if (img.src !== main_site_url + "layouts/skins/images/JoForce-footer.png" || cmp.href !== "https://www.smackcoders.com/" || cmp.innerText !== "Smackcoders") {
            document.getElementById('licence-alert-waring').style.display = "block";
        }

        function checkWidth() {
            if ($(window).width() < 500) {
                $('.table-toggle').removeClass('fixed-scroll-table');
                $('.table-toggle1').removeClass('table form-horizontal no-border');
                $('.table_step').removeClass('table editview-table no-border');
            } else {
                $('.table-toggle').addClass('fixed-scroll-table');
                $('.table-toggle1').addClass('table form-horizontal no-border');
                $('.table_step').addClass('table editview-table no-border');
            }

        }

    };
})
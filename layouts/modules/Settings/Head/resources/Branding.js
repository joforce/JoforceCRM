 $(document).ready(function()    { 

        $('.go-back').hide();
        $('.skip-the-form').hide();
        // Skip the form and move to next
        $('.skip-the-form').on('click', function()  {
            var current_step = $('#current-step').val();
            var next_step = parseInt(current_step) + 1;
            // Update the current step value
            $('#current-step').val(next_step);

            tablinks = document.getElementsByClassName("tablinks");  
            tablinks[current_step].classList.add("active");

            $('.step-'+current_step).hide();
            $('.step-'+next_step).show();

            if(next_step == 5) {
                $('.skip-the-form').hide();
            }
            $('.notification-section').html('');
        });

        // Go to previous form
        $('.go-back').on('click', function()    {
            var current_step = $('#current-step').val();
            var previous_step = parseInt(current_step) - 1;
            // Update the current step
            $('#current-step').val(previous_step);

            tablinks = document.getElementsByClassName("tablinks");  
            tablinks[previous_step].classList.remove("active");

            $('.step-'+current_step).hide();
            $('.step-'+previous_step).show();

            if(previous_step == 1) {
                $('.go-back').hide();
                $('.skip-the-form').hide();
            }
            else if(previous_step == 2) {
                $('.skip-the-form').show();
            }
            if(previous_step == 4) {
                $('.skip-the-form').show();
            }
        });
  
    $('[name="serverType"]').on('change', function()  {
    var Servertype = $('[name="serverType"]').val();
    var Server = $('[name="server"]').val(Servertype);
    });

        // Submit the form
        $('.form-submit').on('click', function()    {
            var current_step = $('#current-step').val();

            if(current_step == 1) {
                $('#updateCompanyDetailsForm').submit();    
            }
            else if(current_step == 2)  {
                $('#OutgoingServerForm').submit();                
            }
            else if(current_step == 3)  {
                $('#Brandinglogo').submit();
            }
            else if(current_step == 4)  {
                $('#loginlogo').submit();
            }
            else if(current_step == 5)  {
                var jo_url = jQuery('#joforce_site_url').val();
                window.location.href = jo_url+'Home/view/List';
            }
        });

        //Brandinglogo settings
         $('#Brandinglogo').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: 'index.php?module=Head&action=BrandinglogoSave&parent=Settings',
                type: 'POST',
                data: formData,
                success: function (data) {
                    var current_step = $('#current-step').val();
                    if(data.success == true)    {
                      
                       tablinks = document.getElementsByClassName("tablinks"); 
                       tablinks[3].classList.add("active");
                      
                        $('.notification-section').html('');
                        var next_step = parseInt(current_step) + 1;
                        // Update the current step
                        $('#current-step').val(next_step);

                        // Update the logo
                        $('.company_logo').attr('src', data.result.res);
                        $('.step-'+current_step).hide();
                        $('.step-'+next_step).show();

                        // Show back button
                        $('.go-back').show();
                        $('.skip-the-form').show();
                    }
                    else    {
                        // Show message
                        $('.notification-section').html("<div class='alert alert-danger'>"+data.error.message+"</div>");
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });


          // Save Login logo
        $('#loginlogo').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: 'index.php?module=Head&action=LoginlogoSave&parent=Settings',
                type: 'POST',
                data: formData,
                success: function (data) {
                    var current_step = $('#current-step').val();
                    if(data.success == true)    {
                        tablinks = document.getElementsByClassName("tablinks"); 
                       tablinks[4].classList.add("active");

                        $('.notification-section').html('');
                        var next_step = parseInt(current_step) + 1;
                        // Update the current step
                        $('#current-step').val(next_step);

                        // Update the logo
                        $('.company_logo').attr('src', data.result.res);
                        $('.step-'+current_step).hide();
                        $('.step-'+next_step).show();

                        // Show back button
                        $('.go-back').show();
                        $('.skip-the-form').hide();
                    }
                    else    {
                        // Show message
                        $('.notification-section').html("<div class='alert alert-danger'>"+data.error.message+"</div>");
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        // Save outgoing server
        $('#OutgoingServerForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: 'index.php?module=Head&action=OutgoingServerSaveAjax&parent=Settings',
                type: 'POST',
                data: formData,
                success: function (data) {
                    tablinks = document.getElementsByClassName("tablinks");  
                    tablinks[2].classList.add("active");
                    var current_step = $('#current-step').val();
                    if(data.success == true)    {
                        $('.notification-section').html('');
                        var next_step = parseInt(current_step) + 1;
                        // Update the current step
                        $('#current-step').val(next_step);

                        $('.step-'+current_step).hide();
                        $('.step-'+next_step).show();                        
                       
                    }
                    else    {
                        // Show message
                        $('.notification-section').html("<div class='alert alert-danger'>"+data.error.message+"</div>");
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        // Save company details
        $('#updateCompanyDetailsForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: 'index.php?module=Head&action=CompanyDetailsSave&parent=Settings',
                type: 'POST',
                data: formData,
                success: function (data) {
                    var current_step = $('#current-step').val();
                    if(data.success == true)    {
                      
                       tablinks = document.getElementsByClassName("tablinks");                        
                       tablinks[1].classList.add("active");
                      
                        $('.notification-section').html('');
                        var next_step = parseInt(current_step) + 1;
                        // Update the current step
                        $('#current-step').val(next_step);

                        // Update the logo
                        $('.company_logo').attr('src', data.result.res);
                        $('.step-'+current_step).hide();
                        $('.step-'+next_step).show();

                        // Show back button
                        $('.go-back').show();
                        $('.skip-the-form').show();
                    }
                    else    {
                        // Show message
                        $('.notification-section').html("<div class='alert alert-danger'>"+data.error.message+"</div>");
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });
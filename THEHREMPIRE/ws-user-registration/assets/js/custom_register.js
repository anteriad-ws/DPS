jQuery(document).ready(function($){
jQuery(document).on('click', '#save_form', function () {
    jQuery('.td_display_err').hide();
    if (jQuery(".user-registration form").valid()) {
        jQuery('.ur-form-row-1').hide();
        jQuery('.ur-form-row-2').attr("style", "display: flex !important");
        jQuery('#register_button').show();
        jQuery('#save_form').hide();
        jQuery('.go-back').show();

        jQuery('#save_form').removeAttr('disabled');
    } else {
        jQuery('.td_display_err').html('(*) Mandatory fields.');
        jQuery('.td_display_err').show();
    }
});
});
jQuery(document).ready(function($){
    $.validator.addMethod('restrictedEmail', function(value, element) {
        var restrictedDomains = ['yahoo', 'gmail', 'outlook','hotmail', 'rediffmail'];
        
        // Extract the domain from the email
        var emailDomain = value.split('@')[1].split('.')[0];
        
        // Check if the email domain is restricted
        return !restrictedDomains.includes(emailDomain);
      }, 'Email addresses from Yahoo, Gmail, Outlook, hotmail,and Rediffmail are not allowed.');
    $('#register-form').validate({

// Specify the validation rules,
rules: {
    username:"required",
    fname: "required",
    lname: "required",
    phoneno: "required",
    jobtitle: "required",
    companyname: "required",
    country:"required",
    email: {
        required: true,
        email: true,
        restrictedEmail: true,
    },
    password: {
        required: true,
        minlength: 5
    },
    agree: "required"
},
// Specify the validation error messages
messages: {
    fname: "Please enter your first name",
    lname: "Please enter your last name",
    agree: "Please accept our policy",
    password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
    },
    email: {
        required: 'Please enter an email address.',
        email:"we accept only buiness mail"
    } 
    
},

submitHandler: function(form) {
    form.submit();
}
});
});
$(document).ready(function(){

    $.validator.addMethod("usernameRegex", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]*$/i.test(value);
    }, "Username must contain only letters, numbers");

    $(".next").click(function(){
        var form = $("#register-form");
        form.validate({
            errorElement: 'span',
            errorClass: 'help-block',
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            rules: {
                username: {
                    required: true,
                    usernameRegex: true,
                    minlength: 6,
                },
                password : {
                    required: true,
                },
                conf_password : {
                    required: true,
                    equalTo: '#password',
                },
                company:{
                    required: true,
                },
                url:{
                    required: true,
                },
                name: {
                    required: true,
                    minlength: 3,
                },
                email: {
                    required: true,
                    minlength: 3,
                },
                
            },
            messages: {
                username: {
                    required: "Username required",
                },
                password : {
                    required: "Password required",
                },
                conf_password : {
                    required: "Password required",
                    equalTo: "Password don't match",
                },
                name: {
                    required: "Name required",
                },
                email: {
                    required: "Email required",
                },
            }
        });
        if (form.valid() === true){
            if ($('#account_information').is(":visible")){
                current_fs = $('#account_information');
                next_fs = $('#country_information');
            }else if($('#country_information').is(":visible")){
                current_fs = $('#country_information');
                next_fs = $('#country_information');
            }
            
            next_fs.show(); 
            current_fs.hide();
        }
    });

    $('#previous').click(function(){
        if($('#country_information').is(":visible")){
            current_fs = $('#country_information');
            next_fs = $('#account_information');
        }
        next_fs.show(); 
        current_fs.hide();
    });
    
    
});
// js/custom-tabs.js

jQuery(document).ready(function($) {
    $('.tab-links li a').on('click', function(e) {
        e.preventDefault();
        $('.tab-content').removeClass('active');
        $(this.hash).addClass('active');
        $('.tab-links li').removeClass('active');
        $(this).parent().addClass('active');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const tabLinks = document.querySelectorAll('.tab-links a');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach((tabLink) => {
        tabLink.addEventListener('click', function (event) {
            event.preventDefault();
            const targetId = tabLink.getAttribute('href').substring(1);

            // Hide all tab contents
            tabContents.forEach((tabContent) => {
                tabContent.classList.remove('active');
            });

            // Show the clicked tab content
            const targetTab = document.getElementById(targetId);
            targetTab.classList.add('active');
        });
    });

    // Initially, show the first set of tab contents
    tabContents[0].classList.add('active');
});

document.addEventListener('DOMContentLoaded', function() {
    var submitButton = document.getElementById('register_button');
    submitButton.disabled = false;
});


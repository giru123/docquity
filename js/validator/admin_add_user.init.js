$.validator.setDefaults(
{
    submitHandler: function() {
        form.submit();          
    },
    showErrors: function(map, list) 
    {
        this.currentElements.parents('label:first, div:first').find('.has-error').remove();
        this.currentElements.parents('.form-group:first').removeClass('has-error');

        $.each(list, function(index, error) 
        {
                var ee = $(error.element);
                var eep = ee.parents('label:first').length ? ee.parents('label:first') : ee.parents('div:first');

                ee.parents('.form-group:first').addClass('has-error');
                eep.find('.has-error').remove();
                eep.append('<p class="has-error help-block">' + error.message + '</p>');
        });
        //refreshScrollers();
    }
});

// Admin Add User Validation
$(function()
{
    $("#admin_add_user_form").validate({
        rules: {
            firstname: "required",
            lastname: "required",
            email: {
                required: true,
                email: true
            },
            password: "required",
            reg_no: "required"
//            practice_since: "required",
//            appointment_contact: "required",
//            office_contact: "required",
//            mobile: {
//                required: true,
//                minlength: 10,
//                maxlength: 10,
//                digits: true
//            },
//            dob: "required",
//            country: "required",
//            city: "required",
//            state: "required",
//            bio: "required",
//            contact_note: "required",
//            profile_pic: "required",
//            youtube_url: "required",
//            twitter_url: "required",
//            linkedin_url: "required",
//            latitude: "required",
//            longitude: "required",
//            source_id: "required",
//            ip_device: "required"
        },
        messages: {
            firstname: "Please Enter First Name",
            lastname: "Please Enter Last Name",
            email: {
                required: "Please Enter Email",
                email: "Please Enter Valid Email"
            },
            password: "Please Enter Password",
            reg_no: "Please Enter Registration Number"
//            practice_since: "Please Enter Practice Since Date",
//            appointment_contact: "Please Enter Appointment Contact",
//            office_contact: "Please Enter Office Contact",
//            mobile: {
//                required: "Please Enter Contact",
//                minlength: "Please Enter Valid Number(10 digit)",
//                maxlength: "Please Enter Valid Number(10 digit)",
//                digits: "Please Enter Valid Number"
//            },
//            dob: "Please Select Date",
//            country: "Please Enter Country",
//            city: "Please Enter City",
//            state: "Please Enter State",
//            bio: "Please Enter Biography",
//            contact_note: "Please Enter Contact Note",
//            profile_pic: "Please Upload Profile Pic",
//            youtube_url: "Please Enter Youtube URL",
//            twitter_url: "Please Enter Twitter URL",
//            linkedin_url: "Please Enter Linkedin URL",
//            latitude: "Please Enter Latitude",
//            longitude: "Please Enter Longitude",
//            source_id: "Please Enter Source Id",
//            ip_device: "Please Enter Either IP Address or Device Id"
        }
    });
});
// Admin Add User Validation



$.validator.setDefaults(
{
    submitHandler: function() {
        $.ajax({
            url: "../ajax/submit_admin_add_speciality.php",
            type: "post",
            data: $('#admin_add_speciality_form').serialize(),
            success: function(data){
                $('#tab_5').html(data);
            }
        });
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
    $("#admin_add_speciality_form").validate({
        rules: {
            speciality_name: "required"
        },
        messages: {
            speciality_name: "Please Enter Speciality Name"
        }
    });
});
// Admin Add User Validation



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

// Admin Login Validation
$(function()
{
    $("#admin_login_form").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: "required"
        },
        messages: {
            email: {
                required: "Please Enter Email",
                email: "Please Enter Valid Email"
            },
            password: "Please Enter Password"
        }
    });
});
// Admin Login Validation



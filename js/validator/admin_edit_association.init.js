$.validator.setDefaults(
{
    submitHandler: function() {
        $.ajax({
            url: "../ajax/submit_admin_edit_association.php",
            type: "post",
            data: $('#admin_edit_association_form').serialize(),
            success: function(data){
                $('#tab_2').html(data);
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
    $("#admin_edit_association_form").validate({
        rules: {
            assoc_name: "required"
//            position: "required",
//            location: "required",
//            start_date: "required",
//            end_date: "required",
            //desc: "required"
        },
        messages: {
            assoc_name: "Please Enter Associaiton Name"
//            position: "Please Enter Position",
//            location: "Please Enter Location",
//            start_date: "Please Enter Start Date",
//            end_date: "Please Enter End Date",
            //desc: "Please Enter Description"
        }
    });
});
// Admin Add User Validation



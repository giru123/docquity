$.validator.setDefaults(
{
    submitHandler: function() {
        $.ajax({
            url: "../ajax/submit_admin_add_education.php",
            type: "post",
            data: $('#admin_add_education_form').serialize(),
            success: function(data){
                $('#tab_3').html(data);
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
    $("#admin_add_education_form").validate({
        rules: {
            school_name: "required"
//            degree: "required",
//            grade: "required",
//            fos: "required",
//            start_date: "required",
//            end_date: "required",
//            act_soc: "required",
            //desc: "required"
        },
        messages: {
            school_name: "Please Enter School/College Name"
//            degree: "Please Enter Degree",
//            grade: "Please Enter Grade",
//            fos: "Please Enter Field Of Study",
//            start_date: "Please Enter Start Date",
//            end_date: "Please Enter End Date",
//            act_soc: "Please Enter Activities and Societies",
            //desc: "Please Enter Description"
        }
    });
});
// Admin Add User Validation



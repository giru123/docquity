$.validator.setDefaults(
{
    submitHandler: function() {
        var form = new FormData($('#admin_edit_project_form')[0]);
        //$('#service-pic').html('<img src="/images/loading1.gif" style="padding:0px;position: relative; top: -60px; left: 5%;"> Please wait...');
        $.ajax({
            url: "../ajax/submit_admin_edit_project.php",
            type:"post",
            data:form,
            success:function(data){
                $('#tab_8').html(data);
            },
            cache: false,
            contentType: false,
            processData: false
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
    $("#admin_edit_project_form").validate({
        rules: {
            research_id: "required",
            title: "required",
            //desc: "required",
            //caption: "required"
        },
        messages: {
            research_id: "Please Enter Research Id",
            title: "Please Enter Project Title",
            //desc: "Please Enter Project Description",
            //caption: "Please Enter Project Caption"
        }
    });
});
// Admin Add User Validation



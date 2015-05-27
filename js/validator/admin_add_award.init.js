$.validator.setDefaults(
{
    submitHandler: function() {
        var form = new FormData($('#admin_add_award_form')[0]);
        //$('#service-pic').html('<img src="/images/loading1.gif" style="padding:0px;position: relative; top: -60px; left: 5%;"> Please wait...');
        $.ajax({
            url: "../ajax/submit_admin_add_award.php",
            type:"post",
            data:form,
            success:function(data){
                $('#tab_6').html(data);
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
    $("#admin_add_award_form").validate({
        rules: {
            title: "required",
            //desc: "required",
            //date: "required",
            //pic: "required",
            //link: "required"
        },
        messages: {
            title: "Please Enter Title",
            //desc: "Please Enter Description",
            //date: "Please Enter Date",
            //pic: "Please Select Award Pic",
            //link: "Please Enter Award Link"
        }
    });
});
// Admin Add User Validation



<?php
    $uid = $_GET['uid'];
    $auth_key = $_GET['auth_key'];
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form name="admin_add_education_form" id="admin_add_education_form" method="post">
            <input type="hidden" name="uid" value="<?php echo $uid; ?>">
            <input type="hidden" name="auth_key" value="<?php echo $auth_key; ?>">
            <div class="form-group">
                <label for="school_name">School/College</label>
                <input type="text" class="form-control" name="school_name" id="school_name" placeholder="School/College">
            </div>
            <div class="form-group">
                <label for="degree">Degree</label>
                <input type="text" class="form-control" name="degree" id="degree" placeholder="Degree">
            </div>
            <div class="form-group">
                <label for="grade">Grade</label>
                <input type="text" class="form-control" name="grade" id="grade" placeholder="Grade">
            </div>
            <div class="form-group">
                <label for="fos">Field Of Study</label>
                <input type="text" class="form-control" name="fos" id="fos" placeholder="Field Of Study">
            </div>
            <div class="form-group">
                <label for="act_soc">Activities and Societies</label>
                <input type="text" class="form-control" name="act_soc" id="act_soc" placeholder="Activities and Societies">
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="text" class="form-control" name="start_date" id="start_date" placeholder="YYYY">
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="text" class="form-control" name="end_date" id="end_date" placeholder="YYYY">
            </div>
            <div class="form-group">
                <label for="desc">Description</label>
                <textarea class="form-control" name="desc" id="desc" placeholder="Description"></textarea>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" value="submit">
                <input class="btn btn-primary" type="button" name="cancel" value="cancel" onclick="showEducation('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">
            </div>
        </form>
    </div>
</div>
<script src="../js/validator/admin_add_education.init.js" type="text/javascript"></script>
<script>
    $(function(){
        $('#start_date').datepicker({
            format: 'yyyy',
            autoclose: true,
            minViewMode: 2
        });
    });
    $(function(){
        $('#end_date').datepicker({
            format: 'yyyy',
            autoclose: true,
            minViewMode: 2
        });
    });
</script>
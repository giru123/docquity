<?php
    $uid = $_GET['uid'];
    $auth_key = $_GET['auth_key'];
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form name="admin_add_association_form" id="admin_add_association_form" method="post">
            <input type="hidden" name="uid" value="<?php echo $uid; ?>">
            <input type="hidden" name="auth_key" value="<?php echo $auth_key; ?>">
            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" class="form-control" name="position" id="position" placeholder="Position">
            </div>
            <div class="form-group">
                <label for="assoc_name">Professional Experience</label>
                <input type="text" class="form-control" name="assoc_name" id="assoc_name" placeholder="Professional Experience">
            </div>
            
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" name="location" id="location" placeholder="Location">
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
                <input class="btn btn-primary" type="button" name="cancel" value="cancel" onclick="showAssociation('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">
            </div>
        </form>
    </div>
</div>
<script src="../js/validator/admin_add_association.init.js" type="text/javascript"></script>
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
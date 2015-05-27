<?php
    $uid = $_GET['uid'];
    $auth_key = $_GET['auth_key'];
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form name="admin_add_award_form" id="admin_add_award_form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="uid" value="<?php echo $uid; ?>">
            <input type="hidden" name="auth_key" value="<?php echo $auth_key; ?>">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="desc">Description</label>
                <textarea class="form-control" name="desc" id="desc" placeholder="Description"></textarea>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="text" class="form-control" name="date" id="date" placeholder="YYYY-MM-DD">
            </div>
            
            <div class="form-group">
                <label for="pic">Award Pic</label>
                <input type="file" name="pic" id="pic">
            </div>
            <div class="form-group">
                <label for="link">Award Link</label>
                <input type="text" class="form-control" name="link" id="link" placeholder="Award Link">
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" value="submit">
                <input class="btn btn-primary" type="button" name="cancel" value="cancel" onclick="showAward('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">
            </div>
        </form>
    </div>
</div>
<script src="../js/validator/admin_add_award.init.js" type="text/javascript"></script>
<script>
    $(function(){
        $('#date').datepicker({
            format: 'yyyy-mm-dd',
            startView: 1,
            autoclose: true
        });
    });
</script>
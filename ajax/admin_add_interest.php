<?php
    $uid = $_GET['uid'];
    $auth_key = $_GET['auth_key'];
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form name="admin_add_interest_form" id="admin_add_interest_form" method="post">
            <input type="hidden" name="uid" value="<?php echo $uid; ?>">
            <input type="hidden" name="auth_key" value="<?php echo $auth_key; ?>">
            <div class="form-group">
                <label for="interest_name">Interest Name</label>
                <input type="text" class="form-control" name="interest_name" id="interest_name" placeholder="Interest Name">
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" value="submit">
                <input class="btn btn-primary" type="button" name="cancel" value="cancel" onclick="showInterests('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">
            </div>
        </form>
    </div>
</div>
<script src="../js/validator/admin_add_interest.init.js" type="text/javascript"></script>
<?php
    session_start();
    require '../connect/setting.php';
    $assoc_id = $_GET['assoc_id'];
    $uid = $_GET['uid'];
    $auth_key = $_GET['auth_key'];
    $admin_id = isset($_SESSION['admin_id'])?$_SESSION['admin_id']:'';
    // Call Get Profile API
    $url = $web_server.'connect/webservice/getApi.php?rquest=GetProfile';
    //print_r($_POST);die;
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $fields = array(
        'user_id'          => $uid,
        'user_auth_key'    => $auth_key,
        'source_user_id'   => $admin_id,
        'source_id'        => '1',
        'ip_device'        => $ip_address,
        'latitude'         => '',
        'longitude'        => '',
        'format'           => 'json'
     );
    //print_r($fields); die;
     $fields_string='';
     //url-ify the data for the POST
     foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
     rtrim($fields_string,'&');
     //open connection
     $ch = curl_init();
     //set the url, number of POST vars, POST data
     curl_setopt($ch,CURLOPT_URL,$url);
     curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
     curl_setopt($ch,CURLOPT_POST,count($fields));
     curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
     //execute post
     $result = curl_exec($ch);
     //print_r($result);die;
     $result_profile_data = json_decode($result,true);
     curl_close($ch);
     $result_profile = $result_profile_data['posts'];
     $association_count = $association_count = count($result_profile['posts']['association']);
     for($i=0;$i<$association_count;$i++){
         if($result_profile['posts']['association'][$i]['association_id']==$assoc_id){
             $assoc_name = $result_profile['posts']['association'][$i]['association_name'];
             $position = $result_profile['posts']['association'][$i]['position'];
             $location = $result_profile['posts']['association'][$i]['location'];
             $start_date = $result_profile['posts']['association'][$i]['start_date'];
             $end_date = $result_profile['posts']['association'][$i]['end_date'];
             $description = $result_profile['posts']['association'][$i]['description'];
             break;
         }
         else{
             $assoc_name = '';
             $position = '';
             $location = '';
             $start_date = '';
             $end_date = '';
             $description = '';
         }
     }
    
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form name="admin_edit_association_form" id="admin_edit_association_form" method="post">
            <input type="hidden" name="assoc_id" value="<?php echo $assoc_id; ?>">
            <input type="hidden" name="uid" value="<?php echo $uid; ?>">
            <input type="hidden" name="auth_key" value="<?php echo $auth_key; ?>">
            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" class="form-control" name="position" id="position" placeholder="Position" value="<?php echo $position; ?>">
            </div>
            <div class="form-group">
                <label for="assoc_name">Professional Experience</label>
                <input type="text" class="form-control" name="assoc_name" id="assoc_name" placeholder="Professional Experience" value="<?php echo $assoc_name; ?>">
            </div>
            
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" name="location" id="location" placeholder="Location" value="<?php echo $location; ?>">
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="text" class="form-control" name="start_date" id="start_date" placeholder="YYYY" value="<?php echo $start_date; ?>">
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="text" class="form-control" name="end_date" id="end_date" placeholder="YYYY" value="<?php echo $end_date; ?>">
            </div>
            <div class="form-group">
                <label for="desc">Description</label>
                <textarea class="form-control" name="desc" id="desc" placeholder="Description"><?php echo strip_tags($description); ?></textarea>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" value="submit">
                <input class="btn btn-primary" type="button" name="cancel" value="cancel" onclick="showAssociation('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">
            </div>
        </form>
    </div>
</div>
<script src="../js/validator/admin_edit_association.init.js" type="text/javascript"></script>
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
<?php
    session_start();
    require '../connect/setting.php';
    $award_id = $_GET['award_id'];
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
     $award_count = count($result_profile['posts']['award']);
     for($i=0;$i<$award_count;$i++){
         if($result_profile['posts']['award'][$i]['award_id']==$award_id){
             $title = $result_profile['posts']['award'][$i]['title'];
             $description = $result_profile['posts']['award'][$i]['description'];
             $date = $result_profile['posts']['award'][$i]['date'];
             $award_pic = $result_profile['posts']['award'][$i]['award_pic'];
             $live_link = $result_profile['posts']['award'][$i]['live_link'];
             break;
         }
         else{
             $title = '';
             $description = '';
             $date = '';
             $award_pic = '';
             $live_link = '';
         }
     }
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form name="admin_edit_award_form" id="admin_edit_award_form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="award_id" value="<?php echo $award_id; ?>">
            <input type="hidden" name="uid" value="<?php echo $uid; ?>">
            <input type="hidden" name="auth_key" value="<?php echo $auth_key; ?>">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $title; ?>">
            </div>
            <div class="form-group">
                <label for="desc">Description</label>
                <textarea class="form-control" name="desc" id="desc" placeholder="Description"><?php echo strip_tags($description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="text" class="form-control" name="date" id="date" placeholder="YYYY-MM-DD" value="<?php if($date!='0000-00-00'){echo $date;} ?>">
            </div>
            
            <div class="form-group">
                <label for="pic">Award Pic</label>
                <input type="file" name="pic" id="pic">
            </div>
            <div class="form-group">
                <label for="link">Award Link</label>
                <input type="text" class="form-control" name="link" id="link" placeholder="Award Link" value="<?php echo $live_link; ?>">
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" value="submit">
                <input class="btn btn-primary" type="button" name="cancel" value="cancel" onclick="showAward('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">
            </div>
        </form>
    </div>
</div>
<script src="../js/validator/admin_edit_award.init.js" type="text/javascript"></script>
<script>
    $(function(){
        $('#date').datepicker({
            format: 'yyyy-mm-dd',
            startView: 1,
            autoclose: true
        });
    });
</script>
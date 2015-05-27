<?php
    session_start();
    require '../connect/setting.php';
    $research_id = $_GET['research_id'];
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
     $research_count = count($result_profile['posts']['research']);
     for($i=0;$i<$research_count;$i++){
         if($result_profile['posts']['research'][$i]['research_id']==$research_id){
             $title = $result_profile['posts']['research'][$i]['title'];
             $summary = $result_profile['posts']['research'][$i]['summary'];
             break;
         }
         else{
             $title = '';
             $summary = '';
         }
     }
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form name="admin_edit_research_form" id="admin_edit_research_form" method="post">
            <input type="hidden" name="research_id" value="<?php echo $research_id; ?>">
            <input type="hidden" name="uid" value="<?php echo $uid; ?>">
            <input type="hidden" name="auth_key" value="<?php echo $auth_key; ?>">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $title; ?>">
            </div>
            <div class="form-group">
                <label for="summary">Summary</label>
                <textarea class="form-control" name="summary" id="summary" placeholder="Summary"><?php echo strip_tags($summary); ?></textarea>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" value="submit">
                <input class="btn btn-primary" type="button" name="cancel" value="cancel" onclick="showResearch('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">
            </div>
        </form>
    </div>
</div>
<script src="../js/validator/admin_edit_research.init.js" type="text/javascript"></script><?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php
    session_start();
    require '../connect/setting.php';
    $education_id = $_GET['education_id'];
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
     $education_count = count($result_profile['posts']['education']);
     for($i=0;$i<$education_count;$i++){
         if($result_profile['posts']['education'][$i]['education_id']==$education_id){
             $school = $result_profile['posts']['education'][$i]['school'];
             $start_date = $result_profile['posts']['education'][$i]['start_date'];
             $end_date = $result_profile['posts']['education'][$i]['end_date'];
             $degree = $result_profile['posts']['education'][$i]['degree'];
             $field_study = $result_profile['posts']['education'][$i]['field_of_study'];
             $grade = $result_profile['posts']['education'][$i]['grade'];
             $act_soc = $result_profile['posts']['education'][$i]['activities_and_societies'];
             $description = $result_profile['posts']['education'][$i]['description'];
             break;
         }
         else{
             $school = '';
             $start_date = '';
             $end_date = '';
             $degree = '';
             $field_study = '';
             $grade = '';
             $act_soc = '';
             $description = '';
         }
     }
    
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form name="admin_edit_education_form" id="admin_edit_education_form" method="post">
            <input type="hidden" name="education_id" value="<?php echo $education_id; ?>">
            <input type="hidden" name="uid" value="<?php echo $uid; ?>">
            <input type="hidden" name="auth_key" value="<?php echo $auth_key; ?>">
            <div class="form-group">
                <label for="school_name">School/College</label>
                <input type="text" class="form-control" name="school_name" id="school_name" placeholder="School/College" value="<?php echo $school; ?>">
            </div>
            <div class="form-group">
                <label for="degree">Degree</label>
                <input type="text" class="form-control" name="degree" id="degree" placeholder="Degree" value="<?php echo $degree; ?>">
            </div>
            <div class="form-group">
                <label for="grade">Grade</label>
                <input type="text" class="form-control" name="grade" id="grade" placeholder="Grade" value="<?php echo $grade; ?>">
            </div>
            <div class="form-group">
                <label for="fos">Field Of Study</label>
                <input type="text" class="form-control" name="fos" id="fos" placeholder="Field Of Study" value="<?php echo $field_study; ?>">
            </div>
            <div class="form-group">
                <label for="act_soc">Activities and Societies</label>
                <input type="text" class="form-control" name="act_soc" id="act_soc" placeholder="Activities and Societies" value="<?php echo $act_soc; ?>">
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
                <input class="btn btn-primary" type="button" name="cancel" value="cancel" onclick="showEducation('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">
            </div>
        </form>
    </div>
</div>
<script src="../js/validator/admin_edit_education.init.js" type="text/javascript"></script>
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
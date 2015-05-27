<?php
    session_start();
    require '../connect/setting.php';
    $research_id = $_GET['research_id'];
    $project_id = $_GET['project_id'];
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
         $project_count = count($result_profile['posts']['research'][$i]['project']);
         for($j=0;$j<$project_count;$j++){
            if($result_profile['posts']['research'][$i]['project'][$j]['project_id']==$project_id){
                $title = $result_profile['posts']['research'][$i]['project'][$j]['title'];
                $description = $result_profile['posts']['research'][$i]['project'][$j]['description'];
                $caption = $result_profile['posts']['research'][$i]['project'][$j]['caption'];
                $project_pic = $result_profile['posts']['research'][$i]['project'][$j]['project_image_path'];
                $file_path = $result_profile['posts']['research'][$i]['project'][$j]['file_path'];
                $link = $result_profile['posts']['research'][$i]['project'][$j]['project_link'];
                $flag=1;
                break;
            }
            else{
                $title = '';
                $description = '';
                $caption = '';
                $project_pic = '';
                $file_path = '';
                $link = '';
            }
         }
         if($flag==1){
             break;
         }
     }
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form name="admin_edit_project_form" id="admin_edit_project_form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="research_id" value="<?php echo $research_id; ?>">
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
            <input type="hidden" name="uid" value="<?php echo $uid; ?>">
            <input type="hidden" name="auth_key" value="<?php echo $auth_key; ?>">
            <div class="form-group">
                <label for="research_id">Research Name</label>
               
                <?php 
                    for($i=0;$i<$research_count;$i++){
                        if($result_profile['posts']['research'][$i]['research_id']==$research_id){
                ?>
                            <input readonly type="text" class="form-control" value="<?php echo $result_profile['posts']['research'][$i]['title']; ?>">
                <?php
                            break;
                        }
                    }
                ?>
                
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $title; ?>">
            </div>
            <div class="form-group">
                <label for="desc">Description</label>
                <textarea class="form-control" name="desc" id="desc" placeholder="Description"><?php echo strip_tags($description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="caption">Caption</label>
                <textarea class="form-control" name="caption" id="caption" placeholder="Caption"><?php echo strip_tags($caption); ?></textarea>
            </div>
            <div class="form-group">
                <label for="pic">Project Pic</label>
                <input type="file" name="pic">
            </div>
            <div class="form-group">
                <label for="file">Project File</label>
                <input type="file" name="file">
                <p class="help-block">File should be pdf, doc, docx, txt format</p>
            </div>
            <div class="form-group">
                <label for="pic">Project Link</label>
                <input type="text" class="form-control" name="link" id="link" placeholder="Project Link" value="<?php echo $link; ?>">
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" value="submit">
                <input class="btn btn-primary" type="button" name="cancel" value="cancel" onclick="showProject('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">
            </div>
        </form>
    </div>
</div>
<script src="../js/validator/admin_edit_project.init.js" type="text/javascript"></script>
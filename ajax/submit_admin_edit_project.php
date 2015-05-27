<?php
    session_start();
    require '../connect/setting.php';
    // Get Project Pic
    $new_byte='';
    $byte_type='';
    if(isset($_FILES['pic']['tmp_name']) && $_FILES['pic']['tmp_name']!='' ){
       $file=$_FILES['pic']['tmp_name'];
       $file_byte=  file_get_contents($file);
       $new_byte=urlencode(base64_encode($file_byte));
       $byte_type=$_FILES['pic']['type'];
    }
    // Get Project File
    $new_byte1='';
    $byte_type1='';
    if(isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name']!='' ){
       $file1=$_FILES['file']['tmp_name'];
       $file_byte1=  file_get_contents($file1);
       $new_byte1=urlencode(base64_encode($file_byte1));
       $byte_type1=$_FILES['file']['type'];
    }
    $url = $web_server.'connect/webservice/setApi.php?rquest=SetProject';
    //print_r($_POST);die;
    $fields = array(
        'project_id'      => $_POST['project_id'],
        'research_id'     => $_POST['research_id'],
        'user_id'         => $_POST['uid'],
        'user_auth_key'   => $_POST['auth_key'],
        'title'           => $_POST['title'],
        'description'     => $_POST['desc'],
        'caption'         => $_POST['caption'],
        'image'           => $new_byte,
        'image_type'      => $byte_type,
        'file'            => $new_byte1,
        'file_type'       => $byte_type1,
        'project_link'    => $_POST['link'],
        'format'          => 'json'
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
     $result_project = json_decode($result,true);
     curl_close($ch);
     $status = $result_project['posts']['status'];
    // if($status==1){
        $url = $web_server.'connect/webservice/getApi.php?rquest=GetProfile';
        //print_r($_POST);die;
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $admin_id = isset($_SESSION['admin_id'])?$_SESSION['admin_id']:'';
        $fields = array(
            'user_id'          => $_POST['uid'],
            'user_auth_key'    => $_POST['auth_key'],
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
?>
       <div class="row">
                                                
            <img style="float: left; margin: 8px;margin-right: 2px;margin-bottom: 2px;" src="../img/add.jpg" width="30px">
            <h3 class="box-title"><a href="javascript:;" onclick="projectForm('<?php echo $_POST['uid']; ?>','<?php echo $_POST['auth_key']; ?>');">Add New Project</a></h3>

                <?php
                    if($result_profile['status']==1){
                        if(count($result_profile['posts']['research'])!=0){
                            for($i=0;$i<count($result_profile['posts']['research']);$i++){
                                if(count($result_profile['posts']['research'][$i]['project'])!=0){
                                    for($j=0;$j<count($result_profile['posts']['research'][$i]['project']);$j++){
                                        $research_id = $result_profile['posts']['research'][$i]['research_id'];
                                        $project_id = $result_profile['posts']['research'][$i]['project'][$j]['project_id'];
                ?>
                                        <div class="col-lg-4 col-xs-8">
                                            <!-- small box -->
                                            <div class="small-box bg-yellow">
                                                <div class="inner">
                                                    <p>
                                                        Project Title : 
                                                        <?php echo $result_profile['posts']['research'][$i]['project'][$j]['title']; ?>
                                                    </p>
                                                    <p>
                                                        Project Description : 
                                                        <?php echo $result_profile['posts']['research'][$i]['project'][$j]['description']; ?>
                                                    </p>
                                                    <p>
                                                        Project Caption : 
                                                        <?php echo $result_profile['posts']['research'][$i]['project'][$j]['caption']; ?>
                                                    </p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-person-add"></i>
                                                </div>
                                                <a href="javascript:;" onclick="editProjectForm('<?php echo $research_id; ?>','<?php echo $project_id; ?>','<?php echo $_POST['uid']; ?>','<?php echo $_POST['auth_key']; ?>');" class="small-box-footer">
                                                    Edit <i class="fa fa-arrow-circle-right"></i>
                                                </a>
                                            </div>
                                        </div>

                <?php
                                    }
                                }

                            }
                        }
                    }
                ?>

        </div>
<?php
     //}
?>

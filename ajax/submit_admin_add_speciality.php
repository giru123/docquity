<?php
    session_start();
    require '../connect/setting.php';
    $url = $web_server.'connect/webservice/setApi.php?rquest=CreateSpeciality';
    //print_r($_POST);die;
    $fields = array(
        'user_id'                  => $_POST['uid'],
        'user_auth_key'            => $_POST['auth_key'],
        'speciality_name'          => $_POST['speciality_name'],
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
     $result_speciality = json_decode($result,true);
     curl_close($ch);
     $status = $result_speciality['posts']['status'];
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
                <h3 class="box-title"><a href="javascript:;" onclick="specialityForm('<?php echo $_POST['uid']; ?>','<?php echo $_POST['auth_key']; ?>');">Add New Speciality</a></h3>

                <?php
                    if($result_profile['status']==1){
                        if(count($result_profile['posts']['speciality'])!=0){
                            for($i=0;$i<count($result_profile['posts']['speciality']);$i++){
                                $speciality_id = $result_profile['posts']['speciality'][$i]['speciality_id'];
                ?>
                                <div class="col-lg-4 col-xs-8">
                                    <!-- small box -->
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <p>
                                                Speciality Name : 
                                                <?php echo $result_profile['posts']['speciality'][$i]['speciality_name']; ?>
                                            </p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-person-add"></i>
                                        </div>
                                        <a href="javascript:;" onclick="editSpecialityForm('<?php echo $speciality_id; ?>','<?php echo $_POST['uid']; ?>','<?php echo $_POST['auth_key']; ?>');" class="small-box-footer">
                                            Edit <i class="fa fa-arrow-circle-right"></i>
                                        </a>
                                    </div>
                                </div>
                <?php
                            }
                        }
                    }
                ?>

        </div>
<?php
     //}
?>

<?php
    //Connect with database
    require '../connect/setting.php';
    $user_id=$_GET['userId'];
    $auth_key = $_GET['authKey'];
    $url = $web_server.'connect/webservice/setApi.php?rquest=RemoveUser';
    //print_r($_POST);die;
    $fields = array(
       'userid'   => $user_id,
       'authkey'  => $auth_key,
       'format'   => 'json'
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
    $result_user = json_decode($result,true);
    curl_close($ch);
    $status=$result_user['posts']['status'];
    if($status==1){
        $url = $web_server.'connect/webservice/getApi.php?rquest=GetDoctorList';
        //print_r($_POST);die;
        $fields = array(
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
        $result_doctor = json_decode($result,true);
        curl_close($ch);
        $status=$result_doctor['posts']['status'];
        if($status==1){
            $count=1;
            for($i=0;$i<count($result_doctor['posts'])-2;$i++){
        ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $result_doctor['posts'][$i]['first_name']; ?></td>
                    <td><?php echo $result_doctor['posts'][$i]['last_name']; ?></td>
                    <td><?php echo $result_doctor['posts'][$i]['email']; ?></td>
                    <td><?php echo $result_doctor['posts'][$i]['registration_no']; ?></td>
                    <td><?php echo $result_doctor['posts'][$i]['mobile']; ?></td>
                    <td><?php echo $result_doctor['posts'][$i]['country']; ?></td>
                    <td><?php echo $result_doctor['posts'][$i]['city']; ?></td>
                    <td><?php echo $result_doctor['posts'][$i]['state']; ?></td>
                    <td>
                        <?php 
                            if($result_doctor['posts'][$i]['user_status']==1){
                                echo 'Active';
                            }
                            else if($result_doctor['posts'][$i]['user_status']==9){
                                echo 'Deactive';
                            }
                            else if($result_doctor['posts'][$i]['user_status']==8){
                                echo 'Suspend';
                            }
                            else if($result_doctor['posts'][$i]['user_status']==0){
                                echo 'Not Verified';
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            if($result_doctor['posts'][$i]['user_status']==1){
                                echo '<a href="javascript:;" onclick="deactiveUser('.$result_doctor['posts'][$i]['user_id'].','.$auth_key.');">Deactive</a>';
                            }
                            else if($result_doctor['posts'][$i]['user_status']==9 || $result_doctor['posts'][$i]['user_status']==8 || $result_doctor['posts'][$i]['user_status']==0){
                                echo '<a href="javascript:;" onclick="activeUser('.$result_doctor['posts'][$i]['user_id'].','.$auth_key.');">Active</a>';
                            }
                            
                            echo ' | <a href="edit_user.php?uid='.$result_doctor['posts'][$i]['user_id'].'">Edit</a>';
                        ?>
                    </td>
                </tr>
        <?php
                $count++;
            }
        }
    }
    else{
        
    }
?>


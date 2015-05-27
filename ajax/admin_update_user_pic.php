<?php
    session_start();
    require '../connect/setting.php';
    $uid = $_GET['uid'];
    $auth_key = $_GET['auth_key'];
    //Get user pic
    $new_byte='';
    $byte_type='';
    if(isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name']!='' ){
       $file=$_FILES['file']['tmp_name'];
       $file_byte=file_get_contents($file);
       $new_byte=  urlencode(base64_encode($file_byte));
       $byte_type=$_FILES['file']['type'];   
    }
    //echo $new_byte;die;
    ini_set ( 'max_input_time', 259200); 
    //ini_set ( 'max_execution_time', 259200); 
    $url = $web_server.'connect/webservice/setApi.php?rquest=SetUserPic';
    $fields = array(
            'user_id'       => $uid,
            'authkey'       => $auth_key,
            'userpic'       => $new_byte,
            'userpictype'   => $byte_type,
            'format'        => 'json'
    );
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
    $result_image = json_decode($result,true);
    curl_close($ch);
    //print_r($result_image);die;
    $status=$result_image['posts']['status'];
    $imageURL=$result_image['posts']['user_pic_url'];
    $imgTag='<img src='.$web_server.$imageURL.' alt="User picture" width="100" height="100">';
    echo $status.','.$imgTag;
?>

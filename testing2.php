<?php
//phpinfo();
    require 'connect/setting.php';
    $url = $web_server.'connect/webservice/getApi.php?rquest=Login';
    //print_r($_POST);die;
    //$ip_address = $_SERVER['REMOTE_ADDR'];
    $fields = array(
        'email' => 'abc@gmail.com',
        'password'  => '1212',
        'latitude'  => '',
        'longitude' => '',
        'source_id' => '2',
        'ip_device' => 'adad',
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
     //print_r($result);die;
     $result_friend_list = json_decode($result,true);
     curl_close($ch);
     echo '<pre>';
     print_r($result_friend_list);
     echo '</pre>';
?>
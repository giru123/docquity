<?php
    require 'connect/setting.php';
    $passkey=$_GET['passkey'];
    $arr=explode('-',$passkey);
    $confirm_code=$arr[0];
    //$confirm_code='c1a2f86965a1c0400c3c447a42b2359b';
    $user_id=$arr[1];
    //$user_id='5';
    $url = $web_server.'connect/webservice/setApi.php?rquest=VerifyUser';
    $fields = array(
        'user_id'             => $user_id,
        'verification_code'   => $confirm_code,
        'format'              => 'json'
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
     $result_confirm = json_decode($result,true);
     curl_close($ch);
     print_r($result_confirm);
?>

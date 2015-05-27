<?php
//phpinfo();
    require 'connect/setting.php';
    $url = $web_server.'connect/webservice/getApi.php?rquest=GetProfile';
    $fields = array(
        'user_id'       => '7',
        'user_auth_key'       => '14296979427',
        'source_user_id'        => '1',
        'source_id'       => '1',
        'ip_device'     => '192.1.2.3',
        'latitude' => '22.70',
        'longitude'  => '77.60',
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
     $result_group = json_decode($result,true);
     curl_close($ch);
     echo '<pre>';
     print_r($result_group);
     //$data = json_decode($result_group['posts']['serialised_data'],true);
     //print_r($data);
     echo '</pre>';
?>
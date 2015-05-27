<?php
if( ini_get('safe_mode') ){
   // safe mode is on
    echo 'safe mode is on';
}else{
   // it's not
    echo 'safe mode is not on';
}

$output = shell_exec('sh create.sh Group testabhi chat.docquity.com "" Abhishek "Test Abhishek group description"');
//print_r($output);
echo "<pre>$output</pre>";
?>
<?php
    session_start();
    require '../connect/setting.php';
    $url = $web_server.'connect/webservice/setApi.php?rquest=SetBiography';
    //print_r($_POST);die;
    $fields = array(
        'user_id'                  => $_POST['uid'],
        'user_auth_key'            => $_POST['auth_key'],
        'bio'                      => $_POST['summary'],
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
     $result_summary = json_decode($result,true);
     curl_close($ch);
     $status = $result_summary['posts']['status'];
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
            <div class="col-md-8 col-md-offset-2">
                <form name="admin_edit_summary_form" id="admin_edit_summary_form" method="post">
                    <input type="hidden" name="uid" value="<?php echo $_POST['uid']; ?>">
                    <input type="hidden" name="auth_key" value="<?php echo $_POST['auth_key']; ?>">
                    <div class='box-body pad'>
                        <div class="form-group">
                            <textarea name="summary" id="summary" class="textarea" placeholder="Summary" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                <?php
                                    echo strip_tags($result_profile['posts']['bio']);
                                ?>
                            </textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" name="submit" value="submit">
                        </div>
                    </div>
                </form>
            </div>
        </div>   
<?php
     //}
?>
<script type="text/javascript">
    $(function() {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        //CKEDITOR.replace('editor1');
        //bootstrap WYSIHTML5 - text editor
        $("#summary").wysihtml5();
    });
</script>
<script src="../js/validator/admin_edit_summary.init.js" type="text/javascript"></script>
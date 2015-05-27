<?php
    session_start();
    require '../connect/setting.php';
    if(isset($_POST['submit'])){
        $remember = $_POST['remember_me'];
        if($remember=='on'){
            $day = time() + 86400;
            setcookie('username', $_POST['email'], $day);
            setcookie('password', $_POST['password'], $day);
        }
        else{
            $past = time() - 100;
            setcookie('username', '', $past);
            setcookie('password', '', $past);
        }
        $url = $web_server.'connect/webservice/getApi.php?rquest=Login';
        $ip_address = $_SERVER['REMOTE_ADDR'];
        //print_r($_POST);die;
        $fields = array(
            'email'            => $_POST['email'],
            'password'         => $_POST['password'],
            'latitude'         => '',
            'longitude'        => '',
            'source_id'        => '1',
            'ip_device'        => $ip_address,
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
        $result_admin_login = json_decode($result,true);
        //echo "1"; print_r($result); die;
        curl_close($ch);
        $status = $result_admin_login['posts']['status'];
        if($status==1){
            $first_name=$result_admin_login['posts']['first_name'];
            $last_name=$result_admin_login['posts']['last_name'];
            $name = ucfirst($first_name).' '.ucfirst($last_name);
            $_SESSION['admin_name']=$name;
            $_SESSION['admin_id']=$result_admin_login['posts']['user_id'];
            $_SESSION['admin_auth_key']=$result_admin_login['posts']['user_auth_key'];
            header('Location: home.php');
            exit();
        }
    }

?>
<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>Admin Docquity | Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bg-black">
        <div class="form-box" id="login-box">
            <div class="header">Sign In</div>
            <form name="admin_login_form" id="admin_login_form" method="post">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo $_COOKIE['username']; ?>"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo $_COOKIE['password']; ?>"/>
                    </div>  
                    <div class="form-group">
                        <input type="checkbox" name="remember_me" <?php if(isset($_COOKIE['username'])) {
                            echo 'checked';
                            }
                            else {
                                echo '';
                            }?>
                            /> Remember me
                    </div>
                </div>
                <div class="footer">                                                               
                    <button type="submit" name="submit" class="btn bg-olive btn-block">Sign me in</button>
                    <p><a href="javascript:;" onclick="adminForgetPassword();">I forgot my password</a></p>
                    <!--<a href="register.php" class="text-center">Register a new membership</a>-->
                </div>
            </form>
        </div>
        <!-- jQuery Library -->
        <script src="../js/jquery-1.11.1.js"></script>
        <!-- Bootstrap -->
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>        
        <!-- jQuery Validator -->
        <script src="../js/jquery.validate.min.js" type="text/javascript"></script> 
        <!-- Include Admin login validation script -->
        <script src="../js/validator/admin_login.init.js" type="text/javascript"></script> 
        <script>
            function adminForgetPassword(){
                alert('hi');
            }
        </script>
    </body>
</html>
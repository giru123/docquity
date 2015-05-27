<?php 
    session_start();
    require '../connect/setting.php';
    if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_auth_key'])){
            $admin_id = $_SESSION['admin_id'];
            $auth_key = $_SESSION['admin_auth_key'];
    }
    else{
        header('Location: index.php');
        exit();
    }
    if(isset($_POST['submit'])){
        $first_name = $_POST['firstname'];
        $last_name = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $reg_no = $_POST['reg_no'];
        $practice_since = $_POST['practice_since'];
        $appointment_contact = $_POST['appointment_contact'];
        $office_contact = $_POST['office_contact'];
        $mobile = $_POST['mobile'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $bio = $_POST['bio'];
        $contact_note = $_POST['contact_note'];
        $language = $_POST['language'];
        // Get user pic
        $new_byte_pic='';
        $pic_type='';
        if(isset($_FILES['profile_pic']['tmp_name']) && $_FILES['profile_pic']['tmp_name']!='' ){
           $file_pic=$_FILES['profile_pic']['tmp_name'];
           $byte_pic=  file_get_contents($file_pic);
           $new_byte_pic=urlencode(base64_encode($byte_pic));
           $pic_type=$_FILES['profile_pic']['type'];
        }
        // Get user pic
        $youtube_url = $_POST['youtube_url'];
        $twitter_url = $_POST['twitter_url'];
        $linkedin_url = $_POST['linkedin_url'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $source_id = $_POST['source_id'];
        $ip_device = $_POST['ip_device'];
        $url = $web_server.'connect/webservice/setApi.php?rquest=SignUp';
        $fields = array(
            'firstname'           => $first_name,
            'lastname'            => $last_name,
            'email'               => $email,
            'password'            => $password,
            'registration_no'     => $reg_no,
            'practice_since'      => $practice_since,
            'appointment_contact' => $appointment_contact,
            'office_contact'      => $office_contact,
            'mobile'              => $mobile,
            'gender'              => $gender,
            'date_of_birth'       => $dob,
            'country'             => $country,
            'city'                => $city,
            'state'               => $state,
            'bio'                 => $bio,
            'language'            => $language,
            'contact_note'        => $contact_note,
            'profile_pic'         => $new_byte_pic,
            'profile_pic_type'    => $pic_type,
            'youtube_url'         => $youtube_url,
            'twitter_url'         => $twitter_url,
            'linkedin_url'        => $linkedin_url,
            'latitude'            => $latitude,
            'longitude'           => $longitude,
            'source_id'           => $source_id,
            'ip_device'           => $ip_device,
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
         //print_r($result);
         $result_user = json_decode($result,true);
         curl_close($ch);
         //print_r($result_user);die;
         $status=$result_user['posts']['status'];
         if($status==1){
             header('Location: doctor_management.php');
             exit();
         }
    }
    // Get Source 
    $url = $web_server.'connect/webservice/getApi.php?rquest=GetSource';
     $fields = array(
        'format'         => 'json'
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
     //print_r($result);
     $result_source = json_decode($result,true);
     curl_close($ch);
     $status=$result_source['posts']['status'];
     if($status==1){
         $source_count = count($result_source['posts'])-2;
     }
     else{
         $source_count = 0;
     }
     // End Get Source
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Docquity | Add User</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- DATA TABLES -->
        <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- Bootstrap datetime picker -->
        <link href="../css/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <?php
            include 'header.php';
        ?>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <?php
                include 'sidebar.php';
            ?>
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Add New Doctor
<!--                        <small>advanced tables</small>-->
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Tables</a></li>
                        <li class="active">Data tables</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                     <!-- form start -->
                    <form role="form" name="admin_add_user_form" id="admin_add_user_form" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Basic Info</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="firstname">First Name</label>
                                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="lastname">Last Name</label>
                                        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <label for="reg_no">Registration No.</label>
                                        <input type="text" class="form-control" name="reg_no" id="reg_no" placeholder="Registration Number">
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                         <select class="form-control" name="gender" id="gender">
                                             <option value="M">Male</option>
                                             <option value="F">Female</option>
                                             <option value="O">Others</option>
                                         </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="profile_pic">Profile Pic</label>
                                        <input type="file" id="profile_pic" name="profile_pic">
                                        <p class="help-block">Upload Your Profile Pic</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="dob">Date of Birth</label>
                                        <input type="text" class="form-control" name="dob" id="dob" placeholder="YYYY-MM-DD">
                                    </div>
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input type="text" class="form-control" name="country" id="country" placeholder="Country">
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" name="city" id="city" placeholder="City">
                                    </div>
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" name="state" id="state" placeholder="State">
                                    </div>
                                   
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                            
                            <div class="box box-success">
                                <div class="box-header">
                                    <h3 class="box-title">Contacts</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body">

                                    <div class="form-group">
                                        <label for="appointment_contact">Appointment Contact</label>
                                        <input type="text" class="form-control" name="appointment_contact" id="appointment_contact" placeholder="Appointment Contact">
                                    </div>
                                    <div class="form-group">
                                        <label for="office_contact">Office Contact</label>
                                        <input type="text" class="form-control" name="office_contact" id="office_contact" placeholder="Office Contact">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile">Mobile</label>
                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile">
                                    </div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                        <div class="col-md-6">
                            <div class="box box-danger">
                                    <div class="box-header">
                                        <h3 class="box-title">Other Info</h3>
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="practice_since">Practice Since</label>
                                            <input type="text" class="form-control" name="practice_since" id="practice_since" placeholder="Practice Since">
                                        </div>
                                        <div class="form-group">
                                            <label for="bio">Biography</label>
                                            <textarea class="form-control" rows="3" name="bio" id="bio" placeholder="Biography"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_note">Contact Note</label>
                                            <textarea class="form-control" rows="3" name="contact_note" id="contact_note" placeholder="Contact Note"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="language">Language</label>
                                            <select class="form-control" name="language" id="language">
                                                <option value="english">English</option>
                                                <option value="hindi">Hindi</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="latitude">Latitude</label>
                                            <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Latitude">
                                        </div>
                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>
                                            <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Longitude">
                                        </div>
                                        <div class="form-group">
                                            <label for="source_id">Source</label>
                                            <input type="text" class="form-control" name="source_id" id="source_id" placeholder="1 for web, 2 for android, 3 for ios ">
                                        </div>
                                        <div class="form-group">
                                            <label for="ip_device">IP/Device Id</label>
                                            <input type="text" class="form-control" name="ip_device" id="ip_device" placeholder="IP Address/Device Id">
                                        </div>
                                    </div><!-- /.box-body -->

                            </div><!-- /.box -->
                            <div class="box box-info">
                                    <div class="box-header">
                                        <h3 class="box-title">Social Sites</h3>
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="youtube_url">Youtube URL</label>
                                            <input type="text" class="form-control" name="youtube_url" id="youtube_url" placeholder="Youtube URL">
                                        </div>
                                        <div class="form-group">
                                            <label for="twitter_url">Twitter URL</label>
                                            <input type="text" class="form-control" name="twitter_url" id="twitter_url" placeholder="Twitter URL">
                                        </div>
                                        <div class="form-group">
                                            <label for="linkedin_url">Linkedin URL</label>
                                            <input type="text" class="form-control" name="linkedin_url" id="linkedin_url" placeholder="Linkedin URL">
                                        </div>
                                    </div><!-- /.box-body -->
                            </div><!-- /.box -->
                            
                        </div>
                           
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                        </form>
                    </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div>

                
        
        
        <!-- jQuery 2.0.2 -->
        <script src="../js/jquery-1.11.1.js" type="text/javascript"></script>
        <!-- Bootstrap -->
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- DATA TABES SCRIPT -->
        <script src="../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../js/AdminLTE/demo.js" type="text/javascript"></script>
        <!-- Bootstrap datetime picker -->
        <script src="../js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <!-- Jquery Validate -->
        <script src="../js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="../js/validator/admin_add_user.init.js" type="text/javascript"></script>
    </body>
</html>
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
    //////////// calling these from GET are wrong, $_GET['uid'], $_GET['auth_key']. You need to get this from SESSION.
    //////////// Correct these.
    if(isset($_POST['submit'])){
        $first_name = $_POST['firstname'];
        $last_name = $_POST['lastname'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $bio = $_POST['bio'];
        $contact_note = $_POST['contact_note'];
        $language = $_POST['language'];
        $practice_since=$_POST['practice_since'];
        $appointment_contact=$_POST['appointment_contact'];
        $office_contact=$_POST['office_contact']; 
        $youtube_url = $_POST['youtube_url'];
        $twitter_url = $_POST['twitter_url'];
        $linkedin_url = $_POST['linkedin_url'];
        $url = $web_server.'connect/webservice/setApi.php?rquest=SetProfile';
        $fields = array(
            'user_id'             => $_GET['uid'], //correct this to get from session
            'user_auth_key'       => $auth_key, //correct this to get from session
            'firstname'           => $_POST['firstname'],
            'lastname'            => $_POST['lastname'],
            'email'               => $_POST['email'],
            'practicing_since'    => $_POST['practice_since'],
            'appointment_contact' => $_POST['appointment_contact'],
            'office_contact'      => $_POST['office_contact'],
            'country'             => $_POST['country'],
            'city'                => $_POST['city'],
            'state'               => $_POST['state'],
            'mobile'              => $_POST['mobile'],
            'gender'              => $_POST['gender'],
            'dob'                 => $_POST['dob'],
//            'bio'                 => $_POST['bio'],
            'contact_note'        => $_POST['contact_note'],
            'language'            => $_POST['language'],
//            'profile_pic'         => $new_byte_pic,
//            'profile_pic_type'    => $pic_type,
            'youtube_url'         => $_POST['youtube_url'],
            'twitter_url'         => $_POST['twitter_url'],
            'linkedin_url'        => $_POST['linkedin_url'],
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
         $result_profile = json_decode($result,true);
         curl_close($ch);
       //  print_r($result_profile); die;
         $status=$result_profile['posts']['status'];
    }
    if(isset($_GET['uid']) && $_GET['uid']!=''){
        $uid=$_GET['uid'];
        $url = $web_server.'connect/webservice/getApi.php?rquest=GetProfile';
        //print_r($_POST);die;
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $fields = array(
            'user_id'          => $uid,
            'user_auth_key'    => $auth_key,
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
//         echo '<pre>';
//         print_r($result_profile_data);
//         echo '</pre>';die;
         $status=$result_profile['status'];
         if($status==1){
            $first_name=$result_profile['posts']['first_name'];
            $last_name=$result_profile['posts']['last_name'];
            $registration_no=$result_profile['posts']['registration_no'];
            $practice_since=$result_profile['posts']['practice_since'];
            $appointment_contact=$result_profile['posts']['appointment_contact'];
            $office_contact=$result_profile['posts']['office_contact'];
            $email=$result_profile['posts']['email'];
            $mobile=$result_profile['posts']['mobile'];
            $dob=$result_profile['posts']['dob'];
            $country=$result_profile['posts']['country'];
            $city=$result_profile['posts']['city'];
            $state=$result_profile['posts']['state'];
            $bio=$result_profile['posts']['bio'];
            $language=$result_profile['posts']['language'];
            $contact_note=$result_profile['posts']['contact_note'];
            $profile_pic=$result_profile['posts']['profile_pic'];
            $gender=$result_profile['posts']['gender'];
            $youtube_url=$result_profile['posts']['youtube_url'];
            $twitter_url=$result_profile['posts']['twitter_url'];
            $linkedin_url=$result_profile['posts']['linkedin_url'];
            
//            $association_count = count($result_profile['posts']['association']);
//            $speciality_count = count($result_profile['posts']['speciality']);
//            $interest_count = count($result_profile['posts']['interest']);
//            $education_count = count($result_profile['posts']['education']);
//            $award_count = count($result_profile['posts']['award']);
//            $research_count = count($result_profile['posts']['research']);
         }
         else{
            $first_name='';
            $last_name='';
            $registration_no='';
            $practice_since='';
            $appointment_contact='';
            $office_contact='';
            $email='';
            $mobile='';
            $dob='';
            $country='';
            $city='';
            $state='';
            $bio='';
            $language='';
            $contact_note='';
            $profile_pic='';
            $gender='';
            $youtube_url='';
            $twitter_url='';
            $linkedin_url='';
            
            
         }
    }
    else{
        $uid='';
        $auth_key='';
        $first_name='';
        $last_name='';
        $registration_no='';
        $practice_since='';
        $appointment_contact='';
        $office_contact='';
        $email='';
        $mobile='';
        $dob='';
        $city='';
        $state='';
        $bio='';
        $language='';
        $contact_note='';
        $profile_pic='';
        $gender='';
        $youtube_url='';
        $twitter_url='';
        $linkedin_url='';
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Docquity | Edit User</title>
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
        <link href="../js/bootstrap-datepicker/bootstrap-datepicker/assets/lib/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="../css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
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
                        Add/Edit Doctor Profile
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">Basic Info</a></li>
                                    <li><a href="#tab_2" data-toggle="tab">Professional Experience</a></li>
                                    <li><a href="#tab_3" data-toggle="tab">Education</a></li>
                                    <li><a href="#tab_4" data-toggle="tab">Interests</a></li>
                                    <li><a href="#tab_5" data-toggle="tab">Speciality</a></li>
                                    <li><a href="#tab_6" data-toggle="tab">Award</a></li>
                                    <li><a href="#tab_7" data-toggle="tab">Research</a></li>
                                    <li><a href="#tab_8" data-toggle="tab">Project</a></li>
                                    <li><a href="#tab_9" data-toggle="tab">Edit Summary</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <form name="admin_edit_user_form" id="admin_edit_user_form" method="post">
                                        <div class="row">
                                            <div class="col-md-6">
                                                    <div class="box box-primary">
                                                        <div class="box-header">
                                                            <h3 class="box-title">Basic Info</h3>
                                                        </div><!-- /.box-header -->
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <label for="firstname">First Name</label>
                                                                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name" value="<?php echo $first_name; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="lastname">Last Name</label>
                                                                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name" value="<?php echo $last_name; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="email">Email</label>
                                                                <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="gender">Gender</label>
                                                                 <select class="form-control" name="gender" id="gender">
                                                                     <option value="M" <?php if($gender=='M'){echo 'selected';} ?>>Male</option>
                                                                     <option value="F" <?php if($gender=='F'){echo 'selected';} ?>>Female</option>
                                                                     <option value="O" <?php if($gender=='O'){echo 'selected';} ?>>Others</option>
                                                                 </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="dob">Date of Birth</label>
                                                                <input type="text" class="form-control" name="dob" id="dob" placeholder="YYYY-MM-DD" value="<?php if($dob!='0000-00-00'){echo $dob;} ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="country">Country</label>
                                                                <input type="text" class="form-control" name="country" id="country" placeholder="Country" value="<?php echo $country; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="city">City</label>
                                                                <input type="text" class="form-control" name="city" id="city" placeholder="City" value="<?php echo $city; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="city">State</label>
                                                                <input type="text" class="form-control" name="state" id="state" placeholder="State" value="<?php echo $state; ?>">
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
                                                                    <input type="text" class="form-control" name="appointment_contact" id="appointment_contact" placeholder="Appointment Contact" value="<?php echo $appointment_contact; ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="office_contact">Office Contact</label>
                                                                    <input type="text" class="form-control" name="office_contact" id="office_contact" placeholder="Office Contact" value="<?php echo $office_contact; ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="mobile">Mobile</label>
                                                                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo $mobile; ?>">
                                                                </div>
                                                            </div><!-- /.box-body -->
                                                    </div><!-- /.box -->
                                            </div>
                                            <div class="col-md-6">
                                                <div class="box box-info">
                                                    <div class="box-header">
                                                        <h3 class="box-title">Upload Pic</h3>
                                                    </div><!-- /.box-header -->
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <input type="file" name="pic" id="pic">
                                                                <input type='button' name='user_pic_button' id='user_pic_button' onclick="submitUserImage('<?php echo $uid; ?>','<?php echo $auth_key; ?>');" style='display:none;'>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <div id="showpic">
                                                                    <img width="100" height="100" src="<?php echo $web_server.$profile_pic; ?>" alt="User picture">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- /.box-body -->
                                                </div>
                                                <div class="box box-danger">
                                                        <div class="box-header">
                                                            <h3 class="box-title">Other Info</h3>
                                                        </div><!-- /.box-header -->
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <label for="practice_since">Practice Since</label>
                                                                <input type="text" class="form-control" name="practice_since" id="practice_since" placeholder="Practicing Since" value="<?php echo $practice_since; ?>">
                                                            </div>
<!--                                                            <div class="form-group">
                                                                <label for="bio">Biography</label>
                                                                <textarea class="form-control" rows="3" name="bio" id="bio" placeholder="Biography"><?php echo $bio; ?></textarea>
                                                            </div>-->
                                                            <div class="form-group">
                                                                <label for="contact_note">Contact Note</label>
                                                                <textarea class="form-control" rows="3" name="contact_note" id="contact_note" placeholder="Contact Note"><?php echo strip_tags($contact_note); ?></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="language">Language</label>
                                                                <select class="form-control" name="language" id="language">
                                                                    <option value="english" <?php if($language=='english'){echo 'selected';} ?>>English</option>
                                                                    <option value="hindi" <?php if($language=='hindi'){echo 'selected';} ?>>Hindi</option>
                                                                </select>
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
                                                                <input type="text" class="form-control" name="youtube_url" id="youtube_url" placeholder="Youtube URL" value="<?php echo $youtube_url; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="twitter_url">Twitter URL</label>
                                                                <input type="text" class="form-control" name="twitter_url" id="twitter_url" placeholder="Twitter URL" value="<?php echo $twitter_url; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="linkedin_url">Linkedin URL</label>
                                                                <input type="text" class="form-control" name="linkedin_url" id="linkedin_url" placeholder="Linkedin URL" value="<?php echo $linkedin_url; ?>">
                                                            </div>
                                                        </div><!-- /.box-body -->
                                                </div><!-- /.box -->
                                            </div>


                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                        </div>  
                                        </form>
                                    </div><!-- /.tab-pane -->
                                    <div class="tab-pane" id="tab_2">
                                        <div class="row">
                                                
                                                    <img style="float: left; margin: 8px;margin-right: 2px;margin-bottom: 2px;" src="../img/add.jpg" width="30px">
                                                    <h3 class="box-title"><a href="javascript:;" onclick="associationForm('<?php echo $uid; ?>','<?php echo $auth_key; ?>')">Add New Professional Experience</a></h3>
                                                
                                                <?php
                                                    if($result_profile['status']==1){
                                                        if(count($result_profile['posts']['association'])!=0){
                                                            for($i=0;$i<count($result_profile['posts']['association']);$i++){
                                                                $assoc_id = $result_profile['posts']['association'][$i]['association_id'];
                                                ?>
                                                                <div class="col-lg-4 col-xs-8">
                                                                    <!-- small box -->
                                                                    <div class="small-box bg-yellow">
                                                                        <div class="inner">
                                                                            <p>
                                                                                Association Name : 
                                                                                <?php echo $result_profile['posts']['association'][$i]['association_name']; ?>
                                                                            </p>
                                                                            <p>
                                                                                Position : 
                                                                                <?php echo $result_profile['posts']['association'][$i]['position']; ?>
                                                                            </p>
                                                                            <p>
                                                                                location : 
                                                                                <?php echo $result_profile['posts']['association'][$i]['location']; ?>
                                                                            </p>
                                                                            <p>
                                                                                Start : 
                                                                                <?php echo $result_profile['posts']['association'][$i]['start_date']; ?>
                                                                            </p>
                                                                            <p>
                                                                                End : 
                                                                                <?php echo $result_profile['posts']['association'][$i]['end_date']; ?>
                                                                            </p>
                                                                        </div>
                                                                        <div class="icon">
                                                                            <i class="ion ion-person-add"></i>
                                                                        </div>
                                                                        <a href="javascript:;" onclick="editAssociationForm('<?php echo $assoc_id; ?>','<?php echo $uid; ?>','<?php echo $auth_key; ?>');" class="small-box-footer">
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
                                    </div><!-- /.tab-pane -->
                                    <div class="tab-pane" id="tab_3">
                                        <div class="row">
                                                
                                                    <img style="float: left; margin: 8px;margin-right: 2px;margin-bottom: 2px;" src="../img/add.jpg" width="30px">
                                                    <h3 class="box-title"><a href="javascript:;" onclick="educationForm('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">Add New Education</a></h3>
                                                
                                                <?php
                                                    if($result_profile['status']==1){
                                                        if(count($result_profile['posts']['education'])!=0){
                                                            for($i=0;$i<count($result_profile['posts']['education']);$i++){
                                                                $education_id = $result_profile['posts']['education'][$i]['education_id'];
                                                ?>
                                                                <div class="col-lg-4 col-xs-8">
                                                                    <!-- small box -->
                                                                    <div class="small-box bg-yellow">
                                                                        <div class="inner">
                                                                            <p>
                                                                                School/College Name : 
                                                                                <?php echo $result_profile['posts']['education'][$i]['school']; ?>
                                                                            </p>
                                                                            <p>
                                                                                Start : 
                                                                                <?php echo $result_profile['posts']['education'][$i]['start_date']; ?>
                                                                            </p>
                                                                            <p>
                                                                                End : 
                                                                                <?php echo $result_profile['posts']['education'][$i]['end_date']; ?>
                                                                            </p>
    <!                                                                      <p>
                                                                                Degree : 
                                                                                <?php echo $result_profile['posts']['education'][$i]['degree']; ?>
                                                                            </p>  
                                                                            <p>
                                                                                Field Of Study : 
                                                                                <?php echo $result_profile['posts']['education'][$i]['field_of_study']; ?>
                                                                            </p>
                                                                            <p>
                                                                                Grade : 
                                                                                <?php echo $result_profile['posts']['education'][$i]['grade']; ?>
                                                                            </p>  
                                                                        </div>
                                                                        <div class="icon">
                                                                            <i class="ion ion-person-add"></i>
                                                                        </div>
                                                                        <a href="javascript:;" onclick="editEducationForm('<?php echo $education_id; ?>','<?php echo $uid; ?>','<?php echo $auth_key; ?>');" class="small-box-footer">
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
                                    </div>
                                    <div class="tab-pane" id="tab_4">
                                        <div class="row">
                                                
                                                    <img style="float: left; margin: 8px;margin-right: 2px;margin-bottom: 2px;" src="../img/add.jpg" width="30px">
                                                    <h3 class="box-title"><a href="javascript:;" onclick="interestForm('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">Add Interests</a></h3>
                                                
                                                <?php
                                                    if($result_profile['status']==1){
                                                        if(count($result_profile['posts']['interest'])!=0){
                                                            for($i=0;$i<count($result_profile['posts']['interest']);$i++){
                                                                $interest_id = $result_profile['posts']['interest'][$i]['interest_id'];
                                                ?>
                                                                <div class="col-lg-4 col-xs-8">
                                                                    <!-- small box -->
                                                                    <div class="small-box bg-yellow">
                                                                        <div class="inner">
                                                                            <p>
                                                                                Interest Name : 
                                                                                <?php echo $result_profile['posts']['interest'][$i]['interest_name']; ?>
                                                                            </p>
                                                                        </div>
                                                                        <div class="icon">
                                                                            <i class="ion ion-person-add"></i>
                                                                        </div>
                                                                        <a href="javascript:;" onclick="editInterestForm('<?php echo $interest_id; ?>','<?php echo $uid; ?>','<?php echo $auth_key; ?>');" class="small-box-footer">
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
                                    </div>
                                    <div class="tab-pane" id="tab_5">
                                         <div class="row">
                                                
                                                    <img style="float: left; margin: 8px;margin-right: 2px;margin-bottom: 2px;" src="../img/add.jpg" width="30px">
                                                    <h3 class="box-title"><a href="javascript:;" onclick="specialityForm('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">Add New Speciality</a></h3>
                                                
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
                                                                        <a href="javascript:;" onclick="editSpecialityForm('<?php echo $speciality_id; ?>','<?php echo $uid; ?>','<?php echo $auth_key; ?>');" class="small-box-footer">
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
                                    </div>
                                    <div class="tab-pane" id="tab_6">
                                        <div class="row">
                                                
                                                    <img style="float: left; margin: 8px;margin-right: 2px;margin-bottom: 2px;" src="../img/add.jpg" width="30px">
                                                    <h3 class="box-title"><a href="javascript:;" onclick="awardForm('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">Add New Award</a></h3>
                                                
                                                <?php
                                                    if($result_profile['status']==1){
                                                        if(count($result_profile['posts']['award'])!=0){
                                                            for($i=0;$i<count($result_profile['posts']['award']);$i++){
                                                                $award_id = $result_profile['posts']['award'][$i]['award_id'];
                                                ?>
                                                                <div class="col-lg-6 col-xs-8">
                                                                    <!-- small box -->
                                                                    <div class="small-box bg-yellow">
                                                                        <div class="inner">
                                                                            <p>
                                                                                Award Title : 
                                                                                <?php echo $result_profile['posts']['award'][$i]['title']; ?>
                                                                            </p>
                                                                            <p>
                                                                                Award Description : 
                                                                                <?php echo $result_profile['posts']['award'][$i]['description']; ?>
                                                                            </p>
                                                                            <p>
                                                                                Award Date : 
                                                                                <?php echo $result_profile['posts']['award'][$i]['date']; ?>
                                                                            </p>
                                                                            <p>
                                                                                Award Link : 
                                                                                <?php echo $result_profile['posts']['award'][$i]['live_link']; ?>
                                                                            </p>
                                                                        </div>
                                                                        <div class="icon">
                                                                            <i class="ion ion-person-add"></i>
                                                                        </div>
                                                                        <a href="javascript:;" onclick="editAwardForm('<?php echo $award_id; ?>','<?php echo $uid; ?>','<?php echo $auth_key; ?>');" class="small-box-footer">
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
                                    </div>
                                    <div class="tab-pane" id="tab_7">
                                        <div class="row">
                                                
                                                <img style="float: left; margin: 8px;margin-right: 2px;margin-bottom: 2px;" src="../img/add.jpg" width="30px">
                                                <h3 class="box-title"><a href="javascript:;" onclick="researchForm('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">Add New Research</a></h3>
                                                
                                                <?php
                                                    if($result_profile['status']==1){
                                                        if(count($result_profile['posts']['research'])!=0){
                                                            for($i=0;$i<count($result_profile['posts']['research']);$i++){
                                                                $research_id = $result_profile['posts']['research'][$i]['research_id'];
                                                ?>
                                                                <div class="col-lg-4 col-xs-8">
                                                                    <!-- small box -->
                                                                    <div class="small-box bg-yellow">
                                                                        <div class="inner">
                                                                            <p>
                                                                                Research Title : 
                                                                                <?php echo $result_profile['posts']['research'][$i]['title']; ?>
                                                                            </p>
                                                                            <p>
                                                                                Research Summary : 
                                                                                <?php echo $result_profile['posts']['research'][$i]['summary']; ?>
                                                                            </p>
                                                                        </div>
                                                                        <div class="icon">
                                                                            <i class="ion ion-person-add"></i>
                                                                        </div>
                                                                        <a href="javascript:;" onclick="editResearchForm('<?php echo $research_id; ?>','<?php echo $uid; ?>','<?php echo $auth_key; ?>');" class="small-box-footer">
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
                                    </div>
                                    <div class="tab-pane" id="tab_8">
                                        <div class="row">
                                                
                                                <img style="float: left; margin: 8px;margin-right: 2px;margin-bottom: 2px;" src="../img/add.jpg" width="30px">
                                                <h3 class="box-title"><a href="javascript:;" onclick="projectForm('<?php echo $uid; ?>','<?php echo $auth_key; ?>');">Add New Project</a></h3>
                                                
                                                <?php
                                                    if($result_profile['status']==1){
                                                        if(count($result_profile['posts']['research'])!=0){
                                                            for($i=0;$i<count($result_profile['posts']['research']);$i++){
                                                                if(count($result_profile['posts']['research'][$i]['project'])!=0){
                                                                    for($j=0;$j<count($result_profile['posts']['research'][$i]['project']);$j++){
                                                                        $research_id = $result_profile['posts']['research'][$i]['research_id'];
                                                                        $project_id = $result_profile['posts']['research'][$i]['project'][$j]['project_id'];
                                                ?>
                                                                        <div class="col-lg-4 col-xs-8">
                                                                            <!-- small box -->
                                                                            <div class="small-box bg-yellow">
                                                                                <div class="inner">
                                                                                    <p>
                                                                                        Project Title : 
                                                                                        <?php echo $result_profile['posts']['research'][$i]['project'][$j]['title']; ?>
                                                                                    </p>
                                                                                    <p>
                                                                                        Project Description : 
                                                                                        <?php echo $result_profile['posts']['research'][$i]['project'][$j]['description']; ?>
                                                                                    </p>
                                                                                    <p>
                                                                                        Project Caption : 
                                                                                        <?php echo $result_profile['posts']['research'][$i]['project'][$j]['caption']; ?>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="icon">
                                                                                    <i class="ion ion-person-add"></i>
                                                                                </div>
                                                                                <a href="javascript:;" onclick="editProjectForm('<?php echo $research_id; ?>','<?php echo $project_id; ?>','<?php echo $uid; ?>','<?php echo $auth_key; ?>');" class="small-box-footer">
                                                                                    Edit <i class="fa fa-arrow-circle-right"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                
                                                <?php
                                                                    }
                                                                }
                                               
                                                            }
                                                        }
                                                    }
                                                ?>
                                            
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_9">
                                        <div class="row">
                                            <div class="col-md-8 col-md-offset-2">
                                                <form name="admin_edit_summary_form" id="admin_edit_summary_form" method="post">
                                                    <input type="hidden" name="uid" value="<?php echo $uid; ?>">
                                                    <input type="hidden" name="auth_key" value="<?php echo $auth_key; ?>">
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
                                    </div>
                                </div><!-- /.tab-content -->
                            </div>
                        </div>
                    </div>
                </div>
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
        <script src="../js/bootstrap-datepicker/bootstrap-datepicker/assets/lib/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="../js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(function() {
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                //CKEDITOR.replace('editor1');
                //bootstrap WYSIHTML5 - text editor
                $("#summary").wysihtml5();
            });
        </script>

        <!-- Jquery Validate -->
        <script src="../js/jquery.validate.min.js" type="text/javascript"></script>
<!--        <script src="../js/validator/admin_edit_user.init.js" type="text/javascript"></script>-->
<!--        <script src="../js/validator/admin_add_association.init.js" type="text/javascript"></script>-->
        <script src="../js/validator/admin_edit_summary.init.js" type="text/javascript"></script>
        <script>
            $(function(){
                $('#dob').datepicker({
                    format: 'yyyy-mm-dd',
                    startView: 1,
                    autoclose: true
                });
            });
        </script>
    </body>
    <script type="text/javascript">
        function associationForm(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_add_association.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_2').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function educationForm(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_add_education.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_3').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function interestForm(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_add_interest.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_4').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function specialityForm(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_add_speciality.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_5').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function awardForm(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_add_award.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_6').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function researchForm(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_add_research.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_7').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function projectForm(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_add_project.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_8').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function editAssociationForm(assoc_id, uid, auth_key){
            $.ajax({
                url: "../ajax/admin_edit_association.php?assoc_id="+assoc_id+"&uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_2').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function editEducationForm(education_id, uid, auth_key){
            $.ajax({
                url: "../ajax/admin_edit_education.php?education_id="+education_id+"&uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_3').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function editInterestForm(interest_id, uid, auth_key){
            $.ajax({
                url: "../ajax/admin_edit_interest.php?interest_id="+interest_id+"&uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_4').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function editSpecialityForm(speciality_id, uid, auth_key){
            $.ajax({
                url: "../ajax/admin_edit_speciality.php?speciality_id="+speciality_id+"&uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_5').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function editAwardForm(award_id, uid, auth_key){
            $.ajax({
                url: "../ajax/admin_edit_award.php?award_id="+award_id+"&uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_6').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function editResearchForm(research_id, uid, auth_key){
            $.ajax({
                url: "../ajax/admin_edit_research.php?research_id="+research_id+"&uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_7').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function editProjectForm(research_id, project_id, uid, auth_key){
            $.ajax({
                url: "../ajax/admin_edit_project.php?research_id="+research_id+"&project_id="+project_id+"&uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_8').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function showAssociation(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_show_association.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_2').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function showEducation(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_show_education.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_3').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function showInterests(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_show_interests.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_4').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function showSpeciality(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_show_speciality.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_5').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function showAward(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_show_award.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_6').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function showResearch(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_show_research.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_7').html(data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        function showProject(uid, auth_key){
            $.ajax({
                url: "../ajax/admin_show_project.php?uid="+uid+"&auth_key="+auth_key,
                success: function(data){
                    $('#tab_8').html(data);
                }
            });
        }
    </script>
    <script>
        // Update User Pic
        $(function(){
            document.getElementById("pic").onchange = function() {
                document.getElementById("user_pic_button").click();
            };
        });
        function submitUserImage(uid, auth_key){
            var fileInput = $('#pic')[0];
            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append('file', file);
            $.ajax({
                url:"../ajax/admin_update_user_pic.php?uid="+uid+"&auth_key="+auth_key,
                type:"post",
                data:formData,
                success:function(data){//alert(data);
                    var arr=data.split(",");
                    var status=arr[0];
                    var imageTag=arr[1];
                    if(status==1){
                        $('#showpic').html(imageTag);
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
        // End User Pic
    </script>
</html>
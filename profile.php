<?php
    session_start();
    require 'connect/setting.php';
    $_SESSION['user_id'] = '1';
    $user_id = $_SESSION['user_id'];
    // Get Custom Id
    $cid = $_GET['cid']; 
    //echo $cid; die;
    if($cid==''){
        // redirect to home page
    }
// Code To Get Custom Id
//    $doctor_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//    $doctor_path = parse_url($doctor_url, PHP_URL_PATH);
//    $doctor_value = strrchr($doctor_path,"-");
//    $doctor_code = ltrim(rtrim($doctor_value,'.html'),'-');
//    $cid=$doctor_code; //Custom Id
//$custom_id = $_GET['cid'];
// End Code To Get Custom Id   
//  $uid=$_GET['uid'];
//  $auth_key=$_GET['auth_key'];
//    $uid = '7';
    //$auth_key = '143012456241';
    $url = $web_server.'connect/webservice/getApi.php?rquest=GetProfile';
    //print_r($_POST);die;
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $fields = array(
        'user_id'          => $cid,
        //'user_auth_key'    => $auth_key,
        'source_user_id'   => $user_id,
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
//  echo '<pre>';
//  print_r($result_profile);
//  echo '</pre>';die;
     
     $status = $result_profile_data['posts']['status'];
     if($status == 1){
        //$result_profile = json_decode($serailise_data,true);
        $result_profile = $result_profile_data['posts'];
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
        // Get Education Count
        if($result_profile['posts']['education']!=''){
            $education_count = count($result_profile['posts']['education']);
        }
        // Get Association Count
        if($result_profile['posts']['association']!=''){
            $association_count = count($result_profile['posts']['association']);
        }
        // Get Award Count
        if($result_profile['posts']['award']!=''){
            $award_count = count($result_profile['posts']['award']);
        }
        // Get Research Count
        if($result_profile['posts']['research']!=''){
            $research_count = count($result_profile['posts']['research']);
        }
        // Get Interest Count
        if($result_profile['posts']['interest']!=''){
            $interest_count = count($result_profile['posts']['interest']);
        }
        // Get Speciality Count
        if($result_profile['posts']['speciality']!=''){
            $speciality_count = count($result_profile['posts']['speciality']);
        }
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

        $education_count=0;
        $association_count=0;
        $award_count=0;
        $research_count=0;
        $interest_count=0;
        $speciality_count=0;
     } 
     $title = $first_name.' '.$last_name;
     if($country!=''){
         $location = $city.', '.$country;
     }
     else{
         $location = $city;
     }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            <?php
                if($location!=''){
                    echo $title.', '.$location;
                }
                else{
                    echo $title;
                } 
            ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="author" content="">



        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <meta name="description" content="" />
        <meta name="keywords" content="" />

        <link rel="shortcut icon" href="">

        <!--CSS styles-->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">  
        <link rel="stylesheet" href="css/perfect-scrollbar-0.4.5.min.css">
        <link rel="stylesheet" href="css/magnific-popup.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/customStyle.css">
        <link id="theme-style" rel="stylesheet" href="css/styles/default.css">

        
        <!--/CSS styles-->
        <!--Javascript files-->
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="js/TweenMax.min.js"></script>
        <script type="text/javascript" src="js/jquery.touchSwipe.min.js"></script>
        <script type="text/javascript" src="js/jquery.carouFredSel-6.2.1-packed.js"></script>
        
        <script type="text/javascript" src="js/modernizr.custom.63321.js"></script>
        <script type="text/javascript" src="js/jquery.dropdownit.js"></script>

        <script type="text/javascript" src="js/jquery.stellar.min.js"></script>
        <script type="text/javascript" src="js/ScrollToPlugin.min.js"></script>

        <script type="text/javascript" src="js/bootstrap.min.js"></script>

        <script type="text/javascript" src="js/jquery.mixitup.min.js"></script>

        <script type="text/javascript" src="js/masonry.min.js"></script>

        <script type="text/javascript" src="js/perfect-scrollbar-0.4.5.with-mousewheel.min.js"></script>

        <script type="text/javascript" src="js/magnific-popup.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>

        <!--/Javascript files-->


        <!--Custom Styles for demo only-->
        <link rel="stylesheet" href="custom-style.css">
        <script type="text/javascript" src="custom-style.js"></script>
        <link rel="stylesheet" href="css/customStyle.css">
        <!--/Custom Styles-->

    </head>
    <body>

        <div id="wrapper">
            <a href="#sidebar" class="mobilemenu"><i class="icon-reorder"></i></a>

            <div id="sidebar">
                <div id="main-nav">
                    <div id="nav-container">
                        <div id="profile" class="clearfix">
                            <div class="portrate hidden-xs" style="background-image: url(<?php echo $web_server.$profile_pic; ?>)">
                            </div>
                            <div class="title">
                                <h2><?php echo ucfirst($first_name).' '.ucfirst($last_name); ?></h2>
                                <h3><?php echo $location ?></h3>
                            </div>
                            
                        </div>
                        <ul id="navigation">
                            <li>
                              <a href="#biography">
                                <div class="icon icon-user"></div>
                                <div class="text">About Me</div>
                              </a>
                            </li>  
                            
                            <li>
                              <a href="#research">
                                <div class="icon icon-book"></div>
                                <div class="text">Contact Me</div>
                              </a>
                            </li> 
                            
<!--                            <li>
                              <a href="#contact">
                                <div class="icon icon-edit"></div>
                                <div class="text">Contact Me</div>
                              </a>
                            </li> -->

<!--                            <li>
                              <a href="#teaching">
                                <div class="icon icon-time"></div>
                                <div class="text">Teaching</div>
                              </a>
                            </li>

                            <li>
                              <a href="#gallery">
                                <div class="icon icon-picture"></div>
                                <div class="text">Gallery</div>
                              </a>
                            </li>

                            <li>
                              <a href="#contact">
                                  <div class="icon icon-calendar"></div>
                                  <div class="text">Contact & Meet Me</div>
                              </a>
                            </li>

                            <li class="external">
                              <a href="#">
                                  <div class="icon icon-download-alt"></div>
                                  <div class="text">Download CV</div>
                              </a>
                            </li>-->
                        </ul>
                    </div>        
                </div>
                
                <div class="social-icons">
                    <ul>
                        <li><a href="http://<?php echo $youtube_url; ?>"><i class="icon-youtube"></i></a></li>
                        <li><a href="http://<?php echo $twitter_url; ?>"><i class="icon-twitter"></i></a></li>
                        <li><a href="http://<?php echo $linkedin_url; ?>"><i class="icon-linkedin"></i></a></li>
                    </ul>
                </div>    
            </div>

            <div id="main">
            
                <div id="biography" class="page home customization" data-pos="home">
                    <div class="pageheader">
                        <div class="headercontent">
                            <div class="section-container">
                                
                                <div class="row">
                                    
                                    <div class="col-sm-2 visible-sm"></div>
                                    <div class="col-sm-8 col-md-5">
                                        <div class="biothumb">
                                            <?php
                                                if($profile_pic!=''){
                                            ?>
                                                    <img alt="image" src="<?php echo $web_server.$profile_pic; ?>"  class="img-responsive" width="100%">
                                            <?php
                                                }
                                            ?>
                                            <div class="overlay">
                                                
                                                <h1 class=""><?php echo ucfirst($first_name).' '.ucfirst($last_name); ?></h1>
                                                <ul class="list-unstyled">                                                   
                                                    <li><?php //echo $result_profile['posts']['education'][$education_count-1]['degree']; ?></li>
                                                    <li><?php echo ucfirst($result_profile['posts']['speciality'][0]['speciality_name']); ?></li>  
                                                    <li><?php //echo ucfirst($result_profile['posts']['education'][$education_count-1]['school']); ?></li>
                                                    <li><?php echo $location; ?></li>
                                                </ul>
                                            </div>    
                                        </div>
                                    </div>
                                    <div class="clearfix visible-sm visible-xs"></div>
                                    <div class="col-sm-12 col-md-7">
                                        <h3 class="title margin-no">Bio</h3>
                                        <p style="text-align: justify;"><?php echo $bio; ?></p>
                                    </div>  
                                    
                                </div>
                            </div>        
                        </div>
                    </div>

                    <div class="pagecontents">
                        <div class="section color-1">
                            <div class="section-container">
                                <div class="row">
                                    <div class="col-md-5 col-md-offset-1">
                                        <div class="title text-center">
                                            <h3>Professional Experience</h3>
                                        </div>
                                        <ul class="ul-dates">
                                            <?php
                                                for($i=0;$i<$association_count;$i++){
                                            ?>
                                                    <li>
                                                        <div class="dates">
                                                            <span>
                                                                <?php 
                                                                    if($result_profile['posts']['association'][$i]['end_date']!=''){
                                                                        echo $result_profile['posts']['association'][$i]['end_date'];
                                                                    }
                                                                    else{
                                                                        echo '-';
                                                                    }
                                                                ?>
                                                            </span>
                                                            <span>
                                                                <?php 
                                                                    if($result_profile['posts']['association'][$i]['start_date']!=''){
                                                                        echo $result_profile['posts']['association'][$i]['start_date']; 
                                                                    }
                                                                    else{
                                                                        echo '-';
                                                                    }
                                                                ?>
                                                            </span>
                                                        </div>
                                                        <div class="content">
                                                            <h4><?php echo ucfirst($result_profile['posts']['association'][$i]['position']); ?></h4>
                                                            <p><em><?php echo ucfirst($result_profile['posts']['association'][$i]['association_name']); ?></em>, <?php echo $result_profile['posts']['association'][$i]['location']; ?></p>
                                                        </div>
                                                    </li>
                                            
                                            <?php
                                                }
                                            
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="title text-center">
                                            <h3>Education</h3>
                                        </div>
                                        <ul class="ul-card">
                                            <?php
                                                for($i=0;$i<$education_count;$i++){
                                            ?>
                                                    <li>
                                                        <div class="dy">
                                                            <span class="degree">
                                                                <?php 
                                                                    if(strlen($result_profile['posts']['education'][$i]['degree'])>18){
                                                                        echo substr($result_profile['posts']['education'][$i]['degree'],0,18).'...'; 
                                                                    }
                                                                    else{
                                                                        echo $result_profile['posts']['education'][$i]['degree'];
                                                                    }
                                                                ?>
                                                            </span>
                                                            <span class="year">
                                                                <?php 
                                                                    if($result_profile['posts']['education'][$i]['start_date']!=0 && $result_profile['posts']['education'][$i]['end_date']!=0){
                                                                        echo $result_profile['posts']['education'][$i]['start_date']; 
                                                                        echo '-'.$result_profile['posts']['education'][$i]['end_date'];
                                                                    }
                                                                    else if($result_profile['posts']['education'][$i]['start_date']==0 && $result_profile['posts']['education'][$i]['end_date']!=0){
                                                                        echo $result_profile['posts']['education'][$i]['end_date'];
                                                                    }
                                                                    else if($result_profile['posts']['education'][$i]['start_date']!=0 && $result_profile['posts']['education'][$i]['end_date']==0){
                                                                        echo $result_profile['posts']['education'][$i]['start_date'];
                                                                    }
                                                                    else{
                                                                        echo '-';
                                                                    }
                                                                ?>
                                                            </span>
                                                        </div>
                                                        <div class="description">
                                                            <p class="waht"><?php echo ucfirst($result_profile['posts']['education'][$i]['field_of_study']); ?></p>
                                                            <p class="where"><?php echo ucfirst($result_profile['posts']['education'][$i]['school']); ?></p>
                                                        </div>
                                                    </li>
                                            <?php
                                                }
                                            ?>
                                        </ul>

                                    </div>    
                                </div>    
                            </div>
                                
                        </div>
                        <?php if($award_count!=0){ ?>
                        <div class="section color-2">
                            <div class="section-container">
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="title text-center">
                                            <h3>Honors, Awards and Grants</h3>
                                        </div>
                                        <ul class="timeline">
                                            <?php
                                                for($i=0;$i<$award_count;$i++){
                                            ?>
                                                    <li class="">
                                                        <div class="date">
                                                            <?php 
                                                                $award_date=$result_profile['posts']['award'][$i]['date']; 
                                                                if($award_date!='0000-00-00'){
                                                                    $award_date_new=date("Y",  strtotime($award_date));
                                                                    echo $award_date_new;
                                                                }
                                                                else{
                                                                    echo '-';
                                                                } 
                                                            ?>
                                                        </div>
                                                        <div class="circle"></div>
                                                        <div class="data">
                                                            <div class="subject"><?php echo ucfirst($result_profile['posts']['award'][$i]['title']); ?></div>
                                                            <div class="text row">
                                                                <div class="col-md-2">
                                                                    <?php
                                                                        if($result_profile['posts']['award'][$i]['award_pic']!=''){
                                                                    ?>
                                                                            <img alt="image" class="thumbnail img-responsive" src="<?php echo $result_profile['posts']['award'][$i]['award_pic']; ?>" >
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <?php echo ucfirst($result_profile['posts']['award'][$i]['description']); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                            <?php
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>   
                        <?php } ?>
                    </div>
                </div>

                <div id="research" class="page">
                    <div class="pageheader">

                        <div class="headercontent">

                            <div class="section-container">
                                <h2 class="title">Research Summary</h2>
                            
                                <div class="row">
                                    <div class="col-md-8">
                                        <ul class="list-unstyled" style="width: 300px;">
                                            <?php if($office_contact!=''){ ?>
                                            <li>
                                                <strong><i class="icon-phone"></i>&nbsp;&nbsp;</strong>
                                                <span>office: <?php  echo $office_contact; ?></span>
                                            </li>
                                            <?php } ?>
                                            <?php if($appointment_contact!=''){ ?>
                                            <li>
                                                <strong><i class="icon-phone"></i>&nbsp;&nbsp;</strong>
                                                <span>appointment: <?php echo $appointment_contact; ?></span>
                                            </li>
                                            <?php } ?>
                                            <?php if($email!=''){ ?>
                                            <li>
                                                <strong><i class="icon-envelope"></i>&nbsp;&nbsp;</strong>
                                                <span><?php echo $email; ?></span>
                                            </li>
                                            <?php } ?>
<!--                                            <li>
                                                <strong><i class="icon-envelope"></i>&nbsp;&nbsp;</strong>
                                                <span>jdoe@gmail.com</span>
                                            </li>-->
                                            <?php if($youtube_url!=''){ ?>
                                            <li>
                                                <strong><i class="icon-youtube"></i>&nbsp;&nbsp;</strong>
                                                <span><a href="http://<?php echo $youtube_url; ?>"><?php echo $youtube_url; ?></a></span>
                                            </li>
                                            <?php } ?>
                                            <?php if($twitter_url!=''){ ?>
                                            <li>
                                                <strong><i class="icon-twitter"></i>&nbsp;&nbsp;</strong>
                                                <span><a href="http://<?php echo $twitter_url; ?>"><?php echo $twitter_url; ?></a></span>
                                            </li>
                                            <?php } ?>
                                            <?php if($linkedin_url!=''){ ?>
                                            <li>
                                                <strong><i class="icon-linkedin-sign"></i>&nbsp;&nbsp;</strong>
                                                <span><a style="font-size: 0.9em;" href="http://<?php echo $linkedin_url; ?>"><?php echo $title; ?></a></span>
                                            </li>
                                            <?php } ?>
                                        </ul>    
                                        <div style="border: solid;height: 150px; width: 250px">
                                            
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    
                                    
<!--                                    <div class="col-md-8">
                                        <?php
                                           // for($i=0;$i<$research_count;$i++){
                                        ?>
                                                <h3><?php //echo $result_profile['posts']['research'][$i]['title']; ?></h3>
                                                <p><?php //echo $result_profile['posts']['research'][$i]['summary']; ?></p>
                                        <?php
                                           // }
                                        ?>
                                        <p> </p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                    </div>-->
                                    <?php if($interest_count!=0){ ?>
                                    <div class="col-md-4">
                                        <div class="subtitle text-center">
                                            <h3>Interests</h3>
                                        </div>
                                        <ul class="ul-boxed list-unstyled">
                                            <?php
                                                for($i=0;$i<$interest_count;$i++){
                                            ?>
                                                    <li><?php echo $result_profile['posts']['interest'][$i]['interest_name'] ?></li>
                                            <?php
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pagecontents">
                    <!--    <div class="section color-1">
                            <div class="section-container">
                                <div class="title text-center">
                                    <h3>Laboratory Personel</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        
                                        <div id="labp-heads-wrap">
                                            
                                            <div id="lab-carousel">
                                                <div><img alt="image" src="img/lab/lab5.jpg" width="120" height="120" class="img-circle lab-img" /></div>
                                                <div><img alt="image" src="img/lab/lab2.jpg" width="120" height="120" class="img-circle lab-img" /></div>
                                                <div><img alt="image" src="img/lab/lab1.jpg" width="120" height="120" class="img-circle lab-img" /></div>
                                                <div><img alt="image" src="img/lab/lab4.jpg" width="120" height="120" class="img-circle lab-img" /></div>
                                                <div><img alt="image" src="img/lab/lab3.jpg" width="120" height="120" class="img-circle lab-img" /></div>
                                                <div><img alt="image" src="img/lab/lab6.jpg" width="120" height="120" class="img-circle lab-img" /></div>
                                            </div>
                                            <div>
                                                <a href="#" id="prev"><i class="icon-chevron-sign-left"></i></a>
                                                <a href="#" id="next"><i class="icon-chevron-sign-right"></i></a>
                                            </div>
                                        </div>

                                        <div id="lab-details">
                                            <div>
                                                <h3>David A. Doe</h3>
                                                <h4>Postdoctoral fellow</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                            <div>
                                                <h3>James Doe</h3>
                                                <h4>Postdoctoral fellow</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                            <div>
                                                <h3>Nadja Sriram</h3>
                                                <h4>Postdoctoral fellow</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                            <div>
                                                <h3>Davide Doe</h3>
                                                <h4>Research Assistant</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                            <div>
                                                <h3>Pauline Doe</h3>
                                                <h4>Summer Intern</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                            <div>
                                                <h3>James Doe</h3>
                                                <h4>Postdoctoral fellow</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h3>Great lab Personel!</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <?php if($research_count!=0){ ?>
                        <div class="section color-2">
                            <div class="section-container">
                                <div class="title text-center">
                                    <h3>Research Projects</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="ul-withdetails">
                                            <?php
                                                for($i=0;$i<$research_count;$i++){
                                                    if($result_profile['posts']['research'][$i]['project']!=0){
                                                        for($j=0;$j<count($result_profile['posts']['research'][$i]['project']);$j++){
                                            ?>
                                                            <li>
                                                                <div class="row">
                                                                    <div class="col-sm-6 col-md-3">
                                                                        <div class="image">
                                                                            <img alt="image" src="<?php echo $result_profile['posts']['research'][$i]['project'][$j]['project_image_path']; ?>"  class="img-responsive">
                                                                            <div class="imageoverlay">
                                                                                <i class="icon icon-search"></i>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6 col-md-9">
                                                                        <div class="meta">
                                                                            <h3><?php echo $result_profile['posts']['research'][$i]['project'][$j]['title']; ?></h3>
                                                                            <p><?php echo $result_profile['posts']['research'][$i]['project'][$j]['caption']; ?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="details">
                                                                    <p><?php echo $result_profile['posts']['research'][$i]['project'][$j]['description']; ?></p>
<!--                                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>-->
                                                                </div>
                                                            </li>
                                            <?php
                                                        }
                                                    }
                                            ?>
                                                    
                                            <?php
                                                }
                                            ?> 
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>   
                        <?php } ?>
                    </div>
                </div>

                <div id="publications" class="page">
                    <div class="page-container">
                        <div class="pageheader">
                            <div class="headercontent">
                                <div class="section-container">
                                    
                                    <h2 class="title">Selected Publications</h2>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="pagecontents">
                            
                            <div class="section color-1" id="filters">
                                <div class="section-container">
                                    <div class="row">
                                        
                                        <div class="col-md-3">
                                            <h3>Filter by type:</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <select id="cd-dropdown" name="cd-dropdown" class="cd-select">
                                                <option class="filter" value="all" selected>All types</option>
                                                <option class="filter" value="jpaper">Jounal Papers</option>
                                                <option class="filter" value="cpaper">Conference Papers</option>
                                                <option class="filter" value="bookchapter">Book Chapters</option>
                                                <option class="filter" value="book">Books</option>
                                                <!-- <option class="filter" value="report">Reports</option>
                                                <option class="filter" value="tpaper">Technical Papers</option> -->
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3" id="sort">
                                            <span>Sort by year:</span>
                                            <div class="btn-group pull-right"> 

                                                <button type="button" data-sort="data-year" data-order="desc" class="sort btn btn-default"><i class="icon-sort-by-order"></i></button>
                                                <button type="button" data-sort="data-year" data-order="asc" class="sort btn btn-default"><i class="icon-sort-by-order-alt"></i></button>
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </div>

                            <div class="section color-2" id="pub-grid">
                                <div class="section-container">
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pitems">
                                                
                                                <div class="item mix cpaper" data-year="2013">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-warning">Conference Papers</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2012, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>


                                                <div class="item mix book" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                            The Dragonfly Effect: Quick, Effective, and Powerful Ways To Use Social Media to Drive Social Change
                                                        </h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite">
                                                            <span class="label label-primary">Book</span> John Wiley & Sons | September 28, 2010 | <strong>ISBN-10:</strong> 0470614153
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <img alt="image" src="http://placehold.it/150x200"  style="padding:0 30px 30px 0;">
                                                        <h4>Proven strategies for harnessing the power of social media to drive social change</h4>
                                                        <p>Many books teach the mechanics of using Facebook, Twitter, and YouTube to compete in business. But no book addresses how to harness the incredible power of social media to make a difference. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <ul>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>.sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>Onsectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>

                                                <div class="item mix jpaper" data-year="2013">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-success">Journal Paper</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2012, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>

                                                <div class="item mix bookchapter" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                            The Dragonfly Effect: Quick, Effective, and Powerful Ways To Use Social Media to Drive Social Change
                                                        </h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite">
                                                            <span class="label label-info">Book Chapter</span> John Wiley & Sons | September 28, 2010 | <strong>ISBN-10:</strong> 0470614153
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <img alt="image" src="http://placehold.it/150x200"  style="padding:0 30px 30px 0;">
                                                        <h4>Proven strategies for harnessing the power of social media to drive social change</h4>
                                                        <p>Many books teach the mechanics of using Facebook, Twitter, and YouTube to compete in business. But no book addresses how to harness the incredible power of social media to make a difference. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <ul>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>.sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>Onsectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>

                                                <div class="item mix jpaper" data-year="2012">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-success">Journal Paper</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2012, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>
                                                <div class="item mix cpaper" data-year="2012">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-warning">Conference Papers</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2012, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>


                                                <div class="item mix book" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                            The Dragonfly Effect: Quick, Effective, and Powerful Ways To Use Social Media to Drive Social Change
                                                        </h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite">
                                                            <span class="label label-primary">Book</span> John Wiley & Sons | September 28, 2010 | <strong>ISBN-10:</strong> 0470614153
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <img alt="image" src="http://placehold.it/150x200"  style="padding:0 30px 30px 0;">
                                                        <h4>Proven strategies for harnessing the power of social media to drive social change</h4>
                                                        <p>Many books teach the mechanics of using Facebook, Twitter, and YouTube to compete in business. But no book addresses how to harness the incredible power of social media to make a difference. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <ul>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>.sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>Onsectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>

                                                <div class="item mix jpaper" data-year="2011">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-success">Journal Paper</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2012, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>

                                                <div class="item mix bookchapter" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                            The Dragonfly Effect: Quick, Effective, and Powerful Ways To Use Social Media to Drive Social Change
                                                        </h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite">
                                                            <span class="label label-info">Book Chapter</span> John Wiley & Sons | September 28, 2010 | <strong>ISBN-10:</strong> 0470614153
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <img alt="image" src="http://placehold.it/150x200"  style="padding:0 30px 30px 0;">
                                                        <h4>Proven strategies for harnessing the power of social media to drive social change</h4>
                                                        <p>Many books teach the mechanics of using Facebook, Twitter, and YouTube to compete in business. But no book addresses how to harness the incredible power of social media to make a difference. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <ul>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>.sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>Onsectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>

                                                <div class="item mix jpaper" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-success">Journal Paper</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2010, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>
                                                <div class="item mix cpaper" data-year="2011">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-warning">Conference Papers</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2011, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>


                                                <div class="item mix book" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                            The Dragonfly Effect: Quick, Effective, and Powerful Ways To Use Social Media to Drive Social Change
                                                        </h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite">
                                                            <span class="label label-primary">Book</span> John Wiley & Sons | September 28, 2010 | <strong>ISBN-10:</strong> 0470614153
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <img alt="image" src="http://placehold.it/150x200"  style="padding:0 30px 30px 0;">
                                                        <h4>Proven strategies for harnessing the power of social media to drive social change</h4>
                                                        <p>Many books teach the mechanics of using Facebook, Twitter, and YouTube to compete in business. But no book addresses how to harness the incredible power of social media to make a difference. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <ul>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>.sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>Onsectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>

                                                <div class="item mix jpaper" data-year="2009">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-success">Journal Paper</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2009, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>

                                                <div class="item mix bookchapter" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                            The Dragonfly Effect: Quick, Effective, and Powerful Ways To Use Social Media to Drive Social Change
                                                        </h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite">
                                                            <span class="label label-info">Book Chapter</span> John Wiley & Sons | September 28, 2010 | <strong>ISBN-10:</strong> 0470614153
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <img alt="image" src="http://placehold.it/150x200"  style="padding:0 30px 30px 0;">
                                                        <h4>Proven strategies for harnessing the power of social media to drive social change</h4>
                                                        <p>Many books teach the mechanics of using Facebook, Twitter, and YouTube to compete in business. But no book addresses how to harness the incredible power of social media to make a difference. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <ul>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>.sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>Onsectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>

                                                <div class="item mix jpaper" data-year="2008">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-success">Journal Paper</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2008, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div id="teaching" class="page">
                    <div class="pageheader">
                        <div class="headercontent">
                            <div class="section-container">
                                
                                <h2 class="title">Teaching</h2>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>                                                   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pagecontents">
                        <div class="section color-1">
                            <div class="section-container">
                                <div class="row">
                                    <div class="title text-center">
                                        <h3>Currrent Teaching</h3>
                                    </div>
                                    <ul class="ul-dates">
                                        <li>
                                            <div class="dates">
                                                <span>Present</span>
                                                <span>1995</span>
                                            </div>
                                            <div class="content">
                                                <h4>Preclinical Endodnotics</h4>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultrices ac elit sit amet porttitor. Suspendisse congue, erat vulputate pharetra mollis, est eros fermentum nibh, vitae rhoncus est arcu vitae elit.</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dates">
                                                <span>Present</span>
                                                <span>2003</span>
                                            </div>
                                            <div class="content">
                                                <h4>SELC 8160 Molar Endodontic Selective</h4>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultrices ac elit sit amet porttitor. Suspendisse congue, erat vulputate pharetra mollis, est eros fermentum nibh, vitae rhoncus est arcu vitae elit.</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dates">
                                                <span>Present</span>
                                                <span>2010</span>
                                            </div>
                                            <div class="content">
                                                <h4>Endodontics Postdoctoral AEGD Program</h4>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultrices ac elit sit amet porttitor. Suspendisse congue, erat vulputate pharetra mollis, est eros fermentum nibh, vitae rhoncus est arcu vitae elit.</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="section color-2">
                            <div class="section-container">
                                <div class="row">
                                    <div class="title text-center">
                                        <h3>Teaching History</h3>
                                    </div>
                                    <ul class="ul-dates-gray">
                                        <li>
                                            <div class="dates">
                                                <span>1997</span>
                                                <span>1995</span>
                                            </div>
                                            <div class="content">
                                                <h4>Preclinical Endodnotics</h4>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultrices ac elit sit amet porttitor. Suspendisse congue, erat vulputate pharetra mollis, est eros fermentum nibh, vitae rhoncus est arcu vitae elit.</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dates">
                                                <span>2005</span>
                                                <span>2003</span>
                                            </div>
                                            <div class="content">
                                                <h4>SELC 8160 Molar Endodontic Selective</h4>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultrices ac elit sit amet porttitor. Suspendisse congue, erat vulputate pharetra mollis, est eros fermentum nibh, vitae rhoncus est arcu vitae elit.</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dates">
                                                <span>2011</span>
                                                <span>2010</span>
                                            </div>
                                            <div class="content">
                                                <h4>Endodontics Postdoctoral AEGD Program</h4>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultrices ac elit sit amet porttitor. Suspendisse congue, erat vulputate pharetra mollis, est eros fermentum nibh, vitae rhoncus est arcu vitae elit.</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dates">
                                                <span>2011</span>
                                                <span>2010</span>
                                            </div>
                                            <div class="content">
                                                <h4>Endodontics Postdoctoral AEGD Program</h4>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultrices ac elit sit amet porttitor. Suspendisse congue, erat vulputate pharetra mollis, est eros fermentum nibh, vitae rhoncus est arcu vitae elit.</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dates">
                                                <span>2011</span>
                                                <span>2010</span>
                                            </div>
                                            <div class="content">
                                                <h4>Endodontics Postdoctoral AEGD Program</h4>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultrices ac elit sit amet porttitor. Suspendisse congue, erat vulputate pharetra mollis, est eros fermentum nibh, vitae rhoncus est arcu vitae elit.</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="gallery" class="page">
                    <div class="pagecontents">
                        
                        <div class="section color-3" id="gallery-header">
                            <div class="section-container">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h2>Gallery</h2>
                                    </div>
                                    <div class="col-md-9">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section color-3" id="gallery-large">
                            <div class="section-container">
                                
                                <ul id="grid" class="grid">
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/06.jpg">
                                            <a href="img/gallery/06.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <i class="icon-search"></i>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/02.jpg">
                                            <a href="img/gallery/02.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <h3>Image Title</h3>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/03.jpg">
                                            <a href="img/gallery/03.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <i class="icon-search"></i>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/04.jpg">
                                            <a href="img/gallery/04.jpg" class="popup-with-move-anim"> 
                                                <div class="over">
                                                    <div class="comein">
                                                        <h3>Image Title</h3>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/05.jpg">
                                            <a href="img/gallery/05.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <i class="icon-search"></i>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/01.jpg">
                                            <a href="img/gallery/01.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <h3>Image Title</h3>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/07.jpg">
                                            <a href="img/gallery/07.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <i class="icon-search"></i>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/08.jpg">
                                            <a href="img/gallery/08.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <i class="icon-search"></i>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/09.jpg">
                                            <a href="img/gallery/09.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <h3>Image Title</h3>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/10.jpg">
                                            <a href="img/gallery/10.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <i class="icon-search"></i>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/11.jpg">
                                            <a href="img/gallery/11.jpg" class="popup-with-move-anim"> 
                                                <div class="over">
                                                    <div class="comein">
                                                        <h3>Image Title</h3>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/12.jpg">
                                            <a href="img/gallery/12.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <i class="icon-search"></i>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/07.jpg">
                                            <a href="img/gallery/07.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <h3>Image Title</h3>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="img/gallery/02.jpg">
                                            <a href="img/gallery/02.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <i class="icon-search"></i>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    
                                    
                                </ul>
                                    
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div id="contact" class="page stellar">
                    <div class="pageheader">
                        <div class="headercontent">
                            <div class="section-container">
                                
                                <h2 class="title">Contact Me</h2>
                            
                                <div class="row">
                                    <div class="col-md-8">
                                        <p style="text-align: justify;">I would be happy to talk to you if you need my assistance in your research or whether you need bussiness administration support for your company. Though I have limited time for students but I Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>                              
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="list-unstyled" style="width: 300px;">
                                            <?php if($office_contact!=''){ ?>
                                            <li>
                                                <strong><i class="icon-phone"></i>&nbsp;&nbsp;</strong>
                                                <span>office: <?php  echo $office_contact; ?></span>
                                            </li>
                                            <?php } ?>
                                            <?php if($appointment_contact!=''){ ?>
                                            <li>
                                                <strong><i class="icon-phone"></i>&nbsp;&nbsp;</strong>
                                                <span>appointment: <?php echo $appointment_contact; ?></span>
                                            </li>
                                            <?php } ?>
                                            <?php if($email!=''){ ?>
                                            <li>
                                                <strong><i class="icon-envelope"></i>&nbsp;&nbsp;</strong>
                                                <span><?php echo $email; ?></span>
                                            </li>
                                            <?php } ?>
<!--                                            <li>
                                                <strong><i class="icon-envelope"></i>&nbsp;&nbsp;</strong>
                                                <span>jdoe@gmail.com</span>
                                            </li>-->
                                            <?php if($youtube_url!=''){ ?>
                                            <li>
                                                <strong><i class="icon-youtube"></i>&nbsp;&nbsp;</strong>
                                                <span><a href="http://<?php echo $youtube_url; ?>"><?php echo $youtube_url; ?></a></span>
                                            </li>
                                            <?php } ?>
                                            <?php if($twitter_url!=''){ ?>
                                            <li>
                                                <strong><i class="icon-twitter"></i>&nbsp;&nbsp;</strong>
                                                <span><a href="http://<?php echo $twitter_url; ?>"><?php echo $twitter_url; ?></a></span>
                                            </li>
                                            <?php } ?>
                                            <?php if($linkedin_url!=''){ ?>
                                            <li>
                                                <strong><i class="icon-linkedin-sign"></i>&nbsp;&nbsp;</strong>
                                                <span><a style="font-size: 0.9em;" href="http://<?php echo $linkedin_url; ?>"><?php echo $title; ?></a></span>
                                            </li>
                                            <?php } ?>
                                        </ul>    
                                        <div style="border: solid;height: 150px; width: 250px">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<!--                    <div class="pagecontents">
                        <div class="section contact-office" data-stellar-background-ratio="0.1">
                            <div class="section-container">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h2 class="title">At My Office</h2>
                                        <p>You can find me at my office located at Stanford University Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        <p>I am at my office every day from 7:00 until 10:00 am, but you may consider a call to fix an appointment.</p>
                                    </div>
                                    <div class="col-md-4 text-center hidden-xs hidden-sm">
                                        <i class="icon-coffee icon-huge"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="section color-1">
                            <div class="section-container">
                                <div class="row">
                                    <div class="col-md-4 text-center hidden-xs hidden-sm">
                                        <i class="icon-stethoscope icon-huge"></i>
                                    </div>
                                    <div class="col-md-8">
                                        <h2 class="title">At My Work</h2>
                                        <p>You can find me at my Work located at Stanford University Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        <p>I am at my office every day from 7:00 until 10:00 am, but you may consider a call to fix an appointment.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section contact-lab" data-stellar-background-ratio="0.1">
                            <div class="section-container">
                                <div class="row">
                                    
                                    <div class="col-md-8">
                                        <h2 class="title">At My Lab</h2>
                                        <p>You can find me at my office located at Stanford University Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        <p>I am at my office every day from 7:00 until 10:00 am, but you may consider a call to fix an appointment.</p>
                                    </div>
                                    <div class="col-md-4 text-center hidden-xs hidden-sm">
                                        <i class="icon-superscript icon-huge"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>-->
                    
                </div>
                
                <div id="overlay"></div>
            
            </div>
        </div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','../../www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45653136-1', 'owwwlab.com');
  ga('send', 'pageview');

</script>
    </body>
</html>
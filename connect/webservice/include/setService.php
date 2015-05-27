<?php ob_start();/**********************************************************************************************/   
/***********************************This class is to set User details *************************/
/**********************************************************************************************/
require_once("Rest.inc.php");
class setService extends REST 
{
	public $data = "";
	private $db = NULL;
	public function __construct(){}
        
        ///////////////////////////////////////////////////////////////////////////////        
        //////////////////////////// Code to Verify User name /////////////////////////
        //////////////////////////////////////////////////////////////////////////////
        public function check_user($email, $registration_no, $conn){
            $Query="SELECT * FROM user_master WHERE email='$email' OR registration_no='$registration_no'";
            $result = $this->retrive_data($Query,$conn);
            $User_count = mysqli_num_rows($result);
            //print_r($User_count); die;
            return $User_count;
            //echo $User_count; die;
        }
        //////////////////////////// End Code to verify User Name///////////////////////
        
        ///////////////// Code For Send Confirmation Mail To User /////////////////
        public function send_mail($to,$subject,$header,$message){
            // send email
            $sentmail = mail($to,$subject,$message,$header);
        }
        ///////////////// End Code For Send Confirmation Mail To User //////////////////
        
        ///////////////// Code For Verify User /////////////////////
        public function verify_user($user_id,$verification_code,$conn){
            $query="SELECT confirm_code, status FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $result_count = mysqli_num_rows($result);
            if($result_count>0){
                $data=  mysqli_fetch_assoc($result);
                $confirm_code=$data['confirm_code'];
                $verified=$data['status'];
                if($verified==1){
                    $message='User already verified';
                    $status=0;
                }
                else{
                    if($confirm_code==$verification_code){
                        $query="UPDATE user_master SET status=1 WHERE id='$user_id'";
                        $result = $this->retrive_data($query,$conn);
                        if($result!=FALSE){
                            $message='User Verified';
                            $status=1;
                        }
                        else{
                            $message='User Not Verified';
                            $status=0;
                        }
                    }
                    else{
                        $message='Verification code does not match';
                        $status=0;
                    }
                }
            }
            else{
                $message='User Not Exist';
                $status=0;
            }
            $message = array('status' => $status, "msg" => $message);
            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($message), 200) : $this->response($this->json($message), 200);
        }
        ///////////////// End Code For Verify User ///////////////////
        
        /////////////////// Code To Send Verification Code To User Email ///////////////
        public function verification_code($username,$method,$conn){
            if($method=='email'){
                //email verification code here
                $confirm_code=md5(uniqid(rand()));
                $query="UPDATE user_master SET confirm_code='$confirm_code' WHERE id='$username'";
                $result = $this->retrive_data($query,$conn);
                if($result!=FALSE){
                    $query="SELECT email FROM user_master WHERE id='$username'";
                    $result = $this->retrive_data($query,$conn);
                    $result_count = mysqli_num_rows($result);
                    if($result_count>0){
                        $data=  mysqli_fetch_assoc($result);
                        $to=$data['email'];
                    }
                    $passkey=$confirm_code.'-'.$username;
                    // Your subject
                    $subject="Your confirmation link here";
                    $name='docquity';
                    $email='info@docquity.com';
                    $header  = 'MIME-Version: 1.0' . "\r\n";
                    $header.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";	
                    $header.= 'From: '. $name.'<'. $email.'>'."\r\n";
                    // From
                    //$header="from: your name <your email>";
                    // Your message
                    //$message="Your Comfirmation link \r\n";
                    $message='';
                    $message.="Please click on this link to activate your account <br>";
                    $message.="http://5.9.84.146/~docquity/confirmation.php?passkey=$passkey";
                    // Call send_mail function
                    $this->send_mail($to,$subject,$header,$message);   
                }
                else{
                    
                }
            }
            else if($method=='mobile'){
                //phone verification code here
                
            }
        }
        /////////////////// End Code To Send Verification Code To User Email /////////////////
        
        //////////////////// Code To Get Current Location /////////////////////
        public function getaddress($lat,$lng){
            $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
            $json = @file_get_contents($url);
            $data=json_decode($json);
            $status = $data->status;
            if($status=="OK"){
                return $data->results[0]->formatted_address;
            }
            else{
                return false;
            }
        }
        //////////// End Code To Get Current Location //////////////
        
        ////////////////////// Code To Update Serialize Data //////////////////////
        public function insertIntoSerialize($user_id, $custom_id, $data, $conn){
            if($data!=''){
                $query = "SELECT * FROM serialize_user WHERE user_id='$user_id' AND custom_id='$custom_id'";
                $result = $this->retrive_data($query,$conn);
                $result_count = mysqli_num_rows($result);
                if($result_count==1){
                    $query_update = "UPDATE serialize_user SET data='$data' WHERE user_id='$user_id' AND custom_id='$custom_id'";
                    $result_update = $this->retrive_data($query_update,$conn);

                }
                else{
                    $query_insert = "INSERT INTO serialize_user SET data='$data', user_id='$user_id', custom_id='$custom_id'";
                    $result_insert = $this->retrive_data($query_insert,$conn);
                }
            }
        }
        ////////////////////// End Code To Update Serialize Data /////////////////////
        
        ////////////////////// Code To Get Profile In Serialised /////////////////////
        public function serializeProfile($user_id, $conn){
            $query="SELECT id, first_name, last_name, registration_no, practicing_since, appointment_contact, office_contact, email, mobile, date_of_birth, country, city,
                    state, bio, language, contact_note, profile_pic_path, gender, youtube_url, twitter_url,
                    linkedin_url FROM user_master WHERE id='$user_id' OR custom_id='$user_id' AND status=1";
            $result = $this->retrive_data($query,$conn);
            $result_count = mysqli_num_rows($result);
            if($result_count>0){
                $get_result=mysqli_fetch_assoc($result);
                $posts['user_id']=$get_result['id'];
                $user_id = $get_result['id'];
                $posts['first_name']=$get_result['first_name'];
                $posts['last_name']=$get_result['last_name'];
                $posts['registration_no']=$get_result['registration_no'];
                $posts['practice_since']=$get_result['practicing_since'];
                $posts['appointment_contact']=$get_result['appointment_contact'];
                $posts['office_contact']=$get_result['office_contact'];
                $posts['email']=$get_result['email'];
                $posts['mobile']=$get_result['mobile'];
                $posts['dob']=$get_result['date_of_birth'];
                $posts['country']=$get_result['country'];
                $posts['city']=$get_result['city'];
                $posts['state']=$get_result['state'];
                //$posts['bio']=$get_result['bio'];
                $posts['bio']=trim(preg_replace('/\s+/', ' ', $get_result['bio']));
                $posts['language']=$get_result['language'];
                //$posts['contact_note']=$get_result['contact_note'];
                $posts['contact_note']=trim(preg_replace('/\s+/', ' ', $get_result['contact_note']));
                $posts['profile_pic']=$get_result['profile_pic_path'];
                $posts['gender']=$get_result['gender'];
                $posts['youtube_url']=$get_result['youtube_url'];
                $posts['twitter_url']=$get_result['twitter_url'];
                $posts['linkedin_url']=$get_result['linkedin_url'];
                
                // Get Specialities
                $query_speciality = "SELECT id, speciality_name FROM speciality_master WHERE user_id='$user_id' AND status=1";
                $result_speciality = $this->retrive_data($query_speciality,$conn);
                $result_speciality_count = mysqli_num_rows($result_speciality);
                if($result_speciality_count>0){
                    for($i=0;$i<$result_speciality_count;$i++){
                        $get_speciality_result=mysqli_fetch_assoc($result_speciality);
                        $posts['speciality'][$i]['speciality_id']=$get_speciality_result['id'];
                        $posts['speciality'][$i]['speciality_name']=$get_speciality_result['speciality_name'];
                    }
                }
                else{
                    $posts['speciality']=NULL;
                }
                // End
                
                // Get Interests
                $query_interest = "SELECT id, interest_name FROM interests_master WHERE user_id='$user_id' AND status=1";
                $result_interest = $this->retrive_data($query_interest,$conn);
                $result_interest_count = mysqli_num_rows($result_interest);
                if($result_interest_count>0){
                    for($i=0;$i<$result_interest_count;$i++){
                        $get_interest_result=mysqli_fetch_assoc($result_interest);
                        $posts['interest'][$i]['interest_id']=$get_interest_result['id'];
                        $posts['interest'][$i]['interest_name']=$get_interest_result['interest_name'];
                    }
                }
                else{
                    $posts['interest']=NULL;
                }
                // End
                
                // Get Association 
                $query_association = "SELECT id, association_name, position, location, time_period_start_date, time_period_end_date,
                                      description FROM association_master WHERE user_id='$user_id' AND status=1 ORDER BY time_period_start_date DESC";
                $result_association = $this->retrive_data($query_association,$conn);
                $result_association_count = mysqli_num_rows($result_association);
                if($result_association_count>0){
                    for($i=0;$i<$result_association_count;$i++){
                        $get_association_result=mysqli_fetch_assoc($result_association);
                        $posts['association'][$i]['association_id']=$get_association_result['id'];
                        $posts['association'][$i]['association_name']=$get_association_result['association_name'];
                        $posts['association'][$i]['position']=$get_association_result['position'];
                        $posts['association'][$i]['location']=$get_association_result['location'];
                        $posts['association'][$i]['start_date']=$get_association_result['time_period_start_date'];
                        $posts['association'][$i]['end_date']=$get_association_result['time_period_end_date'];
                        //$posts['association'][$i]['description']=$get_association_result['description'];
                        $posts['association'][$i]['description']=trim(preg_replace('/\s+/', ' ', $get_association_result['description']));
                        $posts['association'][$i]['association_pic']=NULL;
                    }
                }
                else{
                    $posts['association']=NULL;
                }
                // End
                
                // Get Education
                $query_education = "SELECT id, school, attended_start_date, attended_end_date, degree, field_of_study,
                                    grade, activities_and_societies, description FROM education_master WHERE user_id='$user_id' AND status=1 ORDER BY attended_start_date DESC";
                $result_education = $this->retrive_data($query_education,$conn);
                $result_education_count = mysqli_num_rows($result_education);
                if($result_education_count>0){
                    for($i=0;$i<$result_education_count;$i++){
                        $get_education_result=mysqli_fetch_assoc($result_education);
                        $posts['education'][$i]['education_id']=$get_education_result['id'];
                        $posts['education'][$i]['school']=$get_education_result['school'];
                        $posts['education'][$i]['start_date']=$get_education_result['attended_start_date'];
                        $posts['education'][$i]['end_date']=$get_education_result['attended_end_date'];
                        $posts['education'][$i]['degree']=$get_education_result['degree'];
                        $posts['education'][$i]['field_of_study']=$get_education_result['field_of_study'];
                        $posts['education'][$i]['grade']=$get_education_result['grade'];
                        $posts['education'][$i]['activities_and_societies']=$get_education_result['activities_and_societies'];
                        //$posts['education'][$i]['description']=$get_education_result['description'];
                        $posts['education'][$i]['description']=trim(preg_replace('/\s+/', ' ', $get_education_result['description']));
                        $posts['education'][$i]['education_pic']=NULL;
                    }
                }
                else{
                    $posts['education']=NULL;
                }
                // End
                
                // Get Award
                $query_award = "SELECT id, title, description, date, award_pic, live_link FROM award_master WHERE user_id='$user_id' AND status=1";
                $result_award = $this->retrive_data($query_award,$conn);
                $result_award_count = mysqli_num_rows($result_award);
                if($result_award_count>0){
                    for($i=0;$i<$result_award_count;$i++){
                        $get_award_result=mysqli_fetch_assoc($result_award);
                        $posts['award'][$i]['award_id']=$get_award_result['id'];
                        $posts['award'][$i]['title']=$get_award_result['title'];
                        //$posts['award'][$i]['description']=$get_award_result['description'];
                        $posts['award'][$i]['description']=trim(preg_replace('/\s+/', ' ', $get_award_result['description']));
                        $posts['award'][$i]['date']=$get_award_result['date'];
                        $posts['award'][$i]['award_pic']=$get_award_result['award_pic'];
                        $posts['award'][$i]['live_link']=$get_award_result['live_link'];
                    }
                }
                else{
                    $posts['award']=NULL;
                }
                // End
                
                // Get Research
                $query_research = "SELECT id, title, summary FROM research_master WHERE user_id='$user_id' AND status=1";
                $result_research = $this->retrive_data($query_research,$conn);
                $result_research_count = mysqli_num_rows($result_research);
                if($result_research_count>0){
                    for($i=0;$i<$result_research_count;$i++){
                        $get_research_result=mysqli_fetch_assoc($result_research);
                        $research_id = $get_research_result['id'];
                        $posts['research'][$i]['research_id']=$get_research_result['id'];
                        $posts['research'][$i]['title']=$get_research_result['title'];
                        //$posts['research'][$i]['summary']=$get_research_result['summary'];
                        $posts['research'][$i]['summary']=trim(preg_replace('/\s+/', ' ', $get_research_result['summary']));
                        
                        // Get Project
                        $query_project = "SELECT id, research_id, title, description, caption, project_image_path, file_path, project_link FROM project_master WHERE research_id='$research_id' AND status=1";
                        $result_project = $this->retrive_data($query_project,$conn);
                        $result_project_count = mysqli_num_rows($result_project);
                        if($result_project_count>0){
                            for($j=0;$j<$result_project_count;$j++){
                                $get_project_result=mysqli_fetch_assoc($result_project);
                                $posts['research'][$i]['project'][$j]['project_id']=$get_project_result['id'];
                                $posts['research'][$i]['project'][$j]['research_id']=$get_project_result['research_id'];
                                $posts['research'][$i]['project'][$j]['title']=$get_project_result['title'];
                                //$posts['research'][$i]['project'][$j]['description']=$get_project_result['description'];
                                $posts['research'][$i]['project'][$j]['description']=trim(preg_replace('/\s+/', ' ', $get_project_result['description']));
                                //$posts['research'][$i]['project'][$j]['caption']=$get_project_result['caption'];
                                $posts['research'][$i]['project'][$j]['caption']=trim(preg_replace('/\s+/', ' ', $get_project_result['caption']));
                                $posts['research'][$i]['project'][$j]['project_image_path']=$get_project_result['project_image_path'];
                                $posts['research'][$i]['project'][$j]['file_path']=$get_project_result['file_path'];
                                $posts['research'][$i]['project'][$j]['project_link']=$get_project_result['project_link'];
                            }
                        }
                        else{
                            $posts['research'][$i]['project']=NULL;
                        }
                        // End
                        
                    }
                }
                else{
                    $posts['research']=NULL;
                }
                $serializeData = $this->publicjson($posts);
            }
            else{
                $serializeData = '';
            }
            
            
            return $serializeData;
                
        }
        ////////////////////// End Code To Get Profile In Serialised ////////////////////

        ////////////////////////////////////////////////////////////////////////////////	
        /////////////////////////Code For Adding User //////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////
	public function sign_up($first_name, $last_name, $email, $gender, $password, $registration_no, $practice_since, $appointment_contact, $office_contact, $mobile, $country, $city, $state, $language, $dob, $bio, $contact_note, $picurl, $youtube_url, $twitter_url, $linkedin_url, $source_id, $ip_device, $latitude, $longitude, $verify_method, $conn){
            $bio = nl2br($bio);
            $contact_note = nl2br($contact_note);
            $max_id_query = "SELECT max(id) FROM user_master";
            $result_max_id = $this->retrive_data($max_id_query,$conn);
            $max_id_data = mysqli_fetch_assoc($result_max_id);
            $max_id = $max_id_data['max(id)']+1;
            $user_auth_key = time().$max_id;
            $query="INSERT into user_master SET first_name='".$first_name."',last_name='".$last_name."',email='".$email."',password='".md5($password)."',registration_no='".$registration_no."',practicing_since='".$practice_since."',appointment_contact='".$appointment_contact."',office_contact='".$office_contact."',mobile='".$mobile."',date_of_birth='".$dob."',gender='".$gender."',country='".$country."',city='".$city."',state='".$state."',bio='".$bio."',language='".$language."',contact_note='".$contact_note."',profile_pic_path='".$picurl."',youtube_url='".$youtube_url."',twitter_url='".$twitter_url."',linkedin_url='".$linkedin_url."',status=0,created_on=now(),user_auth_key=$user_auth_key";
            $result = $this->retrive_data($query,$conn);
            $affected_rows=  mysqli_affected_rows($conn);
            if($affected_rows>0){
                $query_detail = "SELECT * FROM user_master WHERE email='$email'";
                $result_data = $this->retrive_data($query_detail,$conn);
                $result_count = mysqli_num_rows($result_data);
                if($result_count > 0){
                    $data=  mysqli_fetch_assoc($result_data);
                    $id=$data['id'];
                    $auth_key=$data['user_auth_key'];
                    $posts['user_id']=$id;
                    $posts['custom_id']=$data['custom_id'];
                    $posts['user_auth_key']=$auth_key;
                    $posts['first_name']=$data['first_name'];
                    $posts['last_name']=$data['last_name'];
                    $posts['email']=$data['email'];
                    $posts['registration_no']=$data['registration_no'];
                    $posts['mobile']=$data['mobile'];
                    $posts['country']=$data['country'];
                    $posts['city']=$data['city'];
                    $posts['state']=$data['state'];
                    $posts['profile_pic']=$data['profile_pic_path'];
                    $pass = md5($password);
                    // Insert Into Registration Tracking
                    $query_tracking = "INSERT INTO registration_tracking SET source_id='$source_id', ip_device='$ip_device', registered_user_id='$id', latitude='$latitude', longitude='$longitude', datetime=now()";
                    $result = $this->retrive_data($query_tracking,$conn);
                    // End
                    // Insert Into Chat User Master
                    if($source_id==2 || $source_id==3){
                        $query_chat = "INSERT INTO chat_user_master SET user_id='$id', password='$pass', user_auth_key='$user_auth_key'";
                        $result = $this->retrive_data($query_chat,$conn);
                    }
                    // End
                    // Insert Into URL Management
                    $cid = uniqid($id);// Custom Code
                    $query_reg = "UPDATE user_master SET custom_id='$cid' WHERE email='$email'";
                    $result_reg = $this->retrive_data($query_reg,$conn);
                    $first_name = strtolower($first_name);
                    $last_name = strtolower($last_name);
                    $query_url = "INSERT INTO url_management SET user_id='$id', profile_url='http://docquity.com/$first_name.$last_name-$cid.html'";
                    $result = $this->retrive_data($query_url,$conn);
                    // End
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($id, $conn);
                    $this->insertIntoSerialize($id, $cid, $serializedData, $conn);
                    // End
                    // Ejabbered Id
                    $output = shell_exec('sh create.sh User '.$email.' chat.docquity.com '.$password);
                    // End
                    $message = array("status" => "1", "msg" => "User Created Successfully");
                    $post_data=  array_merge($posts,$message);
                    //Call verification_code function
                    if($verify_method!=''){
                        $this->verification_code($id,$verify_method,$conn);
                    }
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($post_data), 200):$this->response($this->publicjson($post_data), 200);
                }
                else{
                    $message = array('status' => "0", "msg" => "Email Not Exist");
                    ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($message), 200) : $this->response($this->json($message), 200);
                }
            }
            else{
                $message = array('status' => "0", "msg" => "User Not Created");
                ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($message), 200) : $this->response($this->json($message), 200);
            }
        }
        ///////////////////// End Code of Add User ///////////////////////
        
        ///////////////////// Code To Create Association ////////////////////
        public function create_association($user_id, $association_name, $position, $location, $start_date, $end_date, $description, $conn){
            $description = nl2br($description);
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_insert = "INSERT INTO association_master SET association_name='$association_name',position='$position',location='$location',time_period_start_date='$start_date',time_period_end_date='$end_date',description='$description',user_id='$user_id',status=1";
                $result_insert = $this->retrive_data($query_insert,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Association Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Association Not Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
		($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ///////////////////// End Code To Create Association //////////////////
        
        ///////////////////// Code To Create Education ///////////////////////
        public function create_education($user_id, $school_name, $start_date, $end_date, $degree, $field_of_study, $grade, $activities_and_societies, $description, $conn){
            $description = nl2br($description);
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_insert = "INSERT INTO education_master SET school='$school_name',attended_start_date='$start_date',attended_end_date='$end_date',degree='$degree',field_of_study='$field_of_study',grade='$grade',activities_and_societies='$activities_and_societies',description='$description',user_id='$user_id',status=1";
                $result_insert = $this->retrive_data($query_insert,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Education Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Education Not Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ///////////////////// End Code To Create Education ////////////////////
        
        ///////////////////// Code To Create Speciality ///////////////////////
        public function create_speciality($user_id, $speciality_name, $conn){
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_insert = "INSERT INTO speciality_master SET speciality_name='$speciality_name',user_id='$user_id',status=1";
                $result_insert = $this->retrive_data($query_insert,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Speciality Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Speciality Not Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ///////////////////// End Code To Create Speciality ////////////////////
        
        ///////////////////// Code To Create Interest ////////////////////////
        public function create_interest($user_id, $interest_name, $conn){
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_insert = "INSERT INTO interests_master SET interest_name='$interest_name',user_id='$user_id',status=1";
                $result_insert = $this->retrive_data($query_insert,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Interest Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Interest Not Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ///////////////////// End Code To Create Interest /////////////////////
        
        ///////////////////// Code To Create Research ///////////////////////
        public function create_research($user_id, $title, $summary, $conn){
            $summary = nl2br($summary);
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_insert = "INSERT INTO research_master SET title='$title', summary='$summary',user_id='$user_id',status=1";
                $result_insert = $this->retrive_data($query_insert,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Research Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Research Not Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ///////////////////// End Code To Create Research ////////////////////
        
        ///////////////////// Code To Create Project /////////////////////////
        public function create_project($research_id, $user_id, $title, $description, $caption, $picurl, $fileurl, $project_link, $conn){
            $description = nl2br($description);
            $caption = nl2br($caption);
            $query="SELECT * FROM user_master um INNER JOIN research_master rm ON rm.user_id = um.id WHERE um.id='$user_id' AND rm.id='$research_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_insert = "INSERT INTO project_master SET title='$title', description='$description', caption='$caption', project_image_path='$picurl', file_path='$fileurl',research_id='$research_id',project_link='$project_link',status=1";
                $result_insert = $this->retrive_data($query_insert,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Project Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Project Not Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "Either User or Research Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ///////////////////// End Code To Create Project //////////////////////
        
        ///////////////////// Code To Create Award //////////////////////
        public function create_award($user_id, $title, $description, $date, $picurl, $live_link, $conn){
            $description = nl2br($description);
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_insert = "INSERT INTO award_master SET title='$title', description='$description', date='$date', award_pic='$picurl', live_link='$live_link', user_id='$user_id',status=1";
                $result_insert = $this->retrive_data($query_insert,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Award Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Award Not Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ///////////////////// End Code To Create Award ///////////////////

        ///////////////////// Code For Cheking Authentication /////////////////////
        public function CheckAuthentication($user_id,$user_auth_key,$conn){
            $query="SELECT user_auth_key FROM user_master WHERE user_auth_key='$user_auth_key'"; // I removed temporarly AND status=1
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count >0 ){ 
               return true;
            }
            else{
               return false; 
            }	
        }
        ////////////////////// End Checking Authentication code ////////////////////////
 
        //////////////////// Code For Checking Reset Password //////////////////////////
	public function reset_password($user_auth_key,$password,$new_password,$confirm_password){   
            $Query="SELECT password FROM user WHERE user_auth_key='".$user_auth_key."' AND active_status='1'";
            $result = $this->retrive_data($Query);
            $result_row = mysql_fetch_array($result);
            if($result_row['password'] != md5($password)){
                $message = array('status' => "0", "msg" => "Your Old Password is Wrong");
		($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
            }
            else{
                if($result != false){
                    $query = "UPDATE user SET password = '".md5($new_password)."' WHERE user_auth_key='".$user_auth_key."'";
                    //echo $query; die;
                    $result = $this->retrive_data($query);
                    $message = array('status' => "1", "msg" => "Password Change Successfully");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $message = array('status' => "0", "msg" => "Password Not Changed");
                    ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($message), 200) : $this->response($this->json($message), 200);
                }
            }
        }
        ////////////////////// End Code For reset Password //////////////////
        
        ///////////////////// Code For Forget Password //////////////////////        
	public function forget_password($email,$new_password,$conn){   
            $sql_Query = "SELECT * FROM user_master WHERE email='".$email."'";
            $result = $this->retrive_data($sql_Query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count == 1){
                $name='docquity';
                $email_from='info@docquity.com';
                ////////////// Code for Sending Email //////////////////////////////  
                $format = '%H:%M:%S';
                $strf = strftime($format);
                //$email = $_POST["email"];
                $to = $email;
                //$subject_admin = "http://188.40.41.142/~vsoftwar/denver/ Inquiry sent by " .$fname;
                $subject_admin  = "Your password";
                $headers_admin  = 'MIME-Version: 1.0' . "\r\n";
                $headers_admin .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";	
                $headers_admin .= 'From: '. $name.'<'. $email_from.'>'."\r\n";
                $message_admin = "<b>Your new password is : </b>".$new_password;
                
                //////////// Code for Sending Email End ////////////////////////////       
                $query="UPDATE user_master SET password='".md5($new_password)."' WHERE email='".$email."'";
                $result_data = $this->retrive_data($query,$conn);
                $affected_rows=  mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Call send_mail function
                    $this->send_mail($to,$subject_admin,$headers_admin,$message_admin); 
                    $message = array('status' => "1", "msg" => "Your New Password Sent To Your Email");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $message = array('status' => "0", "msg" => "Password Not Set");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "You Entered An Invalid Email");
		($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ///////////////////// End Code For Forget Password //////////////////
  
        ////////////////////// Code To Update User Profile /////////////////////
        public function set_profile($user_id, $first_name, $last_name, $email, $practice_since, $appointment_contact, $office_contact, $country, $city, $state, $mobile, $gender, $dob, $contact_note, $language, $youtube_url, $twitter_url, $linkedin_url, $conn){
            /////////////
            /// Write a method to get Custom ID of any User but not call mysqli or any other direct dateabase connection.
            /////////////
            $contact_note = nl2br($contact_note);
            //$contact_note = str_replace('\n', '\n', $contact_note);
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                // code To update record in user table..................
                $query = "UPDATE user_master SET first_name='$first_name',last_name='$last_name',email='$email',practicing_since='$practice_since',appointment_contact='$appointment_contact',office_contact='$office_contact',country='$country',city='$city',state='$state',gender='$gender',date_of_birth='$dob',mobile='$mobile',contact_note='$contact_note',language='$language',youtube_url='$youtube_url',twitter_url='$twitter_url',linkedin_url='$linkedin_url'";
//                if($picurl!=''){
//                    $query = "UPDATE user_master SET first_name='$first_name',last_name='$last_name',email='$email',practicing_since='$practice_since',appointment_contact='$appointment_contact',office_contact='$office_contact',country='$country',city='$city',state='$state',gender='$gender',date_of_birth='$dob',mobile='$mobile',bio='$bio',contact_note='$contact_note',language='$language',youtube_url='$youtube_url',twitter_url='$twitter_url',linkedin_url='$linkedin_url',profile_pic_path='$picurl'";
//                }
//                else{
//                    $query = "UPDATE user_master SET first_name='$first_name',last_name='$last_name',email='$email',practicing_since='$practice_since',appointment_contact='$appointment_contact',office_contact='$office_contact',country='$country',city='$city',state='$state',gender='$gender',date_of_birth='$dob',mobile='$mobile',bio='$bio',contact_note='$contact_note',language='$language',youtube_url='$youtube_url',twitter_url='$twitter_url',linkedin_url='$linkedin_url'";
//                }
                $query.=" WHERE id='$user_id'";
                //echo $query; die;
                $result = $this->retrive_data($query,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Profile Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Profile Not Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
		($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Update User Profile //////////////////////
        
        ////////////////////// Code To Update Biography ////////////////////////
        public function set_biography($user_id, $bio, $conn){
            $bio = nl2br($bio);
            //$bio = str_replace('\n','\n',$bio);
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_update = "UPDATE user_master SET bio='$bio' WHERE id='$user_id'";
                $result_update = $this->retrive_data($query_update,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Biography Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Biography Not Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Update Biography //////////////////////
        
        ////////////////////// Code To Update Association //////////////////////
        public function set_association($association_id, $user_id, $association_name, $position, $location, $start_date, $end_date, $description, $conn){
            $description = nl2br($description);
            //$description = trim(preg_replace('/\s+/', '\n', $description));
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_update = "UPDATE association_master SET association_name='$association_name',position='$position',location='$location',time_period_start_date='$start_date',time_period_end_date='$end_date',description='$description' WHERE id='$association_id' AND user_id='$user_id'";
                $result_update = $this->retrive_data($query_update,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Association Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Association Not Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Update Association //////////////////
        
        ////////////////////// Code To Update Education ////////////////////////
        public function set_education($education_id, $user_id, $school_name, $start_date, $end_date, $degree, $field_of_study, $grade, $activities_and_societies, $description, $conn){
            $description = nl2br($description);
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_update = "UPDATE education_master SET school='$school_name',attended_start_date='$start_date',attended_end_date='$end_date',degree='$degree',field_of_study='$field_of_study',grade='$grade',activities_and_societies='$activities_and_societies',description='$description' WHERE id='$education_id' AND user_id='$user_id'";
                $result_update = $this->retrive_data($query_update,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Education Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Education Not Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Update Education /////////////////////
        
        ////////////////////// Code To Update Speciality ////////////////////////
        public function set_speciality($speciality_id, $user_id, $speciality_name, $conn){
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_update = "UPDATE speciality_master SET speciality_name='$speciality_name' WHERE id='$speciality_id' AND user_id='$user_id'";
                $result_update = $this->retrive_data($query_update,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Speciality Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Speciality Not Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Update Speciality /////////////////////
        
        ////////////////////// Code To Update Interest //////////////////////
        public function set_interest($interest_id, $user_id, $interest_name, $conn){
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_update = "UPDATE interests_master SET interest_name='$interest_name' WHERE id='$interest_id' AND user_id='$user_id'";
                $result_update = $this->retrive_data($query_update,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Interest Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Interest Not Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Update Interest //////////////////////
        
        ////////////////////// Code To Update Research ////////////////////////
        public function set_research($research_id, $user_id, $title, $summary, $conn){
            $summary = nl2br($summary);
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_update = "UPDATE research_master SET title='$title', summary='$summary' WHERE id='$research_id' AND user_id='$user_id'";
                $result_update = $this->retrive_data($query_update,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Research Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Research Not Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Update Research //////////////////////
        
        ////////////////////// Code To Update Project ///////////////////////
        public function set_project($project_id, $research_id, $user_id, $title, $description, $caption, $picurl, $fileurl, $project_link, $conn){
            $description = nl2br($description);
            $caption = nl2br($caption);
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                if($picurl!='' && $fileurl!=''){
                    $query_update = "UPDATE project_master SET title='$title', description='$description', caption='$caption', project_image_path='$picurl', file_path='$fileurl', project_link='$project_link' WHERE id='$project_id' AND research_id='$research_id'";
                }
                else if($picurl!='' && $fileurl==''){
                    $query_update = "UPDATE project_master SET title='$title', description='$description', caption='$caption', project_image_path='$picurl', project_link='$project_link' WHERE id='$project_id' AND research_id='$research_id'";
                }
                else if($picurl=='' && $fileurl!=''){
                    $query_update = "UPDATE project_master SET title='$title', description='$description', caption='$caption', file_path='$fileurl', project_link='$project_link' WHERE id='$project_id' AND research_id='$research_id'";
                }
                else{
                    $query_update = "UPDATE project_master SET title='$title', description='$description', caption='$caption', project_link='$project_link' WHERE id='$project_id' AND research_id='$research_id'";
                }
                $result_update = $this->retrive_data($query_update,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Project Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Project Not Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Update Project /////////////////////
        
        ////////////////////// Code To Update Awards ///////////////////////
        public function set_award($award_id, $user_id, $title, $description, $date, $picurl, $live_link, $conn){
            $description = nl2br($description);
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                if($picurl!=''){
                    $query_update = "UPDATE award_master SET title='$title', description='$description', date='$date', award_pic='$picurl', live_link='$live_link'  WHERE id='$award_id' AND user_id='$user_id'";
                }
                else{
                    $query_update = "UPDATE award_master SET title='$title', description='$description', date='$date', live_link='$live_link'  WHERE id='$award_id' AND user_id='$user_id'";
                }
                $result_update = $this->retrive_data($query_update,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Award Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Award Not Updated");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Update Awards ///////////////////
        
        ////////////////////// Code To Remove Association ////////////////////
        public function remove_association($association_id, $user_id, $conn){
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_remove = "UPDATE association_master SET status=0 WHERE id='$association_id' AND user_id='$user_id'";
                $result_remove = $this->retrive_data($query_remove,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Association Removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Association Not Removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Remove Association ////////////////// 
        
        ////////////////////// Code To Remove Education //////////////////////
        public function remove_education($education_id, $user_id, $conn){
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_remove = "UPDATE education_master SET status=0 WHERE id='$education_id' AND user_id='$user_id'";
                $result_remove = $this->retrive_data($query_remove,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Education Removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Education Not Removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Remove Education ///////////////////
        
        ////////////////////// Code To Remove Research //////////////////////
        public function remove_research($research_id, $user_id, $conn){
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_remove = "UPDATE research_master SET status=0 WHERE id='$research_id' AND user_id='$user_id'";
                $result_remove = $this->retrive_data($query_remove,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    $query_rem = "UPDATE project_master SET status=0 WHERE research_id='$research_id'";
                    $result_rem = $this->retrive_data($query_rem,$conn);
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Research Removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Research Not Removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Remove Research /////////////////////
        
        ////////////////////// Code To Remove Project ////////////////////////
        public function remove_project($project_id, $research_id, $user_id, $conn){
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_remove = "UPDATE project_master SET status=0 WHERE id='$project_id' AND research_id='$research_id'";
                $result_remove = $this->retrive_data($query_remove,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Project Removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Project Not Removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
                
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Remove Project /////////////////////
        
        ////////////////////// Code To Remove Interest ///////////////////////
        public function remove_interest($interest_id, $user_id, $conn){
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                $query_remove = "UPDATE interests_master SET status=0 WHERE id='$interest_id' AND user_id='$user_id'";
                $result_remove = $this->retrive_data($query_remove,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $message = array('status'=> "1","msg"=>"Interest Removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Interest Not Removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Remove Interest ////////////////////
       
        ////////////////////// Code To Update Profile Pic ///////////////////////
        public function set_user_pic($user_id, $picurl, $conn){
            $query="SELECT * FROM user_master WHERE id='$user_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $cid = $data['custom_id'];
                // code To update record in user table..................
                $query = "UPDATE user_master SET profile_pic_path='$picurl'";
                $query.=" WHERE id='$user_id'";
                //echo $query; die;
                $result = $this->retrive_data($query,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Insert Into Serialize Data
                    $serializedData = $this->serializeProfile($user_id, $conn);
                    $this->insertIntoSerialize($user_id, $cid, $serializedData, $conn);
                    // End
                    $posts['user_pic_url']=$picurl;
                    $message = array('status'=> "1","msg"=>"User Pic Added");
                    $post_data=  array_merge($posts,$message);
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($post_data), 200):$this->response($this->publicjson($post_data), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "User Pic Not Added");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Exist");
		($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Update Profile Pic ////////////////////
        
        ////////////////////// Code To Create Group ////////////////////////
        public function create_group($group_name, $group_desc, $owner_id, $picurl, $conn){
            $query="SELECT * FROM user_master WHERE id='$owner_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $query_check = "SELECT * FROM group_master WHERE name='$group_name'";
                $result_check = $this->retrive_data($query_check,$conn);
                $result_check_count = mysqli_num_rows($result_check);
                if($result_check_count>0){
                    $jabber_name = $group_name.($result_check_count+1);
                }
                else{
                    $jabber_name = $group_name.'1';
                }
                
                
                $query = "INSERT INTO group_master SET name='$group_name',jabber_group_name='$jabber_name',description='$group_desc',owner_id='$owner_id',group_pic='$picurl',datetime=now(),status=1";
                $result = $this->retrive_data($query,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Add Owner Into Group
                    $group_id = mysqli_insert_id($conn);
                    $query_insert = "INSERT INTO group_member_mapping SET group_id='$group_id',member_id='$owner_id',accept_status=1,datetime=now(),status=1";
                    $result_insert = $this->retrive_data($query_insert,$conn);
                    // End
                    // Ejabbered
                    $output = shell_exec('sh create.sh Group testabhi chat.docquity.com "" '.$group_name.' "'.$group_desc.'"');
                    // End
                    // Get Group Id
                    $posts['$group_id'] = $group_id;
                    $posts['jabber_name'] = $jabber_name;
                    // End
                    $message = array('status'=> "1","msg"=>"Group Created");
                    $posts_data = array_merge($posts, $message);
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($posts_data), 200):$this->response($this->publicjson($posts_data), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Group Not Created");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "Group Owner Not Exist");
		($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Create Group ////////////////////
        
        ////////////////////// Code To Edit Group ////////////////////////
        public function edit_group($group_id, $group_name, $group_desc, $owner_id, $picurl, $conn){
            $query = "SELECT * FROM group_master WHERE id='$group_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $query="SELECT * FROM user_master WHERE id='$owner_id'";
                $result = $this->retrive_data($query,$conn);
                $row_count = mysqli_num_rows($result);
                if($row_count>0){
                    if($picurl!=''){
                        $query = "UPDATE group_master SET name='$group_name',description='$group_desc',owner_id='$owner_id',group_pic='$picurl' WHERE id='$group_id'";
                    }
                    else{
                        $query = "UPDATE group_master SET name='$group_name',description='$group_desc',owner_id='$owner_id' WHERE id='$group_id'";
                    }
                    $result = $this->retrive_data($query,$conn);
                    $affected_rows=mysqli_affected_rows($conn);
                    if($affected_rows>0){
                        $message = array('status'=> "1","msg"=>"Group Updated");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                    }
                    else{
                        $error = array('status' => "0", "msg" => "Group Not Updated");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                    }
                }
                else{
                    $error = array('status' => "0", "msg" => "Group Owner Not Exist");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "Group Not Exist");
		($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Edit Group /////////////////////
        
        ////////////////////// Code To Delete Group ///////////////////////
        public function remove_group($group_id, $conn){
            $query = "SELECT * FROM group_master WHERE id='$group_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $status = $data['status'];
                if($status==1){
                    $query = "UPDATE group_master SET status=0 WHERE id='$group_id'";
                    $result = $this->retrive_data($query,$conn);
                    $affected_rows=mysqli_affected_rows($conn);
                    if($affected_rows>0){
                        $message = array('status'=> "1","msg"=>"Group Removed");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                    }
                    else{
                        $error = array('status' => "0", "msg" => "Group Not Removed");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                    }
                }
                else{
                    $error = array('status' => "0", "msg" => "Group already removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "Group Not Exist");
		($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Delete Group ////////////////////
        
        ////////////////////// Code To Add Member Into Group /////////////////
        public function add_member_to_group($group_id, $member_id, $conn){
            $query = "SELECT * FROM group_master WHERE id='$group_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $query = "SELECT * FROM user_master WHERE id='$member_id'";
                $result = $this->retrive_data($query,$conn);
                $row_count = mysqli_num_rows($result);
                if($row_count>0){
                    $query_check = "SELECT * FROM group_member_mapping WHERE group_id='$group_id' AND member_id='$member_id'";
                    $result_check = $this->retrive_data($query_check,$conn);
                    $row_count_check = mysqli_num_rows($result_check);
                    if($row_count_check==0){
                        $query_insert = "INSERT INTO group_member_mapping SET group_id='$group_id',member_id='$member_id',accept_status=1,datetime=now(),status=1";
                        $result_insert = $this->retrive_data($query_insert,$conn);
                        $affected_rows=mysqli_affected_rows($conn);
                        if($affected_rows>0){
                            $message = array('status'=> "1","msg"=>"User added in this group");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                        }
                        else{
                            $error = array('status' => "0", "msg" => "User not added");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                        }
                    }
                    else{
                        $error = array('status' => "0", "msg" => "User already a member of this group");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                    }
                }
                else{
                    $error = array('status' => "0", "msg" => "User Not Exist");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "Group Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Add Member Into Group //////////////////
        
        ////////////////////// Code To Delete Member From Group /////////////////////
        public function remove_member_from_group($group_id, $member_id, $conn){
            $query = "SELECT * FROM group_master WHERE id='$group_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $query = "SELECT * FROM user_master WHERE id='$member_id'";
                $result = $this->retrive_data($query,$conn);
                $row_count = mysqli_num_rows($result);
                if($row_count>0){
                    $query_check = "SELECT * FROM group_member_mapping WHERE group_id='$group_id' AND member_id='$member_id'";
                    $result_check = $this->retrive_data($query_check,$conn);
                    $row_count_check = mysqli_num_rows($result_check);
                    if($row_count_check==1){
                        $data = mysqli_fetch_assoc($result_check);
                        $status = $data['status'];
                        if($status==1){
                            $query_insert = "UPDATE group_member_mapping SET status=0 WHERE group_id='$group_id' AND member_id='$member_id'";
                            $result_insert = $this->retrive_data($query_insert,$conn);
                            $affected_rows=mysqli_affected_rows($conn);
                            if($affected_rows>0){
                                $message = array('status'=> "1","msg"=>"User deleted from this group");
                                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                            }
                            else{
                                $error = array('status' => "0", "msg" => "User not deleted");
                                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                            }
                        }
                        else{
                            $error = array('status' => "0", "msg" => "User already deleted from this group");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                        }
                    }
                    else{
                        $error = array('status' => "0", "msg" => "User not a member of this group");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                    }
                }
                else{
                    $error = array('status' => "0", "msg" => "User Not Exist");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "Group Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Delete Member From Group ///////////////////
        
        ////////////////////// Code To Create Discussion ////////////////////////
        public function create_discussion($discussion_name, $discussion_desc, $owner_id, $picurl, $conn){
            $query="SELECT * FROM user_master WHERE id='$owner_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $query = "INSERT INTO discussion_master SET name='$discussion_name',description='$discussion_desc',owner_id='$owner_id',discussion_pic='$picurl',datetime=now(),status=1";
                $result = $this->retrive_data($query,$conn);
                $affected_rows=mysqli_affected_rows($conn);
                if($affected_rows>0){
                    // Add Owner Into Discussion
                    $discussion_id = mysqli_insert_id($conn);
                    $query_insert = "INSERT INTO discussion_member_mapping SET discussion_id='$discussion_id',member_id='$owner_id',accept_status=1,datetime=now(),status=1";
                    $result_insert = $this->retrive_data($query_insert,$conn);
                    // End
                    // Get Discussion Id
                    $posts['discussion_id'] = $discussion_id;
                    // End
                    $message = array('status'=> "1","msg"=>"Discussion Created");
                    $posts_data = array_merge($posts, $message);
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($posts_data), 200):$this->response($this->publicjson($posts_data), 200);
                }
                else{
                    $error = array('status' => "0", "msg" => "Discussion Not Created");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "Discussion Owner Not Exist");
		($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Create Discussion ////////////////////
        
        ////////////////////// Code To Edit Discussion ////////////////////////
        public function edit_discussion($discussion_id, $discussion_name, $discussion_desc, $owner_id, $picurl, $conn){
            $query = "SELECT * FROM discussion_master WHERE id='$discussion_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $query = "SELECT * FROM user_master WHERE id='$owner_id'";
                $result = $this->retrive_data($query,$conn);
                $row_count = mysqli_num_rows($result);
                if($row_count>0){
                    if($picurl!=''){
                        $query = "UPDATE discussion_master SET name='$discussion_name',description='$discussion_desc',owner_id='$owner_id',discussion_pic='$picurl' WHERE id='$discussion_id'";
                    }
                    else{
                        $query = "UPDATE discussion_master SET name='$discussion_name',description='$discussion_desc',owner_id='$owner_id' WHERE id='$discussion_id'";
                    }
                    $result = $this->retrive_data($query,$conn);
                    $affected_rows=mysqli_affected_rows($conn);
                    if($affected_rows>0){
                        $message = array('status'=> "1","msg"=>"Discussion Updated");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                    }
                    else{
                        $error = array('status' => "0", "msg" => "Discussion Not Updated");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                    }
                }
                else{
                    $error = array('status' => "0", "msg" => "Discussion Owner Not Exist");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "Discussion Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            } 
        }
        ////////////////////// End Code To Edit Discussion /////////////////////
        
        ////////////////////// Code To Delete Discussion ///////////////////////
        public function remove_discussion($discussion_id, $conn){
            $query = "SELECT * FROM discussion_master WHERE id='$discussion_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $data = mysqli_fetch_assoc($result);
                $status = $data['status'];
                if($status==1){
                    $query = "UPDATE discussion_master SET status=0 WHERE id='$discussion_id'";
                    $result = $this->retrive_data($query,$conn);
                    $affected_rows=mysqli_affected_rows($conn);
                    if($affected_rows>0){
                        $message = array('status'=> "1","msg"=>"Discussion Removed");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                    }
                    else{
                        $error = array('status' => "0", "msg" => "Discussion Not Removed");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                    }
                }
                else{
                    $error = array('status' => "0", "msg" => "Discussion already removed");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                } 
            }
            else{
                $error = array('status' => "0", "msg" => "Discussion Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Delete Discussion ////////////////////
        
        ////////////////////// Code To Add Member Into Discussion /////////////////
        public function add_member_to_discussion($discussion_id, $member_id, $conn){
            $query = "SELECT * FROM discussion_master WHERE id='$discussion_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $query = "SELECT * FROM user_master WHERE id='$member_id'";
                $result = $this->retrive_data($query,$conn);
                $row_count = mysqli_num_rows($result);
                if($row_count>0){
                    $query_check = "SELECT * FROM discussion_member_mapping WHERE discussion_id='$discussion_id' AND member_id='$member_id'";
                    $result_check = $this->retrive_data($query_check,$conn);
                    $row_count_check = mysqli_num_rows($result_check);
                    if($row_count_check==0){
                        $query_insert = "INSERT INTO discussion_member_mapping SET discussion_id='$discussion_id',member_id='$member_id',accept_status=1,datetime=now(),status=1";
                        $result_insert = $this->retrive_data($query_insert,$conn);
                        $affected_rows=mysqli_affected_rows($conn);
                        if($affected_rows>0){
                            $message = array('status'=> "1","msg"=>"User added in this discussion");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                        }
                        else{
                            $error = array('status' => "0", "msg" => "User not added");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                        }
                    }
                    else{
                        $error = array('status' => "0", "msg" => "User already a member of this discussion");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                    }
                }
                else{
                    $error = array('status' => "0", "msg" => "User Not Exist");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "Discussion Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Add Member Into Discussion /////////////////////
        
        ////////////////////// Code To Delete Member From Discussion /////////////////////
        public function remove_member_from_discussion($discussion_id, $member_id, $conn){
            $query = "SELECT * FROM discussion_master WHERE id='$discussion_id'";
            $result = $this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                $query = "SELECT * FROM user_master WHERE id='$member_id'";
                $result = $this->retrive_data($query,$conn);
                $row_count = mysqli_num_rows($result);
                if($row_count>0){
                    $query_check = "SELECT * FROM discussion_member_mapping WHERE discussion_id='$discussion_id' AND member_id='$member_id'";
                    $result_check = $this->retrive_data($query_check,$conn);
                    $row_count_check = mysqli_num_rows($result_check);
                    if($row_count_check==1){
                        $data = mysqli_fetch_assoc($result_check);
                        $status = $data['status'];
                        if($status==1){
                            $query_insert = "UPDATE discussion_member_mapping SET status=0 WHERE discussion_id='$discussion_id' AND member_id='$member_id'";
                            $result_insert = $this->retrive_data($query_insert,$conn);
                            $affected_rows=mysqli_affected_rows($conn);
                            if($affected_rows>0){
                                $message = array('status'=> "1","msg"=>"User deleted from this discussion");
                                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                            }
                            else{
                                $error = array('status' => "0", "msg" => "User not deleted");
                                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                            }
                        }
                        else{
                            $error = array('status' => "0", "msg" => "User already deleted from this discussion");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                        }
                    }
                    else{
                        $error = array('status' => "0", "msg" => "User not a member of this discussion");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                    }
                }
                else{
                    $error = array('status' => "0", "msg" => "User Not Exist");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                }
            }
            else{
                $error = array('status' => "0", "msg" => "Discussion Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Delete Member From Discussion ///////////////////
        
        ////////////////////// Code To Send Friend Request ///////////////////////
        public function request_friend($requester_id, $requestee_id, $conn){
           $query = "SELECT * FROM user_master WHERE id='$requester_id' OR id='$requestee_id'"; 
           $result=$this->retrive_data($query,$conn);
           $row_count = mysqli_num_rows($result);
           if($row_count>0){
                $query_check = "SELECT * FROM connection WHERE requester_id='$requester_id' AND requestee_id='$requestee_id'";
                $result_check=$this->retrive_data($query_check,$conn);
                $row_count_check = mysqli_num_rows($result_check);
                if($row_count_check==0){
                    $query_insert = "INSERT INTO connection SET requester_id='$requester_id',requestee_id='$requestee_id',approved=9,created=now()";
                    $result_insert=$this->retrive_data($query_insert,$conn);
                    $affected_rows=mysqli_affected_rows($conn);
                    if($affected_rows>0){
                        $message = array('status'=> "1","msg"=>"Request Send");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                    }
                    else{
                        $error = array('status' => "0", "msg" => "Request Not Send");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                    }
                }
                else{
                    $data = mysqli_fetch_assoc($result_check);
                    $approved = $data['approved'];
                    if($approved==1){
                        $error = array('status' => "0", "msg" => "Already a friend");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                    }
                    else if($approved==0){
                        $query_update = "UPDATE connection SET approved=9 WHERE requester_id='$requester_id' AND requestee_id='$requestee_id'";
                        $result_update=$this->retrive_data($query_update,$conn);
                        $affected_rows=mysqli_affected_rows($conn);
                        if($affected_rows>0){
                            $message = array('status'=> "1","msg"=>"Request Send");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                        }
                        else{
                            $error = array('status' => "0", "msg" => "Request Not Send");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                        }
                    }
                    else{
                        $error = array('status' => "0", "msg" => "Request already sent");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                    }
                }
           }
           else{
               $error = array('status' => "0", "msg" => "Either Requester or Requestee Not Exist");
               ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
           }
        }
        ////////////////////// End Code To Send Friend Request ////////////////////
        
        ////////////////////// Code To Accept Friend Request //////////////////////
        public function accept_friend($requester_id, $requestee_id, $conn){
            $query = "SELECT * FROM user_master WHERE id='$requester_id' OR id='$requestee_id'"; 
            $result=$this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                 $query_check = "SELECT * FROM connection WHERE requester_id='$requester_id' AND requestee_id='$requestee_id'";
                 $result_check=$this->retrive_data($query_check,$conn);
                 $row_count_check = mysqli_num_rows($result_check);
                 if($row_count_check==1){
                     $data = mysqli_fetch_assoc($result_check);
                     $approved = $data['approved'];
                     if($approved==9){
                         $query_update = "UPDATE connection SET approved=1 WHERE requester_id='$requester_id' AND requestee_id='$requestee_id'";
                         $result_update=$this->retrive_data($query_update,$conn);
                         $affected_rows=mysqli_affected_rows($conn);
                         if($affected_rows>0){
                            $message = array('status'=> "1","msg"=>"Request Accepted");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                         }
                         else{
                            $error = array('status' => "0", "msg" => "Request Not Accepted");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                         }  
                     }
                     else if($approved==1){
                        $error = array('status' => "0", "msg" => "Request Already Accepted");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                     }
                     else{
                        $error = array('status' => "0", "msg" => "Request Declined");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                     }
                 }
                 else{
                     $error = array('status' => "0", "msg" => "Request not sended");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                 }
            }
            else{
                $error = array('status' => "0", "msg" => "Either Requester or Requestee Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
         }
         ////////////////////// End Code To Accept Friend Request /////////////////////
         
         ////////////////////// Code To Decline Friend Request ////////////////////
         public function decline_friend($requester_id, $requestee_id, $conn){
            $query = "SELECT * FROM user_master WHERE id='$requester_id' OR id='$requestee_id'"; 
            $result=$this->retrive_data($query,$conn);
            $row_count = mysqli_num_rows($result);
            if($row_count>0){
                 $query_check = "SELECT * FROM connection WHERE requester_id='$requester_id' AND requestee_id='$requestee_id'";
                 $result_check=$this->retrive_data($query_check,$conn);
                 $row_count_check = mysqli_num_rows($result_check);
                 if($row_count_check==1){
                     $data = mysqli_fetch_assoc($result_check);
                     $approved = $data['approved'];
                     if($approved==9){
                         $query_update = "UPDATE connection SET approved=0 WHERE requester_id='$requester_id' AND requestee_id='$requestee_id'";
                         $result_update=$this->retrive_data($query_update,$conn);
                         $affected_rows=mysqli_affected_rows($conn);
                         if($affected_rows>0){
                            $message = array('status'=> "1","msg"=>"Request Declined");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
                         }
                         else{
                            $error = array('status' => "0", "msg" => "Request Not Declined");
                            ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                         }  
                     }
                     else if($approved==1){
                        $error = array('status' => "0", "msg" => "Already Accepted");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                     }
                     else{
                        $error = array('status' => "0", "msg" => "Request Already Declined");
                        ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                     }
                 }
                 else{
                     $error = array('status' => "0", "msg" => "Request not sended");
                    ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
                 }
            }
            else{
                $error = array('status' => "0", "msg" => "Either Requester or Requestee Not Exist");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
         }
         ////////////////////// End Code To Decline Friend Request ///////////////////
         
         ////////////////////// Code To Active User ///////////////////////
         public function active_user($userid, $conn){
             $query="UPDATE user_master SET status='1' WHERE id='$userid'";
             $result=$this->retrive_data($query,$conn);
             $affected_rows=mysqli_affected_rows($conn);
             if($affected_rows>0){
                 $message = array('status'=> "1","msg"=>"User Activated");
                 ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
             }
             else{
                 $error = array('status' => "0", "msg" => "User Not Activated");
                 ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
             }
        }
        ////////////////////// End Code To Active User ////////////////////
        
        ////////////////////// Code To Remove User ////////////////////////
        public function remove_user($userid, $conn){
            $query="UPDATE user_master SET status='9' WHERE id='$userid'";
            $result=$this->retrive_data($query,$conn);
            $affected_rows=mysqli_affected_rows($conn);
            if($affected_rows>0){
                $message = array('status'=> "1","msg"=>"User Removed");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($message), 200):$this->response($this->publicjson($message), 200);
            }
            else{
                $error = array('status' => "0", "msg" => "User Not Removed");
                ($_REQUEST['format']=='xml')?$this->response($this->publicxml($error), 200):$this->response($this->publicjson($error), 200);
            }
        }
        ////////////////////// End Code To Remove User /////////////////////
         
        ///////////////////// Execute Query ////////////////////
        public function retrive_data($query,$conn)
	{
            if(isset($query))
                return mysqli_query($conn,$query);
            else
                return false;
	}
        /*
         *	Encode array into JSON
        */
        public function publicjson($data){
            return $this->json($data);
        }
        public function publicxml($posts){
            return $this->xml($posts);
        }
        private function json($data){
            header('Content-type: application/json');
            return json_encode(array('posts'=>$data));
        }
        private function xml($posts){
            if(is_array($posts)){
                $data='';
                header('Content-type: text/xml');
                echo '<posts>';
                foreach($posts as $tag => $val) {
                  echo '<',$tag,'>',htmlentities($val),'</',$tag,'>';
                }
                echo '</posts>';
            }
        }
}
ob_end_flush();?>
<?php ob_start();
        /* 
                        This is class script proceeding secured API
                        To use this class you should keep same as query string and function name
        */
	include('include/getService.php');
	require_once("Rest.inc.php");
        require_once('../setting.php');
        
	class API extends REST {
            public $data = "";
            const DB_SERVER = "localhost";
            
//            const DB_USER = "root";
//            const DB_PASSWORD = "";
//            const DB = "docquity";
            
            const DB_USER = "docquity_user";
            const DB_PASSWORD = "!@#$%^";
            const DB = "docquity_db";

            private $db = NULL;
            public function __construct(){
                    parent::__construct();		// Init parent contructor
            }
            ///////////////////////////// Data base Connection ///////////////////////////// 
            private function dbConnect(){
                $this->db = mysqli_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD,self::DB);
                //                if($this->db){
                //                    mysql_select_db(self::DB,$this->db);
                //                }       
            }
            private function dbClose(){
                if($this->db)
                    mysqli_close($this->db);
            }
            /*
             * Public method for access api.
             * This method dynmically call the method based on the query string
             *
             */
            public function processApi(){
                $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
                if((int)method_exists($this,$func) > 0)
                    $this->$func();
                else
                    $this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
            }

            /* 
             *	Simple login API
             *  Login must be POST method
             *  email : <USER EMAIL>
             *  pwd : <USER PASSWORD>
             */

            ////////////////////////////////////////////////////////////////////////////////
            ///////////////////////// Check Login /////////////////////////////
            ////////////////////////////////////////////////////////////////////////////////
            public function Login(){
                if($this->get_request_method()!= "POST"){
                    $this->response('',406);
                }
                $username   =   $_POST['email'];
                $password   =   $_POST['password'];
                $latitude   =   $_POST['latitude'];
                $longitude  =   $_POST['longitude'];
                $source_id  =   $_POST['source_id'];
                $ip_device  =   $_POST['ip_device'];
                $format     =   $_POST['format'];
                $db_access  =   $this->dbConnect();
                $conn       =   $this->db;
                $res        =   new getService();
                if(!empty($username) && !empty($password)){
                    $result_check = $res->check_email_password($username, $password, $conn);
                    if($result_check==1){
                        $result = $res->check_user($username,$conn);
                        if($result>0){
                            $res->get_login($username, $password, $latitude, $longitude, $source_id, $ip_device, $conn);
                            $this->dbClose();
                        }
                        else{
                            $this->dbClose();
                            $error = array('status' => "0", "msg" => "Not Active User !!");
                            ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($res->json($error), 200);
                        }
                    }
                    else{
                        $this->dbClose();
                        $error = array('status' => "0", "msg" => "Either Email or Password is wrong");
                        ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($res->json($error), 200);
                    }  
                }
                else{	
                    $this->dbClose();
                    $error = array('status' => "0", "msg" => "Fill Both Fields !!");
                    ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($res->json($error), 200);
                }
            }
            ///////////////////////// End of Login ///////////////////////////
            
            ///////////////////////// Admin Login ///////////////////////////
            public function AdminLogin(){
                if($this->get_request_method()!= "POST"){
                    $this->response('',406);
                }
                $username   =   $_POST['email'];
                $password   =   $_POST['password'];
                $format     =   $_POST['format'];
                $db_access  =   $this->dbConnect();
                $conn       =   $this->db;
                $res        =   new getService();
                if(!empty($username) && !empty($password)){
                    $res->admin_login($username, $password, $conn);
                    $this->dbClose();
                }
                else{	
                    $this->dbClose();
                    $error = array('status' => "0", "msg" => "Fill Both Fields !!");
                    ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($res->json($error), 200);
                }
            }
            ///////////////////////// Admin Login ///////////////////////////
            
            ///////////////////////// Get Source ////////////////////////////
            public function GetSource(){
                if($this->get_request_method()!= "POST"){
                    $this->response('',406);
                }
                $format     =   $_POST['format'];
                $db_access  =   $this->dbConnect();
                $conn       =   $this->db;
                $res        =   new getService();
                $res->get_source($conn);
            }
            ///////////////////////// Get Source ////////////////////////////
            
            ///////////// Code For SignUp facebook ////////////////////////////////////
            public function SignInFacebook(){
                if ($this->get_request_method() != "POST") {
                    $this->response('', 406);
                }
                $email              =   $_POST['email'];
                $pic1               =   $_POST['pic1'];
                $first_name         =   $_POST['first_name']; 
                $last_name          =   $_POST['last_name'];
                $user_name          =   $_POST['user_name'];
                $gender             =   $_POST['gender'];
                $birth_date         =   $_POST['birth_date'];
                $login_type         =   $_POST['login_type'];
                $latitude           =   $_POST['latitude'];
                $longitude          =   $_POST['longitude'];
                $source_id          =   $_POST['source_id'];
                $format             =   $_POST['format'];
                
                $dobarr = explode("/",$birth_date);
                $month = $dobarr[0];
                $day = $dobarr[1];
                $year = $dobarr[2];
                $dob=$year.'-'.$month.'-'.$day;
                
                $db_access  =   $this->dbConnect();
                $conn       =   $this->db;
                $res        =   new getService();
                $result = $res->check_user($email,$conn);
                if($result == 0){
                    $response = $res->sign_up_facebook($email,$pic1,$first_name,$last_name,$user_name,$gender,$dob,$login_type,$latitude,$longitude,$source_id,$conn);
                }
                elseif($result > 0){
                    $response = $res->sign_in_facebook($email,$pic1,$first_name,$last_name,$user_name,$gender,$dob,$latitude,$longitude,$source_id,$conn);
                    $this->dbClose();
                   // $error = array('status' => "0","msg" => "Email Already Exists ");
                   // ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 400) : $this->response($this->json($error), 400);
                }
            }
            ///////////////// End Code For SignUp facebook ///////////////////////////
                                       
            ////////////////// Code To Get the profile details of a user //////////////////
            public function GetProfile(){ //GetProfile
                if($this->get_request_method()!= "POST"){
                    $this->response('',406);
                }
                $user_id        =   $_POST['user_id'];
                $user_auth_key  =   $_POST['user_auth_key'];
                $source_user_id =   $_POST['source_user_id'];
                $source_id      =   $_POST['source_id'];
                $ip_device      =   $_POST['ip_device'];
                $latitude       =   $_POST['latitude'];
                $longitude      =   $_POST['longitude'];
                $format         =   $_POST['format'];
                $db_access = $this->dbConnect();
                $conn =  $this->db;
                $res = new getService();
                if(!empty($user_id)){
                    $result = $res->CheckAuthentication($user_id, $user_auth_key, $conn);
                    if($result != false){
                        $res->get_profile($user_id, $source_user_id, $source_id, $ip_device, $latitude, $longitude, $conn);
                        $this->dbClose();
                    }
                    else{
                        $this->dbclose();
                        $error = array('status' => "0", "msg" => "Not Authorised To get detail");
                        ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($res->json($error), 200);
                    }
                }
                else{
                    $error = array('status' => "0", "msg" => "Fill All Fields");
                    ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($res->json($error), 200);
                }
            }
            ////////////////// End Code to Get the Profile details of a user ////////////////  
            
            ////////////////// Code To Get Doctor List ///////////////////
            public function GetDoctorList(){
                if($this->get_request_method()!= "POST"){
                    $this->response('',406);
                }
                $format = $_POST['format'];
                $db_access = $this->dbConnect();
                $conn =  $this->db;
                $res = new getService();
                $res->get_doctor_list($conn);
            }
            ////////////////// End Code To Get Doctor List /////////////////
             
            ////////////////// Code To Get Total Count ///////////////////////
            public function GetTotalCount(){
                if($this->get_request_method()!= "POST"){
                    $this->response('',406);
                }
                $format = $_POST['format'];
                $db_access = $this->dbConnect();
                $conn =  $this->db;
                $res = new getService();
                $res->get_total_count($conn);
            }
            ////////////////// End Code To Get Total Count ////////////////////
            
            ////////////////// Code To Get Search Name List ///////////////////
            public function GetSearchNameList(){
                if($this->get_request_method()!= "POST"){
                    $this->response('',406);
                }
                $type           = $_POST['type'];
                $user_auth_key  = $_POST['user_auth_key'];
                $format         = $_POST['format'];
                $db_access = $this->dbConnect();
                $conn =  $this->db;
                $res = new getService();
                if(!empty($type) && !empty($user_auth_key)){
                    $result = $res->CheckAuthentication('', $user_auth_key, $conn);
                    if($result != false){
                        $res->get_search_name_list($type, $conn);
                        $this->dbClose();
                    }
                    else{
                        $this->dbclose();
                        $error = array('status' => "0", "msg" => "Not Authorised To get detail");
                        ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($res->json($error), 200);
                    }
                }
                else{
                    $error = array('status' => "0", "msg" => "Fill All Fields");
                    ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($res->json($error), 200);
                }
            }
            ////////////////// End Code To Get Search Name List ////////////////
            
            /////////////////// Code To Search By Type ////////////////////
            public function GetSearch(){
                if($this->get_request_method()!= "POST"){
                    $this->response('',406);
                }
                $user_id        = $_POST['user_id'];
                $keyword        = $_POST['keyword'];
                $type           = $_POST['type'];
                $user_auth_key  = $_POST['user_auth_key'];
                $latitude       = $_POST['latitude'];
                $longitude      = $_POST['longitude'];
                $page_no        = $_POST['page_no'];
                $format         = $_POST['format'];
                $db_access = $this->dbConnect();
                $conn =  $this->db;
                $res = new getService();
                if(!empty($keyword) && !empty($type) && !empty($user_auth_key) && !empty($page_no)){
                    $result = $res->CheckAuthentication($user_id, $user_auth_key, $conn);
                    if($result != false){
                        $res->get_search($user_id, $keyword, $type, $latitude, $longitude, $page_no, $conn);
                        $this->dbClose();
                    }
                    else{
                        $this->dbclose();
                        $error = array('status' => "0", "msg" => "Not Authorised To get detail");
                        ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($res->json($error), 200);
                    }
                }
                else{
                    $error = array('status' => "0", "msg" => "Fill All Fields");
                    ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($res->json($error), 200);
                }
            }
            /////////////////// End Code To Search By Type ////////////////////
            
            /////////////////////// Code To Get Friend List /////////////////////////
            public function FriendList(){
                if ($this->get_request_method() != "POST") {
                    $this->response('', 406);
                }
                $discussion_id = $_POST['discussion_id'];
                $user_id = $_POST['user_id'];
                $authkey = $_POST['authkey'];
                $status = $_POST['status'];// 0 for declined, 1 for accept, 9 for pending
                $format = $_POST['format'];
                $this->dbConnect();
                $conn =  $this->db;
                $res = new getService();
                if(!empty($user_id) && !empty($authkey)){
                    $result = $res->CheckAuthentication($user_id, $authkey, $conn);
                    if($result!=false){
                       $result=$res->get_friend_list($discussion_id, $user_id, $status, $conn);
                    }
                    else{
                        $error = array('status' => "0","msg" => "Not authorised to do this");
                        ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                    }
                }
                else{
                  $error = array('status' => "0", "msg" => "Fill All Fields");
                  ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($this->json($error), 200);
                }
            }
            /////////////////////// End Code To Get Friend List //////////////////////
            
            /////////////////////// Code To Get Group Details ////////////////////////
            public function GetGroupDetails(){
                if ($this->get_request_method() != "POST") {
                    $this->response('', 406);
                }
                $group_id = $_POST['group_id'];
                $authkey = $_POST['authkey'];
                $format = $_POST['format'];
                $this->dbConnect();
                $conn =  $this->db;
                $res = new getService();
                if(!empty($group_id) && !empty($authkey)){
                    $result = $res->CheckAuthentication('', $authkey, $conn);
                    if($result!=false){
                       $result=$res->get_group_details($group_id, $conn);
                    }
                    else{
                        $error = array('status' => "0","msg" => "Not authorised to do this");
                        ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                    }
                }
                else{
                  $error = array('status' => "0", "msg" => "Fill All Fields");
                  ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($this->json($error), 200);
                }
            }
            /////////////////////// End Code To Get Group Details //////////////////////
            
            /////////////////////// Code To Get Discussion Details /////////////////////
            public function GetDiscussionDetails(){
                if ($this->get_request_method() != "POST") {
                    $this->response('', 406);
                }
                $discussion_id = $_POST['discussion_id'];
                $authkey = $_POST['authkey'];
                $format = $_POST['format'];
                $this->dbConnect();
                $conn =  $this->db;
                $res = new getService();
                if(!empty($discussion_id) && !empty($authkey)){
                    $result = $res->CheckAuthentication('', $authkey, $conn);
                    if($result!=false){
                       $result=$res->get_discussion_details($discussion_id, $conn);
                    }
                    else{
                        $error = array('status' => "0","msg" => "Not authorised to do this");
                        ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                    }
                }
                else{
                  $error = array('status' => "0", "msg" => "Fill All Fields");
                  ($_REQUEST['format']=='xml')?$this->response($this->xml($error), 200):$this->response($this->json($error), 200);
                }
            }
            /////////////////////// End Code To Get Discussion Details //////////////////
            
            ///////////////////////////////////////////////////////////////////////////////		
            ///////////////////////////Encode array into JSON//////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////
            private function json($data){
                            header('Content-type: application/json');
                            return json_encode($data);
            }
                
            private function xml($posts){
               if(is_array($posts)){ 
                    $data='';
                    header('Content-type: text/xml');
                    echo '<posts>';
                    foreach($posts as $index => $post){
                        if(! is_array($post)){ 
                                echo '<',$index,'>',htmlentities($post),'</',$index,'>';
                        }else if(is_array($post)){ 
                            foreach($post as $key => $value) {
                                  echo '<',$key,'>';
                                 if(! is_array($value)){
                                    //echo '<',$key,'>',htmlentities($value),'</',$key,'>';
                                    echo htmlentities($value); 
                                 }else if(is_array($value)){ 
                                    foreach($value as $tag => $val){ 
                                       echo '<',$tag,'>',htmlentities($val),'</',$tag,'>';
                                    }
                                 }
                                 echo '</',$key,'>';
                             } 
                         } 
                    } echo '</posts>';
                } 
            }
            ////////////////////////////////Encode array into JSON End/////////////////////             
        }
        // Initiate Library
	$api = new API;
	$api->processApi();
        ob_end_flush();
?>
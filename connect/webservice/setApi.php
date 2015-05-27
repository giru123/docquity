<?php ob_start();
/*This class script proceeding secured API
To use this class you should keep same as query string and function name
*/
 
include('include/setService.php');
require_once("Rest.inc.php");
require_once('../setting.php');

class API extends REST
{
    public $data = "";
    const DB_SERVER = "localhost";
    
//    const DB_USER = "root";
//    const DB_PASSWORD = "";
//    const DB = "docquity";
    
    const DB_USER = "docquity_user";
    const DB_PASSWORD = "!@#$%^";
    const DB = "docquity_db";
    
    private $db = NULL;

    public function __construct()
    {
        parent::__construct(); // Init parent contructor
    }
    
    ////////////////////////////////////////////////////////////////////////////
    ///////////////////  Database connection  //////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    
    private function dbConnect()
    {
        $this->db = mysqli_connect(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
        //$conn=$this->db;
      //  if ($this->db){
        //    mysqli_select_db($this->db, self::DB);
       // }   
    }
    ///////////////////  Database connection close  ////////////////////////////
    
    private function dbClose()
    {
        if ($this->db)
            mysqli_close($this->db);
    }
    
    //////////////// Code for Processs API /////////////////////////////////////    
    public function processApi()
    {
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));
        if ((int) method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 404); // If the method not exist with in this class, response would be "Page not found".
    }

    ////////////////////////////////////////////////////////////////////////////	
    /////////////////////////////Custom Definition of Function//////////////////		
    ////////////////////////////////////////////////////////////////////////////
    
    ///////////////////////// Code For Sign Up /////////////////////////////////
    public function SignUp(){
        //echo 'hi';die;
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $this->dbConnect();
        $res = new setService();
        $first_name         =  $_POST['firstname'];
        $last_name          =  $_POST['lastname'];
        $email              =  $_POST['email'];
        $password           =  $_POST['password'];
        $registration_no    =  $_POST['registration_no'];
        $practice_since     =  $_POST['practice_since'];
        $appointment_contact=  $_POST['appointment_contact'];
        $office_contact     =  $_POST['office_contact'];
        $mobile             =  $_POST['mobile'];
        $gender             =  $_POST['gender'];
        $dob                =  $_POST['date_of_birth'];
        $country            =  $_POST['country'];
        $city               =  $_POST['city'];
        $state              =  $_POST['state'];
        $bio                =  $_POST['bio'];
        $language           =  $_POST['language'];
        $contact_note       =  $_POST['contact_note'];
        $profile_pic        =  $_POST['profile_pic'];
        $profile_pic_type   =  $_POST['profile_pic_type'];
        $youtube_url        =  $_POST['youtube_url'];
        $twitter_url        =  $_POST['twitter_url'];
        $linkedin_url       =  $_POST['linkedin_url'];
        $source_id          =  $_POST['source_id'];
        $ip_device          =  $_POST['ip_device'];
        $latitude           =  $_POST['latitude'];
        $longitude          =  $_POST['longitude'];
        $format             =  $_POST['format'];
        $conn               =  $this->db;
        $picurl='';
        if(empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($registration_no)){
            $message = array('status' => "0","msg" => "Fill All Fields Properly");
            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($message), 200) : $this->response($this->json($message), 200);
        }
        //Check email or registration no of user if already exist?
        $result = $res->check_user($email, $registration_no, $conn);
        if($result == 0){
            $verify_method='email';
            if($profile_pic!=''){
                $picname='profile_'.time().'_'.$registration_no;
                switch($profile_pic_type){
                    case 'image/jpeg':{
                        $new_pic=urldecode($profile_pic);
                        $new_pic=str_replace(' ','+',$new_pic);
                        $content = base64_decode($new_pic);
                        $image = imagecreatefromstring($content);
                        $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.jpeg';
                        $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.jpeg';
                        $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.jpeg';
                        header ( 'Content-type:image/jpeg' );
                        imagejpeg($image, $directory1);
                        imagejpeg($image, $directory2);
                        imagejpeg($image, $directory3);
                        $picurl='images/profile/original/'.$picname.'.jpeg';
                        imagedestroy ( $image );
                        break;
                    }
                    case 'image/png':{
                        $new_pic=urldecode($profile_pic);
                        $new_pic=str_replace(' ','+',$new_pic);
                        $content = base64_decode($new_pic);
                        $image = imagecreatefromstring($content);
                        $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.png';
                        $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.png';
                        $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.png';
                        header ( 'Content-type:image/png' );
                        imagepng($image, $directory1);
                        imagepng($image, $directory2);
                        imagepng($image, $directory3);
                        $picurl='images/profile/original/'.$picname.'.png';
                        imagedestroy ( $image );
                        break;
                    }
                    case 'image/gif':{
                        $new_pic=urldecode($profile_pic);
                        $new_pic=str_replace(' ','+',$new_pic);
                        $content = base64_decode($new_pic);
                        $image = imagecreatefromstring($content);
                        $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.gif';
                        $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.gif';
                        $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.gif';
                        header ( 'Content-type:image/gif' );
                        imagegif($image, $directory1);
                        imagegif($image, $directory2);
                        imagegif($image, $directory3);
                        $picurl='images/profile/original/'.$picname.'.gif';
                        imagedestroy ( $image );
                        break;
                    }
                    case 'image/jpg':{
                        $new_pic=urldecode($profile_pic);
                        $new_pic=str_replace(' ','+',$new_pic);
                        $content = base64_decode($new_pic);
                        $image = imagecreatefromstring($content);
                        $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.jpg';
                        $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.jpg';
                        $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.jpg';
                        header ( 'Content-type:image/jpg' );
                        imagejpeg($image, $directory1);
                        imagejpeg($image, $directory2);
                        imagejpeg($image, $directory3);
                        $picurl='images/profile/original/'.$picname.'.jpg';
                        imagedestroy ( $image );
                        break;
                    }
                    default:{
                        $error = array('status' => "0","msg" => "User Pic Should Be Only JPG, JPEG, PNG & GIF");
                        ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                    }
                }
            }
            
            $response = $res->sign_up($first_name, $last_name, $email, $gender, $password, $registration_no, $practice_since, $appointment_contact, $office_contact, $mobile, $country, $city, $state, $language, $dob, $bio, $contact_note, $picurl, $youtube_url, $twitter_url, $linkedin_url, $source_id, $ip_device, $latitude, $longitude, $verify_method, $conn);
            $this->dbClose();
        }
        else{
            $this->dbClose();
            $error = array('status' => "0","msg" => "Email or Registration No. Already Exists");
            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
        }    
    }
    ///////////////////  End Code For Sign Up   ///////////////////////////
    
    //////////////////// Confirmation mail ///////////////////////
    public function VerifyUser(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $user_id               =   $_POST['user_id'];
        $verification_code     =   $_POST['verification_code'];
        $format                =   $_POST['format'];
        $this->dbConnect();
        $res = new setService();
        $conn = $this->db;
        if(!empty($user_id) && !empty($verification_code)){
            $response = $res->verify_user($user_id,$verification_code,$conn);
            $this->dbClose();
        }
        else{
            $error = array('status' => "0","msg" => "Fill All Fields");
            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
        }
    }
    //////////////////// End Confirmation mail ///////////////////
    
    //////////////////// Code To Create Association ////////////////////
    public function CreateAssociation(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $user_id               =    $_POST['user_id'];
        $user_auth_key         =    $_POST['user_auth_key'];
        $association_name      =    $_POST['association_name'];
        $position              =    $_POST['position'];
        $location              =    $_POST['location'];
        $start_date            =    $_POST['start_date'];
        $end_date              =    $_POST['end_date'];
        $description           =    $_POST['description'];
        $format         =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($user_id) && !empty($user_auth_key) && !empty($association_name)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                  $result=$res->create_association($user_id, $association_name, $position, $location, $start_date, $end_date, $description, $conn);
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
    //////////////////// End Code To Create Association ///////////////////
    
    //////////////////// Code To Create Education /////////////////////////
    public function CreateEducation(){
         if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $user_id                  =    $_POST['user_id'];
        $user_auth_key            =    $_POST['user_auth_key'];
        $school_name              =    $_POST['school_name'];
        $start_date               =    $_POST['start_date'];
        $end_date                 =    $_POST['end_date'];
        $degree                   =    $_POST['degree'];
        $field_of_study           =    $_POST['field_of_study'];
        $grade                    =    $_POST['grade'];
        $activities_and_societies =    $_POST['activities_and_societies'];
        $description              =    $_POST['description'];
        $format                   =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($user_id) && !empty($user_auth_key) && !empty($school_name)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                  $result=$res->create_education($user_id, $school_name, $start_date, $end_date, $degree, $field_of_study, $grade, $activities_and_societies, $description, $conn);
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
    //////////////////// End Code To Create Education /////////////////////
    
    //////////////////// Code To Create Speciality ///////////////////////
    public function CreateSpeciality(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $user_id                  =    $_POST['user_id'];
        $user_auth_key            =    $_POST['user_auth_key'];
        $speciality_name          =    $_POST['speciality_name'];
        $format                   =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($user_id) && !empty($user_auth_key) && !empty($speciality_name)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                 $result=$res->create_speciality($user_id, $speciality_name, $conn);
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
    //////////////////// End Code To Create Speciality /////////////////////
    
    //////////////////// Code To Create Interest /////////////////////
    public function CreateInterest(){
         if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $user_id                =    $_POST['user_id'];
        $user_auth_key          =    $_POST['user_auth_key'];
        $interest_name          =    $_POST['interest_name'];
        $format                 =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($user_id) && !empty($user_auth_key) && !empty($interest_name)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                 $result=$res->create_interest($user_id, $interest_name, $conn);
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
    //////////////////// End Code To Create Interest ////////////////////
    
    //////////////////// Code To Create Research //////////////////////
    public function CreateResearch(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $user_id          =    $_POST['user_id'];
        $user_auth_key    =    $_POST['user_auth_key'];
        $title            =    $_POST['title'];
        $summary          =    $_POST['summary'];
        $format           =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($user_id) && !empty($user_auth_key) && !empty($title)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                 $result=$res->create_research($user_id, $title, $summary, $conn);
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
    //////////////////// End Code To Create Research ///////////////////
    
    //////////////////// Code To Create Project ////////////////////////
    public function CreateProject(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $research_id      =    $_POST['research_id'];
        $user_id          =    $_POST['user_id'];
        $user_auth_key    =    $_POST['user_auth_key'];
        $title            =    $_POST['title'];
        $description      =    $_POST['description'];
        $caption          =    $_POST['caption'];
        $image            =    $_POST['image'];
        $image_type       =    $_POST['image_type'];
        $file             =    $_POST['file'];
        $file_type        =    $_POST['file_type'];
        $project_link     =    $_POST['project_link'];
        $format           =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        $picurl = '';
        $fileurl = '';
        if(!empty($research_id) && !empty($user_id) && !empty($user_auth_key) && !empty($title)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                 if($image!=''){
                     $picname='project'.time().'_'.$user_id;
                     switch($image_type){
                        case 'image/jpeg':{
                            $new_pic=urldecode($image);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/project/original/'.$picname.'.jpeg';
                            $directory2 = '/home/docquity/public_html/images/project/256 x 256/'.$picname.'.jpeg';
                            $directory3 = '/home/docquity/public_html/images/project/128 x 128/'.$picname.'.jpeg';
                            header ( 'Content-type:image/jpeg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/project/original/'.$picname.'.jpeg';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/png':{
                            $new_pic=urldecode($image);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/project/original/'.$picname.'.png';
                            $directory2 = '/home/docquity/public_html/images/project/256 x 256/'.$picname.'.png';
                            $directory3 = '/home/docquity/public_html/images/project/128 x 128/'.$picname.'.png';
                            header ( 'Content-type:image/png' );
                            imagepng($image, $directory1);
                            imagepng($image, $directory2);
                            imagepng($image, $directory3);
                            $picurl='images/project/original/'.$picname.'.png';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/gif':{
                            $new_pic=urldecode($image);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/project/original/'.$picname.'.gif';
                            $directory2 = '/home/docquity/public_html/images/project/256 x 256/'.$picname.'.gif';
                            $directory3 = '/home/docquity/public_html/images/project/128 x 128/'.$picname.'.gif';
                            header ( 'Content-type:image/gif' );
                            imagegif($image, $directory1);
                            imagegif($image, $directory2);
                            imagegif($image, $directory3);
                            $picurl='images/project/original/'.$picname.'.gif';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/jpg':{
                            $new_pic=urldecode($image);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/project/original/'.$picname.'.jpg';
                            $directory2 = '/home/docquity/public_html/images/project/256 x 256/'.$picname.'.jpg';
                            $directory3 = '/home/docquity/public_html/images/project/128 x 128/'.$picname.'.jpg';
                            header ( 'Content-type:image/jpg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/project/original/'.$picname.'.jpg';
                            imagedestroy ( $image );
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "Project Pic Should Be Only JPG, JPEG, PNG & GIF");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    }
                 }
                 if($file!=''){
                     $filename='project'.time().'_'.$user_id;
                     switch($file_type){
                        case 'application/pdf':{
                            $dec=urldecode($file);
                            $new_file=str_replace(' ','+',$dec); 
                            $base64_decode = base64_decode($new_file);
                            $directory='/home/docquity/public_html/doc/project/'.$filename.'.pdf';
                            file_put_contents($directory, $base64_decode);
                            $fileurl='doc/project/'.$filename.'.pdf';
                            break;
                        }
                        case 'application/msword':{
                            $dec=urldecode($file);
                            $new_file=str_replace(' ','+',$dec); 
                            $base64_decode = base64_decode($new_file);
                            $directory='/home/docquity/public_html/doc/project/'.$filename.'.doc';
                            file_put_contents($directory, $base64_decode);
                            $fileurl='doc/project/'.$filename.'.doc';
                            break;
                        }
                        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':{
                            $dec=urldecode($file);
                            $new_file=str_replace(' ','+',$dec); 
                            $base64_decode = base64_decode($new_file);
                            $directory='/home/docquity/public_html/doc/project/'.$filename.'.docx';
                            file_put_contents($directory, $base64_decode);
                            $fileurl='doc/project/'.$filename.'.docx';
                            break;
                        }
                        case 'text/plain':{
                            $dec=urldecode($file);
                            $new_file=str_replace(' ','+',$dec); 
                            $base64_decode = base64_decode($new_file);
                            $directory='/home/docquity/public_html/doc/project/'.$filename.'.txt';
                            file_put_contents($directory, $base64_decode);
                            $fileurl='doc/project/'.$filename.'.txt';
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "File Should Be Only pdf, doc, docx & txt");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    }
                 }
                 $result=$res->create_project($research_id, $user_id, $title, $description, $caption, $picurl, $fileurl, $project_link, $conn);
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
    //////////////////// End Code To Create Project /////////////////////
    
    //////////////////// Code To Create Award ////////////////////////
    public function CreateAward(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $user_id        =    $_POST['user_id'];
        $user_auth_key  =    $_POST['user_auth_key'];
        $title          =    $_POST['title'];
        $description    =    $_POST['description'];
        $date           =    $_POST['date'];
        $award_pic      =    $_POST['award_pic'];
        $award_pic_type =    $_POST['award_pic_type'];
        $live_link      =    $_POST['live_link'];
        $format         =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        $picurl = '';
        if(!empty($user_id) && !empty($user_auth_key) && !empty($title)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                 if($award_pic!=''){
                     $picname='award_'.time().'_'.$user_id;
                     switch($award_pic_type){
                        case 'image/jpeg':{
                            $new_pic=urldecode($award_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/award/original/'.$picname.'.jpeg';
                            $directory2 = '/home/docquity/public_html/images/award/256 x 256/'.$picname.'.jpeg';
                            $directory3 = '/home/docquity/public_html/images/award/128 x 128/'.$picname.'.jpeg';
                            header ( 'Content-type:image/jpeg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/award/original/'.$picname.'.jpeg';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/png':{
                            $new_pic=urldecode($award_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/award/original/'.$picname.'.png';
                            $directory2 = '/home/docquity/public_html/images/award/256 x 256/'.$picname.'.png';
                            $directory3 = '/home/docquity/public_html/images/award/128 x 128/'.$picname.'.png';
                            header ( 'Content-type:image/png' );
                            imagepng($image, $directory1);
                            imagepng($image, $directory2);
                            imagepng($image, $directory3);
                            $picurl='images/award/original/'.$picname.'.png';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/gif':{
                            $new_pic=urldecode($award_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/award/original/'.$picname.'.gif';
                            $directory2 = '/home/docquity/public_html/images/award/256 x 256/'.$picname.'.gif';
                            $directory3 = '/home/docquity/public_html/images/award/128 x 128/'.$picname.'.gif';
                            header ( 'Content-type:image/gif' );
                            imagegif($image, $directory1);
                            imagegif($image, $directory2);
                            imagegif($image, $directory3);
                            $picurl='images/award/original/'.$picname.'.gif';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/jpg':{
                            $new_pic=urldecode($award_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/award/original/'.$picname.'.jpg';
                            $directory2 = '/home/docquity/public_html/images/award/256 x 256/'.$picname.'.jpg';
                            $directory3 = '/home/docquity/public_html/images/award/128 x 128/'.$picname.'.jpg';
                            header ( 'Content-type:image/jpg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/award/original/'.$picname.'.jpg';
                            imagedestroy ( $image );
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "Award Pic Should Be Only JPG, JPEG, PNG & GIF");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    }
                 }
                 $result=$res->create_award($user_id, $title, $description, $date, $picurl, $live_link, $conn);
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
    //////////////////// End Code To Create Award /////////////////////

    ////////////////////////// Code for Reset Password /////////////////////////
    public function ResetPassword(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
            $user_auth_key      =   $_POST['user_auth_key'];
            $password           =   $_POST['password'];
            $new_password       =   $_POST['new_password'];
            $confirm_password   =   $_POST['confirm_password'];
            $format             =   $_POST['format'];
        $this->dbConnect();
        $res = new setService();
        $result = $res->CheckAuthentication($user_auth_key);
        if($new_password != $confirm_password){
            $message = array('status' => "0","msg" => "new password do not match with confirm password");
            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($message), 200) : $this->response($this->json($message), 200);
        }
        if($result != false){   
            $response = $res->reset_password($user_auth_key,$password,$new_password,$confirm_password);
            $this->dbClose();
        }
        else{
            $error = array('status' => "0","msg" => "Not Authorised to do this");
            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
        }
    }
    ///////////////////////////// End Code For Resert Password ////////////////// 
    
    /////////////////////////// Code To Generate Random Alphanumeric Password ////////////////////
    public function generate_random_password(){
        $length = 6;
        $alphabets = range('A','Z');
        $numbers = range('0','9');
        $additional_characters = array('_','.');
        $final_array = array_merge($alphabets,$numbers,$additional_characters);

        $password = '';

        while($length--) {
          $key = array_rand($final_array);
          $password .= $final_array[$key];
        }

        return $password;
    }
    /////////////////////////// End Code To Generate Random Alphanumeric Password /////////////////
    
    /////////////// Code For Forget Password ///////////////////////////////////
    public function ForgetPassword(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        // Call Password Generator
        $new_password = $this->generate_random_password();
        //$new_password = rand(11111,99999);
        $email      =   $_POST['email'];
        $format     =   $_POST['format'];
        $this->dbConnect();
        $conn            =  $this->db;
        $res = new setService();
        if(!empty($_POST['email'])){
            $result = $res->forget_password($email,$new_password,$conn);
        }
        else{
            $error = array('status' => "0","msg" => "Invalid Input !!");
            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
        }
    }
    /////////////// End Code For Forget Password //////////////////////////////
    
    ////////////// Code To Update User Profiie ////////////////
    public function SetProfile(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $user_id            =    $_POST['user_id'];
        $user_auth_key      =    $_POST['user_auth_key'];
        $first_name         =    $_POST['firstname'];
        $last_name          =    $_POST['lastname'];
        $email              =    $_POST['email'];
        $practice_since     =    $_POST['practicing_since'];
        $appointment_contact=    $_POST['appointment_contact'];
        $office_contact     =    $_POST['office_contact'];
        $country            =    $_POST['country'];
        $city               =    $_POST['city'];
        $state              =    $_POST['state'];
        $mobile             =    $_POST['mobile'];
        $gender             =    $_POST['gender'];
        $dob                =    $_POST['dob'];
//        $bio                =    $_POST['bio'];
        $contact_note       =    $_POST['contact_note'];
        $language           =    $_POST['language'];
//        $profile_pic        =    $_POST['profile_pic'];
//        $profile_pic_type   =    $_POST['profile_pic_type'];
        $youtube_url        =    $_POST['youtube_url'];
        $twitter_url        =    $_POST['twitter_url'];
        $linkedin_url       =    $_POST['linkedin_url'];
        $format             =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        $picurl='';
        if(!empty($user_id) && !empty($user_auth_key)){
            // code for checking authentication....
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
            if($result!=false){
                /*if($profile_pic!=''){
                    $picname='profile_'.time().'_'.$user_id;
                    switch($profile_pic_type){
                        case 'image/jpeg':{
                            $new_pic=urldecode($profile_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.jpeg';
                            $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.jpeg';
                            $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.jpeg';
                            header ( 'Content-type:image/jpeg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/profile/original/'.$picname.'.jpeg';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/png':{
                            $new_pic=urldecode($profile_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.png';
                            $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.png';
                            $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.png';
                            header ( 'Content-type:image/png' );
                            imagepng($image, $directory1);
                            imagepng($image, $directory2);
                            imagepng($image, $directory3);
                            $picurl='images/profile/original/'.$picname.'.png';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/gif':{
                            $new_pic=urldecode($profile_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.gif';
                            $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.gif';
                            $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.gif';
                            header ( 'Content-type:image/gif' );
                            imagegif($image, $directory1);
                            imagegif($image, $directory2);
                            imagegif($image, $directory3);
                            $picurl='images/profile/original/'.$picname.'.gif';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/jpg':{
                            $new_pic=urldecode($profile_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.jpg';
                            $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.jpg';
                            $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.jpg';
                            header ( 'Content-type:image/jpg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/profile/original/'.$picname.'.jpg';
                            imagedestroy ( $image );
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "User Pic Should Be Only JPG, JPEG, PNG & GIF");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    }
                }*/
                $result=$res->set_profile($user_id, $first_name, $last_name, $email, $practice_since, $appointment_contact, $office_contact, $country, $city, $state, $mobile, $gender, $dob, $contact_note, $language, $youtube_url, $twitter_url, $linkedin_url, $conn);
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
    /////////////////////// End Code To Update User Profile /////////////////////////
    
    /////////////////////// Code To Update Biography //////////////////////
    public function SetBiography(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $user_id               =    $_POST['user_id'];
        $user_auth_key         =    $_POST['user_auth_key'];
        $bio                   =    $_POST['bio'];
        $format         =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($bio) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                  $result=$res->set_biography($user_id, $bio, $conn);
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
    /////////////////////// End Code To Update Biography /////////////////////
    
    /////////////////////// Code To Update Association ///////////////////////////
    public function SetAssociation(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $association_id        =    $_POST['association_id'];
        $user_id               =    $_POST['user_id'];
        $user_auth_key         =    $_POST['user_auth_key'];
        $association_name      =    $_POST['association_name'];
        $position              =    $_POST['position'];
        $location              =    $_POST['location'];
        $start_date            =    $_POST['start_date'];
        $end_date              =    $_POST['end_date'];
        $description           =    $_POST['description'];
        $format         =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($association_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                  $result=$res->set_association($association_id, $user_id, $association_name, $position, $location, $start_date, $end_date, $description, $conn);
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
    /////////////////////// End Code To Update Association ///////////////////////
    
    /////////////////////// Code To Update Education ////////////////////////
     public function SetEducation(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $education_id             =    $_POST['education_id'];
        $user_id                  =    $_POST['user_id'];
        $user_auth_key            =    $_POST['user_auth_key'];
        $school_name              =    $_POST['school_name'];
        $start_date               =    $_POST['start_date'];
        $end_date                 =    $_POST['end_date'];
        $degree                   =    $_POST['degree'];
        $field_of_study           =    $_POST['field_of_study'];
        $grade                    =    $_POST['grade'];
        $activities_and_societies =    $_POST['activities_and_societies'];
        $description              =    $_POST['description'];
        $format                   =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($education_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                  $result=$res->set_education($education_id, $user_id, $school_name, $start_date, $end_date, $degree, $field_of_study, $grade, $activities_and_societies, $description, $conn);
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
    /////////////////////// End Code To Update Education /////////////////////
    
    /////////////////////// Code To Update Speciality ///////////////////////
     public function SetSpeciality(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $speciality_id            =    $_POST['speciality_id'];
        $user_id                  =    $_POST['user_id'];
        $user_auth_key            =    $_POST['user_auth_key'];
        $speciality_name          =    $_POST['speciality_name'];
        $format                   =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($speciality_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                 $result=$res->set_speciality($speciality_id, $user_id, $speciality_name, $conn);
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
    /////////////////////// End Code To Update Speciality /////////////////////
    
    /////////////////////// Code To Update Interest ////////////////////////
     public function SetInterest(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $interest_id            =    $_POST['interest_id'];
        $user_id                =    $_POST['user_id'];
        $user_auth_key          =    $_POST['user_auth_key'];
        $interest_name          =    $_POST['interest_name'];
        $format                 =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($interest_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                 $result=$res->set_interest($interest_id, $user_id, $interest_name, $conn);
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
    /////////////////////// End Code To Update Interest //////////////////////
    
    /////////////////////// Code To Update Research ////////////////////////
    public function SetResearch(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $research_id      =    $_POST['research_id'];
        $user_id          =    $_POST['user_id'];
        $user_auth_key    =    $_POST['user_auth_key'];
        $title            =    $_POST['title'];
        $summary          =    $_POST['summary'];
        $format           =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($research_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                 $result=$res->set_research($research_id, $user_id, $title, $summary, $conn);
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
    /////////////////////// End Code To Update Research /////////////////////
    
    /////////////////////// Code To Update Project /////////////////////////
    public function SetProject(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $project_id       =    $_POST['project_id'];
        $research_id      =    $_POST['research_id'];
        $user_id          =    $_POST['user_id'];
        $user_auth_key    =    $_POST['user_auth_key'];
        $title            =    $_POST['title'];
        $description      =    $_POST['description'];
        $caption          =    $_POST['caption'];
        $image            =    $_POST['image'];
        $image_type       =    $_POST['image_type'];
        $file             =    $_POST['file'];
        $file_type        =    $_POST['file_type'];
        $project_link     =    $_POST['project_link'];
        $format           =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        $picurl = '';
        $fileurl = '';
        if(!empty($project_id) && !empty($research_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                 if($image!=''){
                     $picname='project'.time().'_'.$user_id;
                     switch($image_type){
                        case 'image/jpeg':{
                            $new_pic=urldecode($image);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/project/original/'.$picname.'.jpeg';
                            $directory2 = '/home/docquity/public_html/images/project/256 x 256/'.$picname.'.jpeg';
                            $directory3 = '/home/docquity/public_html/images/project/128 x 128/'.$picname.'.jpeg';
                            header ( 'Content-type:image/jpeg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/project/original/'.$picname.'.jpeg';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/png':{
                            $new_pic=urldecode($image);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/project/original/'.$picname.'.png';
                            $directory2 = '/home/docquity/public_html/images/project/256 x 256/'.$picname.'.png';
                            $directory3 = '/home/docquity/public_html/images/project/128 x 128/'.$picname.'.png';
                            header ( 'Content-type:image/png' );
                            imagepng($image, $directory1);
                            imagepng($image, $directory2);
                            imagepng($image, $directory3);
                            $picurl='images/project/original/'.$picname.'.png';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/gif':{
                            $new_pic=urldecode($image);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/project/original/'.$picname.'.gif';
                            $directory2 = '/home/docquity/public_html/images/project/256 x 256/'.$picname.'.gif';
                            $directory3 = '/home/docquity/public_html/images/project/128 x 128/'.$picname.'.gif';
                            header ( 'Content-type:image/gif' );
                            imagegif($image, $directory1);
                            imagegif($image, $directory2);
                            imagegif($image, $directory3);
                            $picurl='images/project/original/'.$picname.'.gif';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/jpg':{
                            $new_pic=urldecode($image);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/project/original/'.$picname.'.jpg';
                            $directory2 = '/home/docquity/public_html/images/project/256 x 256/'.$picname.'.jpg';
                            $directory3 = '/home/docquity/public_html/images/project/128 x 128/'.$picname.'.jpg';
                            header ( 'Content-type:image/jpg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/project/original/'.$picname.'.jpg';
                            imagedestroy ( $image );
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "Project Pic Should Be Only JPG, JPEG, PNG & GIF");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    }
                 }
                 if($file!=''){
                     $filename='project'.time().'_'.$user_id;
                     switch($file_type){
                        case 'application/pdf':{
                            $dec=urldecode($file);
                            $new_file=str_replace(' ','+',$dec); 
                            $base64_decode = base64_decode($new_file);
                            $directory='/home/docquity/public_html/doc/project/'.$filename.'.pdf';
                            file_put_contents($directory, $base64_decode);
                            $fileurl='doc/project/'.$filename.'.pdf';
                            break;
                        }
                        case 'application/msword':{
                            $dec=urldecode($file);
                            $new_file=str_replace(' ','+',$dec); 
                            $base64_decode = base64_decode($new_file);
                            $directory='/home/docquity/public_html/doc/project/'.$filename.'.doc';
                            file_put_contents($directory, $base64_decode);
                            $fileurl='doc/project/'.$filename.'.doc';
                            break;
                        }
                        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':{
                            $dec=urldecode($file);
                            $new_file=str_replace(' ','+',$dec); 
                            $base64_decode = base64_decode($new_file);
                            $directory='/home/docquity/public_html/doc/project/'.$filename.'.docx';
                            file_put_contents($directory, $base64_decode);
                            $fileurl='doc/project/'.$filename.'.docx';
                            break;
                        }
                        case 'text/plain':{
                            $dec=urldecode($file);
                            $new_file=str_replace(' ','+',$dec); 
                            $base64_decode = base64_decode($new_file);
                            $directory='/home/docquity/public_html/doc/project/'.$filename.'.txt';
                            file_put_contents($directory, $base64_decode);
                            $fileurl='doc/project/'.$filename.'.txt';
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "File Should Be Only pdf, doc, docx & txt");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    }
                 }
                 $result=$res->set_project($project_id, $research_id, $user_id, $title, $description, $caption, $picurl, $fileurl, $project_link, $conn);
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
    /////////////////////// End Code To Update Project //////////////////////
    
    /////////////////////// Code To Update Awards ///////////////////////
     public function SetAward(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $award_id       =    $_POST['award_id'];
        $user_id        =    $_POST['user_id'];
        $user_auth_key  =    $_POST['user_auth_key'];
        $title          =    $_POST['title'];
        $description    =    $_POST['description'];
        $date           =    $_POST['date'];
        $award_pic      =    $_POST['award_pic'];
        $award_pic_type =    $_POST['award_pic_type'];
        $live_link      =    $_POST['live_link'];
        $format         =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        $picurl = '';
        if(!empty($award_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                 if($award_pic!=''){
                     $picname='award_'.time().'_'.$user_id;
                     switch($award_pic_type){
                        case 'image/jpeg':{
                            $new_pic=urldecode($award_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/award/original/'.$picname.'.jpeg';
                            $directory2 = '/home/docquity/public_html/images/award/256 x 256/'.$picname.'.jpeg';
                            $directory3 = '/home/docquity/public_html/images/award/128 x 128/'.$picname.'.jpeg';
                            header ( 'Content-type:image/jpeg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/award/original/'.$picname.'.jpeg';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/png':{
                            $new_pic=urldecode($award_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/award/original/'.$picname.'.png';
                            $directory2 = '/home/docquity/public_html/images/award/256 x 256/'.$picname.'.png';
                            $directory3 = '/home/docquity/public_html/images/award/128 x 128/'.$picname.'.png';
                            header ( 'Content-type:image/png' );
                            imagepng($image, $directory1);
                            imagepng($image, $directory2);
                            imagepng($image, $directory3);
                            $picurl='images/award/original/'.$picname.'.png';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/gif':{
                            $new_pic=urldecode($award_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/award/original/'.$picname.'.gif';
                            $directory2 = '/home/docquity/public_html/images/award/256 x 256/'.$picname.'.gif';
                            $directory3 = '/home/docquity/public_html/images/award/128 x 128/'.$picname.'.gif';
                            header ( 'Content-type:image/gif' );
                            imagegif($image, $directory1);
                            imagegif($image, $directory2);
                            imagegif($image, $directory3);
                            $picurl='images/award/original/'.$picname.'.gif';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/jpg':{
                            $new_pic=urldecode($award_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/award/original/'.$picname.'.jpg';
                            $directory2 = '/home/docquity/public_html/images/award/256 x 256/'.$picname.'.jpg';
                            $directory3 = '/home/docquity/public_html/images/award/128 x 128/'.$picname.'.jpg';
                            header ( 'Content-type:image/jpg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/award/original/'.$picname.'.jpg';
                            imagedestroy ( $image );
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "Award Pic Should Be Only JPG, JPEG, PNG & GIF");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    }
                 }
                 $result=$res->set_award($award_id, $user_id, $title, $description, $date, $picurl, $live_link, $conn);
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
    /////////////////////// End Code To Update Awards /////////////////////
    
    /////////////////////// Code To Remove Association /////////////////////
    public function RemoveAssociation(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $association_id        =    $_POST['association_id'];
        $user_id               =    $_POST['user_id'];
        $user_auth_key         =    $_POST['user_auth_key'];
        $format         =    $_POST['format'];
        $this->dbConnect();
        $conn  =  $this->db;
        $res   = new setService();
        if(!empty($association_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                  $result=$res->remove_association($association_id, $user_id, $conn);
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
    /////////////////////// End Code To Remove Association //////////////////////
    
    /////////////////////// Code To Remove Education /////////////////////////
    public function RemoveEducation(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $education_id        =    $_POST['education_id'];
        $user_id             =    $_POST['user_id'];
        $user_auth_key       =    $_POST['user_auth_key'];
        $format         =    $_POST['format'];
        $this->dbConnect();
        $conn  =  $this->db;
        $res   = new setService();
        if(!empty($education_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                  $result=$res->remove_education($education_id, $user_id, $conn);
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
    /////////////////////// End Code To Remove Education //////////////////////
    
    /////////////////////// Code To Remove Research ////////////////////////
    public function RemoveResearch(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $research_id         =    $_POST['research_id'];
        $user_id             =    $_POST['user_id'];
        $user_auth_key       =    $_POST['user_auth_key'];
        $format         =    $_POST['format'];
        $this->dbConnect();
        $conn  =  $this->db;
        $res   = new setService();
        if(!empty($research_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                  $result=$res->remove_research($research_id, $user_id, $conn);
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
    /////////////////////// End Code To Remove Research /////////////////////
    
    /////////////////////// Code To Remove Project ///////////////////////
    public function RemoveProject(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $project_id       =    $_POST['project_id'];
        $research_id      =    $_POST['research_id'];
        $user_id          =    $_POST['user_id'];
        $user_auth_key    =    $_POST['user_auth_key'];
        $format           =    $_POST['format'];
        $this->dbConnect();
        $conn  =  $this->db;
        $res   = new setService();
        if(!empty($project_id) && !empty($research_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                  $result=$res->remove_project($project_id, $research_id, $user_id, $conn);
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
    /////////////////////// End Code To Remove Project ////////////////////
    
    /////////////////////// Code To Remove Interest ///////////////////////
    public function RemoveInterest(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $interest_id         =    $_POST['interest_id'];
        $user_id             =    $_POST['user_id'];
        $user_auth_key       =    $_POST['user_auth_key'];
        $format              =    $_POST['format'];
        $this->dbConnect();
        $conn  =  $this->db;
        $res   = new setService();
        if(!empty($interest_id) && !empty($user_id) && !empty($user_auth_key)){
            $result = $res->CheckAuthentication($user_id,$user_auth_key,$conn);
             if($result!=false){
                  $result=$res->remove_interest($interest_id, $user_id, $conn);
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
    /////////////////////// End Code To Remove Interest /////////////////////
    
    /////////////////////// Code To Active Doctor /////////////////////////
    public function ActiveUser(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $userid      =    $_POST['userid'];
        $authkey     =    $_POST['authkey'];
        $format      =    $_POST['format'];
        $this->dbConnect();
        $conn  =  $this->db;
        $res   = new setService();
        if(!empty($userid) && !empty($authkey)){
            $result = $res->CheckAuthentication($userid, $authkey, $conn);
            if($result!=false){
                $result=$res->active_user($userid, $conn);
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
    /////////////////////// End Code To Active Doctor /////////////////////
      
    /////////////////////// Code To Remove User /////////////////////////
    public function RemoveUser(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $userid      =    $_POST['userid'];
        $authkey     =    $_POST['authkey'];
        $format      =    $_POST['format'];
        $this->dbConnect();
        $conn  =  $this->db;
        $res   = new setService();
        if(!empty($userid) && !empty($authkey)){
            $result = $res->CheckAuthentication($userid, $authkey, $conn);
            if($result!=false){
                $result=$res->remove_user($userid, $conn);
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
    /////////////////////// End Code To Remove User //////////////////////
    
    /////////////////////// Code To Update Profile Pic ////////////////////
    public function SetUserPic(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $user_id        =    $_POST['user_id'];
        $authkey        =    $_POST['authkey'];
        $userpic        =    $_POST['userpic'];
        $userpictype    =    $_POST['userpictype'];
        $format         =    $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        $picurl='';
        //echo $userpic;die;
        //ini_set('memory_limit', '64M');
        if(!empty($user_id) && !empty($authkey)){
            $result = $res->CheckAuthentication($user_id, $authkey, $conn);
            if($result!=false){
                 //Handle User Pic
                if($userpic!=''){
                    $picname='profile_'.time().'_'.$user_id;
                    switch($userpictype){
                         case 'image/jpeg':{
                            $new_pic=urldecode($userpic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            //echo $image;
                            $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.jpeg';
                            $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.jpeg';
                            $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.jpeg';
                            header ( 'Content-type:image/jpeg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/profile/original/'.$picname.'.jpeg';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/png':{
                            $new_pic=urldecode($userpic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.png';
                            $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.png';
                            $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.png';
                            header ( 'Content-type:image/png' );
                            imagepng($image, $directory1);
                            imagepng($image, $directory2);
                            imagepng($image, $directory3);
                            $picurl='images/profile/original/'.$picname.'.png';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/gif':{
                            $new_pic=urldecode($userpic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.gif';
                            $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.gif';
                            $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.gif';
                            header ( 'Content-type:image/gif' );
                            imagegif($image, $directory1);
                            imagegif($image, $directory2);
                            imagegif($image, $directory3);
                            $picurl='images/profile/original/'.$picname.'.gif';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/jpg':{
                            $new_pic=urldecode($userpic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/profile/original/'.$picname.'.jpg';
                            $directory2 = '/home/docquity/public_html/images/profile/256 x 256/'.$picname.'.jpg';
                            $directory3 = '/home/docquity/public_html/images/profile/128 x 128/'.$picname.'.jpg';
                            header ( 'Content-type:image/jpg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/profile/original/'.$picname.'.jpg';
                            imagedestroy ( $image );
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "User Pic Should Be Only JPG, JPEG, PNG & GIF");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    } 
                }
                //End Handle User Pic
                $result=$res->set_user_pic($user_id, $picurl, $conn);
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
    /////////////////////// End Code To Update Profile Pic //////////////////////
    
    /////////////////////// Code To Create Group /////////////////////////
    public function CreateGroup(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $authkey = $_POST['authkey'];
        $group_name = $_POST['group_name'];
        $group_desc = $_POST['group_desc'];
        $owner_id = $_POST['owner_id'];
        $group_pic = $_POST['group_pic'];
        $group_pic_type = $_POST['group_pic_type'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        $picurl='';
        if(!empty($authkey) && !empty($group_name) && !empty($owner_id)){
            $result = $res->CheckAuthentication($owner_id, $authkey, $conn);
            if($result!=false){
                //Handle Group Pic
                if($group_pic!=''){
                    $picname='group'.time().'_'.$owner_id;
                    switch($group_pic_type){
                         case 'image/jpeg':{
                            $new_pic=urldecode($group_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            //echo $image;
                            $directory1 = '/home/docquity/public_html/images/group/original/'.$picname.'.jpeg';
                            $directory2 = '/home/docquity/public_html/images/group/256 x 256/'.$picname.'.jpeg';
                            $directory3 = '/home/docquity/public_html/images/group/128 x 128/'.$picname.'.jpeg';
                            header ( 'Content-type:image/jpeg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/group/original/'.$picname.'.jpeg';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/png':{
                            $new_pic=urldecode($group_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/group/original/'.$picname.'.png';
                            $directory2 = '/home/docquity/public_html/images/group/256 x 256/'.$picname.'.png';
                            $directory3 = '/home/docquity/public_html/images/group/128 x 128/'.$picname.'.png';
                            header ( 'Content-type:image/png' );
                            imagepng($image, $directory1);
                            imagepng($image, $directory2);
                            imagepng($image, $directory3);
                            $picurl='images/group/original/'.$picname.'.png';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/gif':{
                            $new_pic=urldecode($group_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/group/original/'.$picname.'.gif';
                            $directory2 = '/home/docquity/public_html/images/group/256 x 256/'.$picname.'.gif';
                            $directory3 = '/home/docquity/public_html/images/group/128 x 128/'.$picname.'.gif';
                            header ( 'Content-type:image/gif' );
                            imagegif($image, $directory1);
                            imagegif($image, $directory2);
                            imagegif($image, $directory3);
                            $picurl='images/group/original/'.$picname.'.gif';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/jpg':{
                            $new_pic=urldecode($group_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/group/original/'.$picname.'.jpg';
                            $directory2 = '/home/docquity/public_html/images/group/256 x 256/'.$picname.'.jpg';
                            $directory3 = '/home/docquity/public_html/images/group/128 x 128/'.$picname.'.jpg';
                            header ( 'Content-type:image/jpg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/group/original/'.$picname.'.jpg';
                            imagedestroy ( $image );
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "Group Pic Should Be Only JPG, JPEG, PNG & GIF");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    } 
                }
                //End Handle Group Pic
                $result=$res->create_group($group_name, $group_desc, $owner_id, $picurl, $conn);
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
    /////////////////////// End Code To Create Group /////////////////////
    
    /////////////////////// Code To Edit Group ///////////////////////
    public function EditGroup(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $group_id = $_POST['group_id'];
        $authkey = $_POST['authkey'];
        $group_name = $_POST['group_name'];
        $group_desc = $_POST['group_desc'];
        $owner_id = $_POST['owner_id'];
        $group_pic = $_POST['group_pic'];
        $group_pic_type = $_POST['group_pic_type'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        $picurl='';
        if(!empty($group_id) && !empty($authkey) && !empty($group_name) && !empty($owner_id)){
            $result = $res->CheckAuthentication($owner_id, $authkey, $conn);
            if($result!=false){
                //Handle Group Pic
                if($group_pic!=''){
                    $picname='group'.time().'_'.$owner_id;
                    switch($group_pic_type){
                         case 'image/jpeg':{
                            $new_pic=urldecode($group_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            //echo $image;
                            $directory1 = '/home/docquity/public_html/images/group/original/'.$picname.'.jpeg';
                            $directory2 = '/home/docquity/public_html/images/group/256 x 256/'.$picname.'.jpeg';
                            $directory3 = '/home/docquity/public_html/images/group/128 x 128/'.$picname.'.jpeg';
                            header ( 'Content-type:image/jpeg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/group/original/'.$picname.'.jpeg';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/png':{
                            $new_pic=urldecode($group_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/group/original/'.$picname.'.png';
                            $directory2 = '/home/docquity/public_html/images/group/256 x 256/'.$picname.'.png';
                            $directory3 = '/home/docquity/public_html/images/group/128 x 128/'.$picname.'.png';
                            header ( 'Content-type:image/png' );
                            imagepng($image, $directory1);
                            imagepng($image, $directory2);
                            imagepng($image, $directory3);
                            $picurl='images/group/original/'.$picname.'.png';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/gif':{
                            $new_pic=urldecode($group_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/group/original/'.$picname.'.gif';
                            $directory2 = '/home/docquity/public_html/images/group/256 x 256/'.$picname.'.gif';
                            $directory3 = '/home/docquity/public_html/images/group/128 x 128/'.$picname.'.gif';
                            header ( 'Content-type:image/gif' );
                            imagegif($image, $directory1);
                            imagegif($image, $directory2);
                            imagegif($image, $directory3);
                            $picurl='images/group/original/'.$picname.'.gif';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/jpg':{
                            $new_pic=urldecode($group_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/group/original/'.$picname.'.jpg';
                            $directory2 = '/home/docquity/public_html/images/group/256 x 256/'.$picname.'.jpg';
                            $directory3 = '/home/docquity/public_html/images/group/128 x 128/'.$picname.'.jpg';
                            header ( 'Content-type:image/jpg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/group/original/'.$picname.'.jpg';
                            imagedestroy ( $image );
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "Group Pic Should Be Only JPG, JPEG, PNG & GIF");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    } 
                }
                //End Handle Group Pic
                $result=$res->edit_group($group_id, $group_name, $group_desc, $owner_id, $picurl, $conn);
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
    /////////////////////// End Code To Edit Group /////////////////////
    
    /////////////////////// Code To Delete Group //////////////////////////
    public function RemoveGroup(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $group_id = $_POST['group_id'];
        $authkey = $_POST['authkey'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($group_id) && !empty($authkey)){
            $result = $res->CheckAuthentication('', $authkey, $conn);
            if($result!=false){
                $result=$res->remove_group($group_id, $conn);
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
    /////////////////////// End Code To Delete Group //////////////////////
    
    /////////////////////// Code To Add Member Into Group //////////////////////
    public function AddMemberToGroup(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $group_id = $_POST['group_id'];
        $member_id = $_POST['member_id'];
        $authkey = $_POST['authkey'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($group_id) && !empty($member_id) && !empty($authkey)){
            $result = $res->CheckAuthentication($member_id, $authkey, $conn);
            if($result!=false){
                $result=$res->add_member_to_group($group_id, $member_id, $conn);
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
    /////////////////////// End Code To Add Memeber Into Group ////////////////////
    
    /////////////////////// Code To Delete Member From Group //////////////////////
    public function RemoveMemberFromGroup(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $group_id = $_POST['group_id'];
        $member_id = $_POST['member_id'];
        $authkey = $_POST['authkey'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($group_id) && !empty($member_id) && !empty($authkey)){
            $result = $res->CheckAuthentication($member_id, $authkey, $conn);
            if($result!=false){
                $result=$res->remove_member_from_group($group_id, $member_id, $conn);
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
    /////////////////////// End Code To Delete Member From Group ////////////////////
    
     /////////////////////// Code To Create Discussion /////////////////////////
    public function CreateDiscussion(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $authkey = $_POST['authkey'];
        $discussion_name = $_POST['discussion_name'];
        $discussion_desc = $_POST['discussion_desc'];
        $owner_id = $_POST['owner_id'];
        $discussion_pic = $_POST['discussion_pic'];
        $discussion_pic_type = $_POST['discussion_pic_type'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        $picurl='';
        if(!empty($authkey) && !empty($discussion_name) && !empty($owner_id)){
            $result = $res->CheckAuthentication($owner_id, $authkey, $conn);
            if($result!=false){
                //Handle Discussion Pic
                if($discussion_pic!=''){
                    $picname='discussion'.time().'_'.$owner_id;
                    switch($discussion_pic_type){
                         case 'image/jpeg':{
                            $new_pic=urldecode($discussion_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            //echo $image;
                            $directory1 = '/home/docquity/public_html/images/discussion/original/'.$picname.'.jpeg';
                            $directory2 = '/home/docquity/public_html/images/discussion/256 x 256/'.$picname.'.jpeg';
                            $directory3 = '/home/docquity/public_html/images/discussion/128 x 128/'.$picname.'.jpeg';
                            header ( 'Content-type:image/jpeg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/discussion/original/'.$picname.'.jpeg';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/png':{
                            $new_pic=urldecode($discussion_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/discussion/original/'.$picname.'.png';
                            $directory2 = '/home/docquity/public_html/images/discussion/256 x 256/'.$picname.'.png';
                            $directory3 = '/home/docquity/public_html/images/discussion/128 x 128/'.$picname.'.png';
                            header ( 'Content-type:image/png' );
                            imagepng($image, $directory1);
                            imagepng($image, $directory2);
                            imagepng($image, $directory3);
                            $picurl='images/discussion/original/'.$picname.'.png';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/gif':{
                            $new_pic=urldecode($discussion_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/discussion/original/'.$picname.'.gif';
                            $directory2 = '/home/docquity/public_html/images/discussion/256 x 256/'.$picname.'.gif';
                            $directory3 = '/home/docquity/public_html/images/discussion/128 x 128/'.$picname.'.gif';
                            header ( 'Content-type:image/gif' );
                            imagegif($image, $directory1);
                            imagegif($image, $directory2);
                            imagegif($image, $directory3);
                            $picurl='images/discussion/original/'.$picname.'.gif';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/jpg':{
                            $new_pic=urldecode($discussion_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/discussion/original/'.$picname.'.jpg';
                            $directory2 = '/home/docquity/public_html/images/discussion/256 x 256/'.$picname.'.jpg';
                            $directory3 = '/home/docquity/public_html/images/discussion/128 x 128/'.$picname.'.jpg';
                            header ( 'Content-type:image/jpg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/discussion/original/'.$picname.'.jpg';
                            imagedestroy ( $image );
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "Discussion Pic Should Be Only JPG, JPEG, PNG & GIF");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    } 
                }
                //End Handle Discussion Pic
                $result=$res->create_discussion($discussion_name, $discussion_desc, $owner_id, $picurl, $conn);
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
    /////////////////////// End Code To Create Discussion /////////////////////
    
    /////////////////////// Code To Edit Discussion ///////////////////////
    public function EditDiscussion(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $discussion_id = $_POST['discussion_id'];
        $authkey = $_POST['authkey'];
        $discussion_name = $_POST['discussion_name'];
        $discussion_desc = $_POST['discussion_desc'];
        $owner_id = $_POST['owner_id'];
        $discussion_pic = $_POST['discussion_pic'];
        $discussion_pic_type = $_POST['discussion_pic_type'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        $picurl='';
        if(!empty($discussion_id) && !empty($authkey) && !empty($discussion_name) && !empty($owner_id)){
            $result = $res->CheckAuthentication($owner_id, $authkey, $conn);
            if($result!=false){
                //Handle Discussion Pic
                if($discussion_pic!=''){
                    $picname='discussion'.time().'_'.$owner_id;
                    switch($discussion_pic_type){
                         case 'image/jpeg':{
                            $new_pic=urldecode($discussion_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            //echo $image;
                            $directory1 = '/home/docquity/public_html/images/discussion/original/'.$picname.'.jpeg';
                            $directory2 = '/home/docquity/public_html/images/discussion/256 x 256/'.$picname.'.jpeg';
                            $directory3 = '/home/docquity/public_html/images/discussion/128 x 128/'.$picname.'.jpeg';
                            header ( 'Content-type:image/jpeg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/discussion/original/'.$picname.'.jpeg';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/png':{
                            $new_pic=urldecode($discussion_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/discussion/original/'.$picname.'.png';
                            $directory2 = '/home/docquity/public_html/images/discussion/256 x 256/'.$picname.'.png';
                            $directory3 = '/home/docquity/public_html/images/discussion/128 x 128/'.$picname.'.png';
                            header ( 'Content-type:image/png' );
                            imagepng($image, $directory1);
                            imagepng($image, $directory2);
                            imagepng($image, $directory3);
                            $picurl='images/discussion/original/'.$picname.'.png';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/gif':{
                            $new_pic=urldecode($discussion_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/discussion/original/'.$picname.'.gif';
                            $directory2 = '/home/docquity/public_html/images/discussion/256 x 256/'.$picname.'.gif';
                            $directory3 = '/home/docquity/public_html/images/discussion/128 x 128/'.$picname.'.gif';
                            header ( 'Content-type:image/gif' );
                            imagegif($image, $directory1);
                            imagegif($image, $directory2);
                            imagegif($image, $directory3);
                            $picurl='images/discussion/original/'.$picname.'.gif';
                            imagedestroy ( $image );
                            break;
                        }
                        case 'image/jpg':{
                            $new_pic=urldecode($discussion_pic);
                            $new_pic=str_replace(' ','+',$new_pic);
                            $content = base64_decode($new_pic);
                            $image = imagecreatefromstring($content);
                            $directory1 = '/home/docquity/public_html/images/discussion/original/'.$picname.'.jpg';
                            $directory2 = '/home/docquity/public_html/images/discussion/256 x 256/'.$picname.'.jpg';
                            $directory3 = '/home/docquity/public_html/images/discussion/128 x 128/'.$picname.'.jpg';
                            header ( 'Content-type:image/jpg' );
                            imagejpeg($image, $directory1);
                            imagejpeg($image, $directory2);
                            imagejpeg($image, $directory3);
                            $picurl='images/discussion/original/'.$picname.'.jpg';
                            imagedestroy ( $image );
                            break;
                        }
                        default:{
                            $error = array('status' => "0","msg" => "Discussion Pic Should Be Only JPG, JPEG, PNG & GIF");
                            ($_REQUEST['format'] == 'xml') ? $this->response($this->xml($error), 200) : $this->response($this->json($error), 200);
                        }
                    } 
                }
                //End Handle Discussion Pic
                $result=$res->edit_discussion($discussion_id, $discussion_name, $discussion_desc, $owner_id, $picurl, $conn);
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
    /////////////////////// End Code To Edit Discussion /////////////////////
    
    /////////////////////// Code To Delete Discussion //////////////////////////
    public function RemoveDiscussion(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $discussion_id = $_POST['discussion_id'];
        $authkey = $_POST['authkey'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($discussion_id) && !empty($authkey)){
            $result = $res->CheckAuthentication('', $authkey, $conn);
            if($result!=false){
                $result=$res->remove_discussion($discussion_id, $conn);
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
    /////////////////////// End Code To Delete Discussion //////////////////////
    
    /////////////////////// Code To Add Member Into Discussion //////////////////////
    public function AddMemberToDiscussion(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $discussion_id = $_POST['discussion_id'];
        $member_id = $_POST['member_id'];
        $authkey = $_POST['authkey'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($discussion_id) && !empty($member_id) && !empty($authkey)){
            $result = $res->CheckAuthentication($member_id, $authkey, $conn);
            if($result!=false){
                $result=$res->add_member_to_discussion($discussion_id, $member_id, $conn);
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
    /////////////////////// End Code To Add Memeber Into Discussion ////////////////////
    
    /////////////////////// Code To Delete Member From Discussion //////////////////////
    public function RemoveMemberFromDiscussion(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $discussion_id = $_POST['discussion_id'];
        $member_id = $_POST['member_id'];
        $authkey = $_POST['authkey'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($discussion_id) && !empty($member_id) && !empty($authkey)){
            $result = $res->CheckAuthentication($member_id, $authkey, $conn);
            if($result!=false){
                $result=$res->remove_member_from_discussion($discussion_id, $member_id, $conn);
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
    /////////////////////// End Code To Delete Member From Discussion ////////////////////
    
    /////////////////////// Code To Send Friend Request ////////////////////////
    public function RequestFriend(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $requester_id = $_POST['requester_id'];
        $requestee_id = $_POST['requestee_id'];
        $authkey = $_POST['authkey'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($requester_id) && !empty($requestee_id) && !empty($authkey)){
            $result = $res->CheckAuthentication($requester_id, $authkey, $conn);
            if($result!=false){
                $result=$res->request_friend($requester_id, $requestee_id, $conn);
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
    /////////////////////// End Code To Send Friend Request ///////////////////
    
    /////////////////////// Code To Accept Friend Request /////////////////////
    public function AcceptFriend(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $requester_id = $_POST['requester_id'];
        $requestee_id = $_POST['requestee_id'];
        $authkey = $_POST['authkey'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($requester_id) && !empty($requestee_id) && !empty($authkey)){
           $result = $res->CheckAuthentication($requestee_id, $authkey, $conn);
           if($result!=false){
               $result=$res->accept_friend($requester_id, $requestee_id, $conn);
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
    /////////////////////// End Code To Accept Friend Request ////////////////////
    
    
    
    /////////////////////// Code To Decline Friend Request ///////////////////////
    public function DeclineFriend(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $requester_id = $_POST['requester_id'];
        $requestee_id = $_POST['requestee_id'];
        $authkey = $_POST['authkey'];
        $format = $_POST['format'];
        $this->dbConnect();
        $conn =  $this->db;
        $res = new setService();
        if(!empty($requester_id) && !empty($requestee_id) && !empty($authkey)){
           $result = $res->CheckAuthentication($requestee_id, $authkey, $conn);
           if($result!=false){
               $result=$res->decline_friend($requester_id, $requestee_id, $conn);
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
    /////////////////////// End Code To Decline Friend Request /////////////////////
    
    /////////////////////// Testing ///////////////////////
    public function Testing(){
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
        $data=$_POST['image'];
        $type=$_POST['type'];
        $size=$_POST['size'];
        $new_data=urldecode($data);
        $new_data=str_replace(' ','+',$new_data);
        
        $content = base64_decode($new_data);
        $image = imagecreatefromstring($content);
        if($image!== false){
            switch($type){
                case 'image/jpeg':{
                    $directory = dirname(__FILE__).DIRECTORY_SEPARATOR."../../assets/Photo-Competition".DIRECTORY_SEPARATOR."comp".$title.".jpeg";
                    header ( 'Content-type:image/jpeg' );
                    imagejpeg($image, $directory);
                    imagedestroy ( $image );
                }
                case 'image/png':{
                    $directory='/home/muzicheads/public_html/images/profile/xyz.png';
                    //$directory = dirname(__FILE__).DIRECTORY_SEPARATOR."../../assets/Photo-Competition".DIRECTORY_SEPARATOR."comp".$title.".jpeg";
                    header ( 'Content-type:image/png' );
                    imagepng($image, $directory);
                    //move_uploaded_file($directory, '/images/profile/xyz.png');
                    imagedestroy ( $image );
                }
                case 'image/gif':{
                    $directory = dirname(__FILE__).DIRECTORY_SEPARATOR."../../assets/Photo-Competition".DIRECTORY_SEPARATOR."comp".$title.".jpeg";
                    header ( 'Content-type:image/gif' );
                    imagegif($image, $directory);
                    imagedestroy ( $image );
                }
                case 'image/jpg':{
                    $directory = dirname(__FILE__).DIRECTORY_SEPARATOR."../../assets/Photo-Competition".DIRECTORY_SEPARATOR."comp".$title.".jpeg";
                    header ( 'Content-type:image/gif' );
                    imagejpeg($image, $directory);
                    imagedestroy ( $image );
                }

            }
        }
        else{
             echo 'An error occurred.';
        }
    }
    
    /////////////////////// Testing ///////////////////////
    
    ////////////////////////Code For Encoding Array into xml/JSON//////////////
    private function json($data){
        header('Content-type: application/json');
        return json_encode(array(
            'posts' => $data
        ));
    }
    private function xml($posts){
        if (is_array($posts)) {
            $data = '';
            header('Content-type: text/xml');
            echo '<posts>';
            foreach ($posts as $tag => $val) {
                echo '<', $tag, '>', htmlentities($val), '</', $tag, '>';
            }
            echo '</posts>';
        }
    }
}
//Match Token
// Initiiate Library
$api = new API;
$api->processApi();
ob_end_flush();
?>

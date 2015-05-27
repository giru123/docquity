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
    $url = $web_server.'connect/webservice/getApi.php?rquest=GetDoctorList';
    //print_r($_POST);die;
    $fields = array(
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
    $result_doctor = json_decode($result,true);
    curl_close($ch);
    $status = $result_doctor['posts']['status'];
    $message = $result_doctor['posts']['msg'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Docquity | Doctor Management</title>
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
                        Doctor Management
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
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <img style="float: left; margin: 8px;margin-right: 2px;margin-bottom: 2px;" src="../img/add.jpg" width="30px">
                                    <h3 class="box-title"><a href="add_user.php">Add New Doctor</a></h3>
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Registration No</th>
                                                <th>Mobile</th>
                                                <th>Country</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if($status==1){
                                                    $count=1;
                                                    for($i=0;$i<count($result_doctor['posts'])-2;$i++){
                                                ?>
                                                        <tr>
                                                            <td><?php echo $count; ?></td>
                                                            <td><?php echo $result_doctor['posts'][$i]['first_name']; ?></td>
                                                            <td><?php echo $result_doctor['posts'][$i]['last_name']; ?></td>
                                                            <td><?php echo $result_doctor['posts'][$i]['email']; ?></td>
                                                            <td><?php echo $result_doctor['posts'][$i]['registration_no']; ?></td>
                                                            <td><?php echo $result_doctor['posts'][$i]['mobile']; ?></td>
                                                            <td><?php echo $result_doctor['posts'][$i]['country']; ?></td>
                                                            <td><?php echo $result_doctor['posts'][$i]['city']; ?></td>
                                                            <td><?php echo $result_doctor['posts'][$i]['state']; ?></td>
                                                            <td>
                                                                <?php 
                                                                    if($result_doctor['posts'][$i]['user_status']==1){
                                                                        echo 'Active';
                                                                    }
                                                                    else if($result_doctor['posts'][$i]['user_status']==9){
                                                                        echo 'Deactive';
                                                                    }
                                                                    else if($result_doctor['posts'][$i]['user_status']==8){
                                                                        echo 'Suspend';
                                                                    }
                                                                    else if($result_doctor['posts'][$i]['user_status']==0){
                                                                        echo 'Not Verified';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    if($result_doctor['posts'][$i]['user_status']==1){
                                                                        echo '<a href="javascript:;" onclick="deactiveUser('.$result_doctor['posts'][$i]['user_id'].','.$auth_key.');">Deactive</a>';
                                                                    }
                                                                    else if($result_doctor['posts'][$i]['user_status']==9 || $result_doctor['posts'][$i]['user_status']==8 || $result_doctor['posts'][$i]['user_status']==0){
                                                                        echo '<a href="javascript:;" onclick="activeUser('.$result_doctor['posts'][$i]['user_id'].','.$auth_key.');">Active</a>';
                                                                    }
                                                                    
                                                                    echo ' | <a href="edit_user.php?uid='.$result_doctor['posts'][$i]['user_id'].'">Edit</a>';
                                                                ?>
                                                            </td>
                                                        </tr>
                                                <?php
                                                        $count++;
                                                    }
                                                }
                                            ?>                                          
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                               <th>S.No.</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Registration No</th>
                                                <th>Mobile</th>
                                                <th>Country</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div>
        
        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- DATA TABES SCRIPT -->
        <script src="../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../js/AdminLTE/demo.js" type="text/javascript"></script>
        <!-- page script -->
        <script type="text/javascript">
            $(function() {
                $("#example1").dataTable({
                    "bPaginate": true,
                    "bStateSave": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": false,
                    "bInfo": true,
                    "bAutoWidth": false
                });
                $('#example2').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false
                });
            });
            //Active User function
            function activeUser(userId, authKey){
                $.ajax({
                   url: '../ajax/active_user.php?userId='+userId+'&authKey='+authKey,
                   success: function(data){
                       $('tbody').html(data);
                   }
                });
            }
            //Deactive User function
            function deactiveUser(userId, authKey){
                $.ajax({
                   url: '../ajax/deactive_user.php?userId='+userId+'&authKey='+authKey,
                   success: function(data){
                       $('tbody').html(data);
                   }
                });
            }
        </script>
    </body>
</html>
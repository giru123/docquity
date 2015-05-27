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
    $url = $web_server.'connect/webservice/getApi.php?rquest=GetTotalCount';
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
    $result_count = json_decode($result,true);
    curl_close($ch);
    $status=$result_count['posts']['status'];
    $message=$result_count['posts']['msg'];
    if($status==1){
        $total_user=$result_count['posts']['total_user'];
        $total_event=$result_count['posts']['total_event'];
        $total_genre=$result_count['posts']['total_genre'];
    }
    else{
        $total_user=0;
        $total_event=0;
        $total_genre=0;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Docquity | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <link href="../css/morris/morris.css" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <link href="../css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- fullCalendar -->
        <link href="../css/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="../css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="../css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
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
                        Dashboard
                        <small>Control panel</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-4 col-xs-8">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>
                                        <?php echo $total_user; ?>
                                    </h3>
                                    <p>
                                        Total Users
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="user-management.php" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-4 col-xs-8">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>
                                        <?php echo $total_event; ?>
                                    </h3>
                                    <p>
                                        View Events
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="event-management.php" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-4 col-xs-8">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>
                                        <?php echo $total_genre; ?>
                                    </h3>
                                    <p>
                                        Music Type
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="genre-management.php" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                        
<!--                        <div class="col-lg-3 col-xs-6">
                             small box 
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>
                                        150
                                    </h3>
                                    <p>
                                        Total Users
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div> ./col -->
                        
                    </div><!-- /.row -->

                    <!-- top row -->
                    <div class="row">
                        <div class="col-xs-12 connectedSortable">
                            
                        </div><!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-lg-6 connectedSortable"> 
                            <!-- Box (with bar chart) -->
                                
                            
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="nav-tabs-custom">
                                <!-- Tabs within a box -->
                                <ul class="nav nav-tabs pull-right">
                                    <li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>
                                    <li><a href="#sales-chart" data-toggle="tab">Donut</a></li>
                                    <li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>
                                </ul>
                                <div class="tab-content no-padding">
                                    <!-- Morris chart - Sales -->
                                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
                                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
                                </div>
                            </div><!-- /.nav-tabs-custom -->
                                             
                        </section><!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
                        <section class="col-lg-6 connectedSortable">
                            <!-- Calendar -->
                            <div class="box box-warning">
                                <div class="box-header">
                                    <i class="fa fa-calendar"></i>
                                    <div class="box-title">Calendar</div>
                                    
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <!-- button with a dropdown -->
                                        <div class="btn-group">
                                            <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button>
                                            <ul class="dropdown-menu pull-right" role="menu">
                                                <li><a href="add_event.php">Add new event</a></li>
                                                <li><a href="#">Clear events</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">View calendar</a></li>
                                            </ul>
                                        </div>
                                    </div><!-- /. tools -->                                    
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <!--The calendar -->
                                    <div id="calendar"></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
 
                        </section><!-- right col -->
                    </div><!-- /.row (main row) -->

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <!-- add new calendar event modal -->


        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- jQuery UI 1.10.3 -->
        <script src="../js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <!-- Bootstrap -->
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Morris.js charts -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="../js/plugins/morris/morris.min.js" type="text/javascript"></script>
        <!-- Sparkline -->
        <script src="../js/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- jvectormap -->
        <script src="../js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
        <script src="../js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
        <!-- fullCalendar -->
        <script src="../js/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <!-- jQuery Knob Chart -->
        <script src="../js/plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>
        <!-- daterangepicker -->
        <script src="../js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="../js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="../js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>

        <!-- AdminLTE App -->
        <script src="../js/AdminLTE/app.js" type="text/javascript"></script>
        
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="../js/AdminLTE/dashboard.js" type="text/javascript"></script>     
        
        <!-- AdminLTE for demo purposes -->
        <script src="../js/AdminLTE/demo.js" type="text/javascript"></script>

    </body>
</html>
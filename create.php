<?php
session_start();
$alert = "";
require_once "./database/config.php";
require_once "./inc/auxilliaries.php";

// AUTHENTICATION
if (!isset($_SESSION['user'])) {
    header("location: ./login.php");
    exit();
}

// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Dues details
    $name = $_POST['name'];
    $yearGroup = $_POST['year_group'];
    $amount = $_POST['amount'];
    $paymentMode = $_POST['payment_mode'];
    $paymentType = $_POST['payment_type'];
    $date = $_POST['date'];
    $phone = $_POST['phone'];

    // Check if required fields are not empty
    if (!empty($name) && !empty($yearGroup) && !empty($amount) && !empty($paymentMode)) {

        // Insert dues information
        $dues = new Admin($pdo, 'payment'); // Replace 'dues' with your actual table name
        $duesInfo = [
            'name' => $name,
            'year_group' => $yearGroup,
            'amount' => $amount,
            'payment_mode' => $paymentMode,
            'payment_type' => $paymentType,
            'phone' => $phone,
            'date' => $date
        ];

        if ($dues->create($duesInfo)) {
            header("location: ./index.php?created=1");
            $alert =  "showAlert('success', 'Details updated successfully');";
        } else {
            // Display an error message to the user using JavaScript
            echo "showAlert('error', 'Unable to create dues record');";
        }
    } else {
        // Display an error message to the user using JavaScript
        $alert =  "showAlert('error', 'Please fill in all required fields');";
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>APSU USA</title>

    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
    <!-- sweet alert -->
    <link rel="stylesheet" href="./css/sweet.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/style.js"> </script>
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" />
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css" />
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css" />
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <?php
    echo "<script>";
    echo $alert;
    echo "</script>";
    ?>
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="margin-left: 0">
            <!-- Left navbar links -->
            <ul class="navbar-nav">

                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index.php" class="btn ml-2 my-2 btn-primary text-light nav-link">Back to dashboard</a>
                </li>

            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">


            </ul>
        </nav>
        <!-- /.navbar -->


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="background-color: honeydew; margin-left: 0px">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-success">Dashboard</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item "><a href="index.php">Payment Dashboard</a></li>
                                <li class="breadcrumb-item active">Create </li>
                            </ol>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-lg-12 connectedSortable">
                            <!-- /.card -->

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title text-success text-center">
                                        Add Dues
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <h4 class="text-info fst-italic text-center">Enter Dues Information</h4>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="name">Full Name<span class="text-danger">* </span></label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Full Name" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="year_group">Year Group<span class="text-danger">* </span></label>
                                                <input type="number" class="form-control" id="year_group" name="year_group" placeholder="Enter Year Group" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="amount">Payment Amount<span class="text-danger">* </span></label>
                                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter Payment Amount" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="payment_mode">Mode of Payment<span class="text-danger">* </span></label>
                                                <input type="text" class="form-control" id="payment_mode" name="payment_mode" placeholder="Enter Mode of Payment" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label for="payment_type">Type of Payment</label>
                                                <select class="form-control" id="payment_type" name="payment_type">
                                                    <option value="full">Full</option>
                                                    <option value="part">Part</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="date">Date</label>
                                                <input type="date" class="form-control" id="date" name="date" placeholder="Enter Date">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="phone">Phone Number<span class="text-danger">* </span></label>
                                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number" required>
                                            </div>
                                        </div>

                                        <button type="submit" name="submit" class="px-4 btn mt-3 btn-success">Create Dues Entry</button>
                                    </form>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </section>
                        <!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
                        <section class="col-lg-5 connectedSortable"></section>
                        <!-- right col -->
                    </div>
                    <!-- /.row (main row) -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer text-success" style="margin-left: 0">
            <strong>Copyright &copy; 2023</strong>
            APSUUSA
            <div class="float-right d-none d-sm-inline-block">
                <a href="https://kingsdevelopers.org/">Developed By:<b> KINGS DEVELOPERS </a> </b>
            </div>
        </footer>


        <!-- /.control-sidebar -->
    </div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge("uibutton", $.ui.button);
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>

    <!-- DataTables  & Plugins -->
    <script src="./plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="./plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="./plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="./plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="./plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="./plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="./plugins/jszip/jszip.min.js"></script>
    <script src="./plugins/pdfmake/pdfmake.min.js"></script>
    <script src="./plugins/pdfmake/vfs_fonts.js"></script>
    <script src="./plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="./plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="./plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1")
                .DataTable({
                    responsive: true,
                    lengthChange: false,
                    autoWidth: false,
                    buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
                })
                .buttons()
                .container()
                .appendTo("#example1_wrapper .col-md-6:eq(0)");
            $("#example2").DataTable({
                paging: true,
                lengthChange: false,
                searching: false,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true,
            });
        });
    </script>
</body>

</html>
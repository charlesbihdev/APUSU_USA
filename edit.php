<?php
session_start();
$alert = "";
require_once "./database/config.php";
require_once "./inc/auxilliaries.php";

// AUTHENTICATION
if (!isset($_SESSION['user']) && !isset($_GET['i'])) {
    header("location: ./login.php");
    exit();
}

$id = $_GET['id']; // Get the ID from the query string

$dues = new Admin($pdo, 'payment'); // Replace 'vehicle' with your actual table name

$results = $dues->read('serial_number', $id)[0];

// print_r($results);

// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Dues details
    $name = $_POST['name'];
    $yearGroup = $_POST['year_group'];
    $amount = $_POST['amount'];
    $phone = $_POST['phone'];
    $paymentMode = $_POST['payment_mode'];
    $paymentType = $_POST['payment_type'];
    $date = $_POST['date'];

    // Check if required fields are not empty
    if (!empty($name) && !empty($yearGroup) && !empty($amount) && !empty($paymentMode)) {

        // Insert dues information
        $dues = new Admin($pdo, 'payment'); // Replace 'dues' with your actual table name
        $duesInfo = [
            'name' => $name,
            'year_group' => $yearGroup,
            'amount' => $amount,
            'phone' => $phone,
            'payment_mode' => $paymentMode,
            'payment_type' => $paymentType,
            'date' => $date
        ];

        if ($dues->update($id, $duesInfo, 'serial_number')) {
            $alert =  "showAlert('success', 'Details updated successfully');";

            header("location: ./index.php?edited=1");
        } else {
            // Display an error message to the user using JavaScript
            $alert =  "showAlert('error', 'Unable to update dues record');";
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
    <title>Vehicle Project</title>

    <!-- add custom css -->
    <link rel="stylesheet" href="../css/custom.css" />
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
                <li class="nav-item">
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index.php" class="btn btn-primary text-light nav-link">Back to dashboard</a>
                </li>
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
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
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
                                        Edit Vehicles
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <h4 class="text-info fst-italic text-center">Edit Dues Information</h4>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="name">Full Name<span class="text-danger">* </span></label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Full Name" value="<?php echo isset($results['name']) ? $results['name'] : ''; ?>" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="year_group">Year Group<span class="text-danger">* </span></label>
                                                <input type="number" class="form-control" id="year_group" name="year_group" placeholder="Enter Year Group" value="<?php echo isset($results['year_group']) ? $results['year_group'] : ''; ?>" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="amount">Payment Amount<span class="text-danger">* </span></label>
                                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter Payment Amount" value="<?php echo isset($results['amount']) ? $results['amount'] : ''; ?>" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="payment_mode">Mode of Payment<span class="text-danger">* </span></label>
                                                <input type="text" class="form-control" id="payment_mode" name="payment_mode" placeholder="Enter Mode of Payment" value="<?php echo isset($results['payment_mode']) ? $results['payment_mode'] : ''; ?>" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label for="payment_type">Type of Payment</label>
                                                <select class="form-control" id="payment_type" name="payment_type">
                                                    <option value="full" <?php echo (isset($results['payment_type']) && $results['payment_type'] === 'full') ? 'selected' : ''; ?>>Full</option>
                                                    <option value="part" <?php echo (isset($results['payment_type']) && $results['payment_type'] === 'part') ? 'selected' : ''; ?>>Part</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="date">Date</label>
                                                <input type="date" class="form-control" id="date" name="date" placeholder="Enter Date" value="<?php echo isset($results['date']) ? $results['date'] : ''; ?>" required>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="phone">Phone Number<span class="text-danger">* </span></label>
                                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number" value="<?php echo isset($results['phone']) ? $results['phone'] : ''; ?>" required>
                                            </div>
                                        </div>



                                        <button type="submit" name="submit" class="px-4 btn mt-3 btn-success">Edit Dues Entry</button>
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
            APSU USA
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
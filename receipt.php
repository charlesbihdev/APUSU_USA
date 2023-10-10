<?php
session_start();

// AUTHENTICATION
if (!isset($_SESSION['user']) && !isset($_GET['id'])) {
  header("location: ./login.php");
  exit();
}
$id = $_GET['id'];
$alert = "";
require_once "./database/config.php";
require_once "./inc/auxilliaries.php";
require_once "./database/config.php";
// include autoloader
require __DIR__ . "/vendor/autoload.php";


use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options;
$options->setChroot(__DIR__);

// Create a new PDF document
$dompdf = new Dompdf($options);

// Set document properties
$dompdf->setPaper('letter', 'portrait');
$dompdf->set_option('isHtml5ParserEnabled', true);
$dompdf->set_option('isRemoteEnabled', true); // Enable loading remote images

//number of payment
$payment = new Admin($pdo, 'payment');
$fetchPayment = $payment->read("serial_number", $id)[0];
// print_r($fetchPayment);
// exit;

$id = $fetchPayment['serial_number'];
$date = $fetchPayment['date'];
$payment_type = $fetchPayment['payment_type'];
$year = $fetchPayment['year_group'];
$name = $fetchPayment['name'];
$payment_mode = $fetchPayment['payment_mode'];
$amount = $fetchPayment['amount'];
$phone = $fetchPayment['phone'];


$html = file_get_contents('./receipt.html');

// Replace placeholders with dynamic data
$html = str_replace('{date}', $date, $html);
$html = str_replace('{amount}', $amount, $html);
$html = str_replace('{id}', $id, $html);
$html = str_replace('{type}', $payment_type, $html);
$html = str_replace('{year}', $year, $html);
$html = str_replace('{name}', $name, $html);
$html = str_replace('{phone}', $phone, $html);
$html = str_replace('{mode}', $payment_mode, $html);

$dompdf->loadHtml($html);

// Render the PDF (first parameter is the output mode: 'stream' or 'download')
$dompdf->render();

// Output the PDF to the browser
$dompdf->stream('receipt.pdf', array('Attachment' => false));

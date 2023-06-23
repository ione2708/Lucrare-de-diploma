<?php
require __DIR__ . "/../vendor/autoload.php";
use Dompdf\Dompdf;
use Dompdf\Options;
$pdf = $_GET['denumire_pdf'];
//preluare date din baza de date
$con = mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
$query = mysqli_query($con, "SELECT document FROM rapoarte WHERE denumire_pdf='$pdf'");
$row = mysqli_fetch_row($query);

$continut_pdf = $row[0];
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=$pdf");

echo $continut_pdf;

<?php
session_start();


  if(isset($_GET['user']))
  {
  $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
  $proiect=$_GET['proiect'];
  $user=$_GET['user'];
  $query=mysqli_query($con,"select id_proiect, detalii, nivel_dificultate, status, data_start, data_end from taskuri where id_proiect='$proiect'");
while ($row = mysqli_fetch_row($query))
  {
    $contor=0;
    foreach ($row as $value)
    {
      $sir[$contor]=$value;
      $contor++;
    }
    $id_proiect=$row[0];
    $detalii=$row[1];
    $dificultate=$row[2];
    $status=$row[3];
    $data_start=$row[4];
    $data_end=$row[5]; 
  }
  
  $data=date("Y-m-d");
  
  $data_incepere=new DateTime($data_start);
  $data_finalizare = new DateTime($data_end);
  $data_finalizare->setTime(23,59);
  $data_incepere->setTime(0,0);
  //calcul timp de lucru efectiv
  $interval1=$data_incepere->diff($data_finalizare);
  $zile1=$interval1->days;
  $ore1 = $interval1->h + ($zile1 * 24);
  $minute1 = $interval1->i; 
   $query1=mysqli_query($con,"UPDATE taskuri SET responsabil='$user', status='In lucru', timp_alocat='$zile1' where id_proiect='$proiect'");
  if($query1)
  {
     header("Location:http://localhost/aplicatie/PaginaAngajat/ProiecteAsignate.php");
     ob_end_flush();
  }
 else
 {
  die(mysqli_error($con));
 }
}
mysqli_close($con); 

?>
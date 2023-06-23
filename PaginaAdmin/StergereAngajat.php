<?php
session_start();


  if(isset($_GET['cod_A']))
  {
  $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
  $cod_A=$_GET['cod_A'];
  $query=mysqli_query($con,"UPDATE angajati SET sters='1' where cod_A='$cod_A'");
  $query1=mysqli_query($con,"DELETE from conturi_logare where user='$cod_A'");
  if($query && $query1)
  {
     header("Location:http://localhost/aplicatie/PaginaAdmin/ListaAngajati.php");
     ob_end_flush();
  }
 else
 {
  die(mysqli_error($con));
 }
}
mysqli_close($con); 

?>
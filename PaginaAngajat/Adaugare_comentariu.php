<?php
session_start();
date_default_timezone_set('Europe/Bucharest');
$con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
$proiect=$_GET['proiect'];
$user=$_GET['user'];
$comentariu=$_POST['comentariu'];

    if(empty($comentariu))
    {
        echo "Completati campul pentru a introduce un comentariu";
    }
    else
    {
         $data=date('Y-m-d H:i:s');
    $comentariu_trimis=htmlspecialchars($comentariu, ENT_QUOTES);
   
    $adaugareComentariu=mysqli_query($con,"INSERT INTO comentarii_task (comentariu, data_comentariu,task,angajat) VALUES ('$comentariu_trimis','$data','$proiect', '$user')");
    header("Location:http://localhost/aplicatie/PaginaAngajat/VizualizareProiect.php?user=$user&proiect=$proiect");
    }
   
    
    exit();
?>
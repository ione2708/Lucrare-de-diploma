<?php

require __DIR__ . "/../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;
date_default_timezone_set('Europe/Bucharest');
//preluare date din formular

$luna = $_POST['luna'];
$luna_afisare=$luna-1;
$departament = $_POST['departament'];
//preluare date din baza de date
$con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
$query=mysqli_query($con,"select denumire from departamente where cod_D='$departament'");
$departament_denumire=mysqli_fetch_row($query);

$angajati=mysqli_query($con,"select count(*) from angajati where departament='$departament'");
$row=mysqli_fetch_row($angajati);
$nr_angajati=$row[0];

$query2=mysqli_query($con,"select count(*) from taskuri where departament='$departament' AND MONTH(data_completare)='$luna'");
$row2=mysqli_fetch_row($query2);
$nr_total_taskuri=$row2[0];

$tu=mysqli_query($con,"select count(*) from taskuri where nivel_dificultate=1 AND departament='$departament' AND MONTH(data_completare)='$luna'");
$tm=mysqli_query($con,"select count(*) from taskuri where nivel_dificultate=2 AND departament='$departament' AND MONTH(data_completare)='$luna'");
$td=mysqli_query($con,"select count(*) from taskuri where nivel_dificultate=3 AND departament='$departament' AND MONTH(data_completare)='$luna'");

$taskuri_usoare=mysqli_fetch_row($tu);
$taskuri_medii=mysqli_fetch_row($tm);
$taskuri_dificile=mysqli_fetch_row($td);

$query3=mysqli_query($con,"select count(*) from taskuri where departament='$departament' AND timp_rezolvare<=timp_alocat AND MONTH(data_completare)='$luna'");
$row3=mysqli_fetch_row($query3);
$la_timp=$row3[0];

$query4=mysqli_query($con,"select count(*) from taskuri where departament='$departament' AND timp_rezolvare>timp_alocat AND MONTH(data_completare)='$luna'");
$row4=mysqli_fetch_row($query4);
$intarziat=$row4[0];

$data = new DateTime();
$data_curenta=$data->format('Y-m-d H:i:s');
$luni = array(
    "Ianuarie",
    "Februarie",
    "Martie",
    "Aprilie",
    "Mai",
    "Iunie",
    "Iulie",
    "August",
    "Septembrie",
    "Octombrie",
    "Noiembrie",
    "Decembrie"
);


$date_t=mysqli_query($con,"select CONCAT (a.prenume, ' ', upper(a.nume)), a.cod_A, b.scor from angajati as a, scor_angajati as b WHERE a.cod_A NOT IN ('A12345') AND sters='0' and a.cod_A=b.angajat AND b.luna='$luna' ORDER BY a.nume");

//tabel
$tabel='<table style="width:100%;text-align: center;">';
$tabel .='<tr>';
$tabel .= '<th>Nume si prenume</th>';
$tabel .= '<th>Cod angajat</th>';
$tabel .= '<th>Nota angajat</th>';
// $tabel .= '<th>Taskuri rezolvate</th>';
$tabel .='</tr>';

while ($row = mysqli_fetch_row($date_t))
{
    $contor=0;
    $tabel .='<tr>';
    foreach ($row as $value)
    {
        $tabel .= '<tD>' . $value . '</tD>';
        $sir[$contor]=$value;
        $contor++;
    }
    $tabel .='</tr>';
}

$tabel .='</table>';
//setare optiuni pdf
$options = new Options;
$options->setChroot(__DIR__);
$options->setIsRemoteEnabled(true);

$dompdf = new Dompdf($options);

//definire pagina
$dompdf->setPaper("A4", "portrait");

$html = file_get_contents("template.html");

$html = str_replace(["{{ luna }}", "{{ departament }}"], [$luni[$luna_afisare], $departament_denumire[0]], $html);
$html = str_replace("{{luna_titlu}}", $luni[$luna_afisare], $html);
$html = str_replace("{{data_raport}}", $data_curenta, $html);
$html = str_replace("{{taskuri}}", $nr_total_taskuri, $html);
$html = str_replace("{{angajati}}", $nr_angajati, $html);
$html = str_replace("{{taskuri_la_timp}}", $la_timp, $html);
$html = str_replace("{{taskuri_intarziere}}", $intarziat, $html);
$html = str_replace("{{tabel}}", $tabel, $html);

$dompdf->loadHtml($html);

//creare pdf
$dompdf->render();

$dompdf->addInfo("Title", "Raport pentru luna $luni[$luna_afisare]"); 

//trimitere in browser
//$dompdf->stream("Raport_$luni[$luna_afisare]_$departament_denumire[0].pdf", ["Attachment" => 0]);

$document = $dompdf->output();
$continut=$con->real_escape_string($document); 
 
$verificare=mysqli_query($con,"SELECT count(*) from rapoarte where luna='$luna' AND departament='$departament'");
$nr=mysqli_fetch_row($verificare);
if($nr[0]==0)
{
    //salvare pdf in baza de date
    $inserare=mysqli_query($con,"INSERT INTO rapoarte (luna, departament, denumire_pdf, document) VALUES ('$luna','$departament','Raport_$departament_denumire[0]_$luni[$luna_afisare].pdf','$continut')")or die ("Inserarea nu a putut avea loc!". mysqli_error($con));
    header('Location:http://localhost/aplicatie/PaginaAdmin/Admin.php');
}
 else
 {
    header('Location:http://localhost/aplicatie/PaginaAdmin/Admin_1.php');
 }
//salvare local
// file_put_contents("Raport_$luni[$luna]_$departament.pdf", $output);
//salvare pdf in baza de date
?>
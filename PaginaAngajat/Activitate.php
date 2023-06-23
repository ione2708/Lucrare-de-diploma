<?php
session_start();
if (!isset($_SESSION['parolaAngajat'])){
header("Location: Login.php");
}
else{

  $user=$_SESSION['userCurent'];
  $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
  $luna_curenta=date("m");
  
  //definire indicatori
  $taskuri_rezolvate=0.3;
  $dificultate=0.5;
  $timp_rezolvare=0.2;

  $departament=mysqli_query($con,"select departament from angajati where cod_A='$user'");
  $dep=mysqli_fetch_row($departament);

  $angajati=mysqli_query($con,"select count(*) from angajati where departament='$dep[0]'");
  $row=mysqli_fetch_row($angajati);
  $nr_angajati=$row[0];

  $performanta=array();
  $la_timp=array();
  $intarziat=array();
  $la_timp_procent=array();
  $intarziat_procent=array();
  $nr_taskuri_angajat=array();
  $nr_total_taskuri=array();
  $taskuri_medii=array();
  $taskuri_usoare=array();
  $taskuri_grele=array();
  $suma_dificultati=array();
  for ($i = 1; $i <= 12; $i++)
  {

    
  //extragere date pentru angajat
  $query1=mysqli_query($con,"select count(*) from taskuri where status IN (2,3) AND responsabil='$user' AND MONTH(data_completare)='$i'");
  $row1=mysqli_fetch_row($query1);
  $nr_taskuri_angajat[$i]=$row1[0];

  $query2=mysqli_query($con,"select count(*) from taskuri where status IN (2,3) AND MONTH(data_completare)='$i' AND departament='$dep[0]'");
  $row2=mysqli_fetch_row($query2);
  $nr_total_taskuri[$i]=$row2[0];

  $query31=mysqli_query($con,"select count(*) from taskuri where nivel_dificultate=1 AND status IN (2,3)  AND MONTH(data_completare)='$i' AND responsabil='$user'");
  $row31=mysqli_fetch_row($query31);

  $query32=mysqli_query($con,"select count(*) from taskuri where nivel_dificultate=2 AND status IN (2,3)  AND MONTH(data_completare)='$i' AND responsabil='$user'");
  $row32=mysqli_fetch_row($query32);

  $query33=mysqli_query($con,"select count(*) from taskuri where nivel_dificultate=3 AND status IN (2,3)  AND MONTH(data_completare)='$i' AND responsabil='$user'");
  $row33=mysqli_fetch_row($query33);

  $taskuri_usoare[$i]=$row31[0];
  $taskuri_medii[$i]=$row32[0];
  $taskuri_grele[$i]=$row33[0];

  $suma_dificultati[$i]=$taskuri_usoare[$i]*2+$taskuri_medii[$i]*3+$taskuri_grele[$i]*5;

  

  //extragere date pentru calcul timp de rezolvare
  $query4=mysqli_query($con,"select SUM(timp_rezolvare) from taskuri where status IN (2,3) AND MONTH(data_completare)='$i' AND responsabil='$user'");
  $row4=mysqli_fetch_row($query4);
  $timp_total_rezolvare=$row4[0];

  $query5=mysqli_query($con,"select SUM(timp_alocat) from taskuri where status IN (2,3) AND MONTH(data_completare)='$i' AND responsabil='$user'");
  $row5=mysqli_fetch_row($query5);
  $timp_maxim_alocat=$row5[0];
  if($nr_taskuri_angajat[$i]==0 || $nr_total_taskuri[$i]==0 || $nr_angajati==0)
     $performanta[$i]=0;
  else
  {
    //calcul nota pe taskuri
    $nr_taskuri_per_angajat=$nr_total_taskuri[$i]/$nr_angajati;
    if($nr_taskuri_angajat[$i]<$nr_taskuri_per_angajat)
      $nota_taskuri=4;
      elseif($nr_taskuri_angajat[$i]>=$nr_taskuri_per_angajat && $nr_taskuri_angajat[$i] <($nr_total_taskuri[$i]/2))
        $nota_taskuri=7;
        elseif($nr_taskuri_angajat[$i] >=($nr_total_taskuri[$i]/2))
          $nota_taskuri=10;
    //calcul nota pe dificultatea taskurilor
    $media_dificultatilor=$suma_dificultati[$i]/$nr_taskuri_angajat[$i];
    if($media_dificultatilor==3)
      $nota_dificultate=4;
      elseif($media_dificultatilor>3 && $media_dificultatilor<4)
        $nota_dificultate=7;
        elseif($media_dificultatilor>=4)
          $nota_dificultate=10;

    //calcul nota pentru timpul de rezolvare
    if($timp_total_rezolvare <= $timp_maxim_alocat)
      $nota_timp=10;
      else
      {
        $diferenta=$timp_total_rezolvare-$timp_maxim_alocat;
        if(10-$diferenta <4)
          $nota_timp=4;
          else
          $nota_timp=10-$diferenta;
      }
  
  $performanta[$i]=$nota_taskuri*$taskuri_rezolvate+$nota_dificultate*$dificultate+$nota_timp*$timp_rezolvare;
  $verificare=mysqli_query($con,"select count(*) from scor_angajati where angajat='$user' and luna='$i'");
  $v=mysqli_fetch_row($verificare);
  if($performanta[$i]!=0)
  {
    if($v[0]==0)
     $inserare=mysqli_query($con,"INSERT INTO scor_angajati (angajat,luna,scor) VALUES ('$user','$i','$performanta[$i]')");
    else
    $update=mysqli_query($con,"UPDATE scor_angajati SET scor='$performanta[$i]' WHERE angajat='$user' AND luna='$i'");
  }
  
    
  $query6=mysqli_query($con,"select count(*) from taskuri where status=2 AND MONTH(data_completare)='$i' AND responsabil='$user'");
  $query7=mysqli_query($con,"select count(*) from taskuri where status=3 AND MONTH(data_completare)='$i' AND responsabil='$user'");

  $a=mysqli_fetch_row($query6);
  $b=mysqli_fetch_row($query7);
  
  $la_timp[$i]=$a[0];
  $intarziat[$i]=$b[0];
  
  $la_timp_procent[$i]=($a[0]*100)/$nr_taskuri_angajat[$i];
  $intarziat_procent[$i]=($b[0]*100)/$nr_taskuri_angajat[$i];
}


}

  // <?php echo number_format($performanta,2) . "%";
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

  ?>
  <html>
<head>
<title>Activitate</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="test.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawChart1);
      
      function drawChart() {
        <?php for ($i = 1; $i <= 12; $i++) { ?>
        var data<?php echo $i; ?> = google.visualization.arrayToDataTable([
          ['Task', 'Status'],
          ['Taskuri ușoare',<?php echo $taskuri_usoare[$i]; ?> ],
          ['Taskuri medii',<?php echo $taskuri_medii[$i]; ?>],
          ['Taskuri dificile',<?php echo $taskuri_grele[$i]; ?>]
        ]);

        var options = {
          title: 'Dificultatea taskurilor rezolvate',
          pieSliceText: 'value',
        };
       
        var chart<?php echo $i; ?> = new google.visualization.PieChart(document.getElementById('piechart<?php echo $i; ?>'));

        chart<?php echo $i; ?>.draw(data<?php echo $i; ?>, options);
        <?php } ?>
      }
      function drawChart1() {
        var data = google.visualization.arrayToDataTable([
          ['Luna', 'Taskuri rezolvate'],
          ['Ianuarie', <?php echo $nr_taskuri_angajat[1];?>],
          ['Februarie',  <?php echo $nr_taskuri_angajat[2];?>],
          ['Martie',  <?php echo $nr_taskuri_angajat[3];?>],
          ['Aprilie',  <?php echo $nr_taskuri_angajat[4];?>],
          ['Mai',  <?php echo $nr_taskuri_angajat[5];?>],
          ['Iunie',  <?php echo $nr_taskuri_angajat[6];?>],
          ['Iulie',  <?php echo $nr_taskuri_angajat[7];?>],
          ['August',  <?php echo $nr_taskuri_angajat[8];?>],
          ['Septembrie', <?php echo $nr_taskuri_angajat[9];?>],
          ['Octombrie',  <?php echo $nr_taskuri_angajat[10];?>],
          ['Noiembrie',  <?php echo $nr_taskuri_angajat[11];?>],
          ['Decembrie',  <?php echo $nr_taskuri_angajat[12];?>]
        ]);

        var options = {
          title: 'Evoluția task-urilor anual',
          curveType: 'function',
          width:550,
        
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
</head>

<body class="sb-nav-fixed">
<!-- Bara de navigare -->
<nav class="sb-topnav navbar navbar-expand">
  <a class="navbar-brand ps-3" href="#">Virtual desk</a>
  <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
          <li><a class="dropdown-item" href="Logout.php">Logout</a></li>
        </ul>
    </li>
  </ul>
</nav>
<!-- Meniu stanga -->
<div id="layoutSidenav">
  <div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
      <div class="sb-sidenav-menu">
        <div class="nav ">
        <div class="sb-sidenav-menu-heading">Acțiuni</div>
          <a class="nav-link" href="Raport_personal.php" aria-selected="true">
            Profilul meu
          </a>
          <a class="nav-link" href="Proiecte.php">
            Taskuri
          </a>
          <a class="nav-link" href="ProiecteAsignate.php">
            Taskurile mele
          </a>
            <a class="nav-link active" href="Activitate.php">
              Activitate
            </a>
        </div>
      </div>
      <div class="sb-sidenav-footer">
      <div class="small">Sunteți logat în contul angajat:</div>
        <?php echo $_SESSION['userCurent']?>
    </div>
    </nav> 
  </div>
  <div id="layoutSidenav_content">
    <!-- Aici apar datele paginii si actiunile -->
    <main>
      <div class="container-fluid px-4">
        <h3 class="mt-4" style="text-align:left">Rezumatul activității</h3>
        <div class="card mb-4">
        <div class="grafice">

         
            
            <div class="card text-center" style="width: 30rem;height:32rem;border-radius:10px;">
               <?php for ($i = 1; $i <= 12; $i++) {
                
                ?>
                <div class="mySlides">
                  <h5 class="card-title">Luna <?php echo $luni[$i-1]?></h5>
                  <div class="card" style="width: 28rem;height:12rem;border-radius:10px;margin-left:15px;text-align:left;">
                  <?php if($nr_total_taskuri[$i]!=0 && $nr_taskuri_angajat[$i]!=0)
                {?>
                  <p>Ai obținut nota <?php echo '<b>'. $performanta[$i] . '</b>';?> aferentă scorului tău de angajat de <?php echo '<b>' . $performanta[$i]*10 . "%" . '</b>';?>. <br>
                  Total taskuri rezolvate: <span class="badge rounded-pill badge-padding-x:5px badge-padding-y:5px" style="color:black;background-color: #d4c1ff;font-size: 15px;"><?php echo $nr_taskuri_angajat[$i];?></span>din
                  <span class="badge rounded-pill badge-padding-x:5px badge-padding-y:5px" style="color:black;background-color: #f5ff6f;font-size: 15px;"><?php echo $nr_total_taskuri[$i];?></span> alocate departamentului tau.
                  <p>Taskuri rezolvate la timp: <span class="badge rounded-pill badge-padding-x:5px badge-padding-y:5px" style="color:black;background-color: #6fffc3;font-size: 15px;"><?php echo $la_timp[$i];?></span> <?php echo " (" . number_format($la_timp_procent[$i],2) . "%)"?></p>
                  <p>Taskuri rezolvate cu întârziere: <span class="badge rounded-pill badge-padding-x:5px badge-padding-y:5px" style="color:black;background-color: #ffee6f;font-size: 15px;"><?php echo $intarziat[$i];?></span><?php echo " (" . number_format($intarziat_procent[$i],2) . "%)"?></p>     
                  
                  <?php } 
                  else
                  {?>
                  <p>Nu există date momentan.</p><?php }?>
                </div>
               <div class="card text-center" style="width: 28rem;height:15rem;border-radius:10px;margin-top:10px;margin-left:15px;">
                  <div id="piechart<?php echo $i; ?>" style="width: 400px; height: 100px;text-align:center"></div>
                </div>
                </div>
                
                <?php }?>
                <a class="prev" onclick="plusSlides(-1)">❮</a>
                <a class="next" onclick="plusSlides(1)">❯</a>
              </div>
            
          

          <div class="card text-center grafic" style="width: 40rem;height:32rem;border-radius:10px;">
          <div id="curve_chart" style="width: 630px; height: 400px"></div>
            
          </div>
          
        </div>
        </div>
      </div>
    </main>
    <footer class="py-4 mt-auto">
      <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
        <div class="text-muted">Copyright &copy; Ștefănescu Georgiana-Ionela 2023</div>
        </div>
      </div>
    </footer>
  </div>
</div>
<script>
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
</script>
</body>
</html>

<?php
}
?>
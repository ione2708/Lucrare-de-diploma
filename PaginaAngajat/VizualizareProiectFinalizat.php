<?php
session_start();
$con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
$proiect=$_GET['proiect'];
$query=mysqli_query($con,"select id_proiect, detalii, nivel_dificultate, status, data_start, data_end, data_completare, timp_alocat, timp_rezolvare from taskuri where id_proiect='$proiect'");
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
    $data_completare=$row[6];  
    $timp_alocat=$row[7];
    $timp_rezolvare=$row[8];    
  }
  
  $data=date("Y-m-d");
  
  $data_incepere=new DateTime($data_start);
  $data_finalizare = new DateTime($data_end);
  $data_rezolvare= new DateTime($data_completare);
  $data_finalizare->setTime(23,59);
  $data_incepere->setTime(0,0);

  //calcul timp de lucru efectiv
  $interval1=$data_incepere->diff($data_finalizare);
  $zile1=$interval1->days;
  $ore1 = $interval1->h + ($zile1 * 24);
  $minute1 = $interval1->i; 

  //calcul timp de rezolvare
  if ($status=='Finalizat')
    {
        $interval=$data_rezolvare->diff($data_finalizare);
        $zile=$interval->days;
        $ore = $interval->h + ($zile * 24);
        $minute = $interval->i; 
    }
    elseif($status=='Finalizat cu intarziere')
    {
        $interval=$data_finalizare->diff($data_rezolvare);
        $zile=$interval->days;
        $ore = $interval->h + ($zile * 24);
        $minute = $interval->i;
    }
  
    //calcul performanta angajat
  $user=$_SESSION['userCurent'];
  //comentarii
  $comentarii=mysqli_query($con,"SELECT comentariu, data_comentariu FROM comentarii_task WHERE task='$proiect' ORDER BY data_comentariu DESC");
  $total_comentarii=mysqli_query($con,"SELECT count(*) as total FROM comentarii_task WHERE task='$proiect'");
  $total=mysqli_fetch_row($total_comentarii);
  ?>
  <html>
<head>
<title>Vizualizare proiect finalizat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <link rel="stylesheet" href="test.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
  
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
          <a class="nav-link active" href="ProiecteAsignate.php">
            Taskurile mele
          </a>
            <a class="nav-link" href="Activitate.php">
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
        <h4 class="mt-4" style="text-align:left"><a href="ProiecteAsignate.php"><i class="fa-solid fa-arrow-left" style="color: #478bff;"></i></a> Taskul <?php echo$_GET['proiect']; ?> </h4>
        <div class="card mb-4">
          <div class="card-body">
            <div class="container-detalii">
                <div class="descriere"> 
                    <div class="card text-center" style="width: 25rem;height:30rem;border-radius:10px">
                        <h5>Specificații task</h5>
                        <div class="row" style="margin-left: 10px;margin-right: 10px;margin-bottom:5px;">
                          <div class="col">
                            <label for="id_p">Id task:</label>
                            <input type="text" class="form-control" value=<?php echo $id_proiect ?> name="id_p" disabled readonly>
                          </div>
                          <div class="col">
                            <label for="dificultate">Nivel de dificultate:</label>
                            <input type="text" class="form-control" value=<?php echo $dificultate; ?> name="dificultate" disabled readonly>
                          </div>
                        </div>
                        <div class="row" style="margin-left: 10px;margin-right: 10px;margin-bottom:5px;">
                          <label for="detalii">Descriere:</label>
                          <textarea disabled style="border-color: #361097;" rows="3" cols="5"><?php echo htmlspecialchars($detalii, ENT_QUOTES); ?></textarea> 
                        </div>
                        <div class="row" style="margin-left: 10px;margin-right: 10px;margin-bottom:5px;">
                          <label for="status">Status:</label>
                          <input type="text" class="form-control" value="<?php echo htmlspecialchars($status, ENT_QUOTES); ?>" name="status" disabled readonly>
                        </div>
                        <div class="row" style="margin-left: 10px;margin-right: 10px;margin-bottom:5px;">
                          <label for="timp_de_lucru">Timp de lucru alocat:</label>
                          <input type="text" class="form-control" value="<?php 
                          if($ore1>=24)
                          echo $timp_alocat . " zile";
                          else
                          echo $ore1 . "h" . $minute . " minute"; ?>" name="timp_lucru" disabled readonly>
                        </div>
                        <div class="row" style="margin-left: 10px;margin-right: 10px;margin-bottom:5px;">
                          <label for="timp_de_rezolvare">Timpul în care s-a rezolvat:</label>
                          <input type="text" class="form-control" value="<?php 
                          if($status=='Finalizat')
                          {
                            if($ore>=24)
                          echo $timp_rezolvare . " zile";
                          else
                          echo $ore . "h " . $minute . " minute"; 
                          }
                          elseif($status=='Finalizat cu intarziere')
                          {
                            if($ore>=24)
                          echo "S-a depasit timpul de rezolvare cu " . $zile . " zile";
                          else
                          echo "S-a depasit timpul de rezolvare cu " . $ore . "h" . $minute . " minute"; 
                          }
                          ?>" name="timp_rezolvare" disabled readonly>
                        </div>
                        <div class="row" style="margin-left: 10px;margin-right: 10px;margin-bottom:5px;">
                          <label for="timp_ramas">Data finalizării</label>
                          <input type="text" class="form-control" value="<?php 
                              echo $data_completare;
                              ?>" name="timp_ramas" disabled readonly>
                        </div>
                    </div>  
                </div>
                <div class="actiuni">
                    <div class="card" style="width: 45rem;height:30rem;border-radius:10px">
                    <div class="status">
                      <!-- Comment form -->
                      <center><h5>Istoric comentarii</h5></center>
                      <h7>Total comentarii: <?php echo $total[0];?></h7>
                      <div class="comentarii" style="width: 39rem;height:25rem;border-radius:10px;overflow:auto">
                      <?php

                        while($lista = mysqli_fetch_row($comentarii))
                        {
                          $contor=0;
                          echo " <div class='card' style='width:38rem; margin-top:10px'>";
                          echo " <div class='card-title' style='margin-bottom: -0.5rem;'>";
                          foreach ($lista as $value)
                          {
                            $sir[$contor]=$value;
                            $contor++;
                          }
                          echo "<b>$user</b>";
                          echo "<h6 class='card-subtitle mb-2 text-muted'>$sir[1]</h6>";
                          echo "<p class=class='card-body'> $sir[0] </p>"; 
                          echo "</div>";
                          echo "</div>";
                          
                        }
                      ?>
                      </div>
                    </div>
                </div>
                </div>
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
</body>
</html>
<script language="javascript">
function askme(){
var r=confirm ('Marcati taskul ca finalizat?');
if(r==true){
return href;
}
return false;
}
</script>
<?php

?>
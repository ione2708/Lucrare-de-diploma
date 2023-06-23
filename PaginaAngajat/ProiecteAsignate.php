<?php
session_start();
if (!isset($_SESSION['parolaAngajat'])){
header("Location: Login.php");
}
else{
  ?>
  <html>
<head>
<title>Taskurile mele</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <link rel="stylesheet" href="test.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
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
        <h3 class="mt-4" style="text-align:left">Taskurile mele</h3>
        <div class="card mb-4">
          <div class="card-body">
          <form method="POST" action="ProiecteAsignate.php">
          <div class="row g-2" style="margin-left: 25px;">
        
          <div class="col-auto">
            <input class="btn btn-sm" style="background-color: #cbf1cc" type="SUBMIT" value="În lucru" name="lucru">
          </div>
          <div class="col-auto">
             <input class="btn btn-sm" style="background-color: #b4c3f2" type="SUBMIT" value="Toate taskurile" name="toate"> 
            </div>
          </div>
        </form>
          <?php
        //conexiune baza de date
        $user=$_SESSION['userCurent'];
        $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
        if(!isset($_POST['toate']))
        {
            $query=mysqli_query($con,"select id_proiect, descriere_scurta, nivel_dificultate, CONCAT (data_start, '-' ,data_end), status from taskuri where responsabil='$user' AND status='In lucru'");
            $nr=@mysqli_num_rows($query);
        if($nr==0)
        {
          die ("Nu aveți niciun task luat în lucru");
        }
        else
        {
          //capul de tabel
          ?>
          <html> 
          <ul class="responsive-table">
          <li class="table-header" style="background-color: #cbf1cc">
            <div class="col">Nr. task</div>
            <div class="col">Descriere scurtă</div>
            <div class="col">Nivel de dificultate</div>
            <div class="col">Perioada de lucru</div>
            <!-- <div class="col">Timp de lucru</div> -->
            <div class="col">Status</div>
            <div class="col">Acțiuni</div>
            
        </li>
              <?php
          while ($row = mysqli_fetch_row($query))
          {
            $contor=0;
            echo " <li class='table-row'>";
            foreach ($row as $value)
            {
              echo "<div class='col'>$value</div>";
              $sir[$contor]=$value;
              $contor++;
            }
            //preluare parametri pentru asignare
            $nrp=$sir[0];
            $descriere=$sir[1];
            $dificultate=$sir[2];
            $data_inceperii=$sir[3];
            $status=$sir[4];
            $user=$_SESSION['userCurent'];
        
             echo "<div class='col'>
             <button class='btn'>
            <a href='VizualizareProiect.php?proiect=$nrp&user=$user'><i class='fa-solid fa-eye' style='color: #3d79e1;' title='Vezi detalii task'></i></a>
            </button>
            </div>"; 
            echo "</li>";
          }
        }
        }
        else
        {
            $query=mysqli_query($con,"select id_proiect, descriere_scurta, nivel_dificultate, CONCAT (data_start, '-' ,data_end), status, data_completare from taskuri where responsabil='$user'");
        
        $nr=@mysqli_num_rows($query);
        if($nr==0)
        {
          die ("Nu aveți niciun task.");
        }
        else
        {
          //capul de tabel
          ?>
          <html> 
          <ul class="responsive-table">
          <li class="table-header">
            <div class="col">Nr. task</div>
            <div class="col">Descriere scurta</div>
            <div class="col">Nivel de dificultate</div>
            <div class="col">Perioada de lucru</div>
            <!-- <div class="col">Timp de lucru</div> -->
            <div class="col">Status</div>
            <div class="col">Data finalizării</div>
            <div class="col">Acțiuni</div>
        </li>
              <?php
          while ($row = mysqli_fetch_row($query))
          {
            $contor=0;
            echo " <li class='table-row'>";
            foreach ($row as $value)
            {
              echo "<div class='col'>$value</div>";
              $sir[$contor]=$value;
              $contor++;
            }
            //preluare parametri pentru asignare
            $nrp=$sir[0];
            $descriere=$sir[1];
            $dificultate=$sir[2];
            $data_inceperii=$sir[3];
            $status=$sir[4];
            $data_f=$sir[5];
            $user=$_SESSION['userCurent'];    
            if($data_f==null)
            {
              echo "<div class='col'>
             <button class='btn'>
            <a href='VizualizareProiect.php?proiect=$nrp&user=$user'><i class='fa-solid fa-eye' style='color: #3d79e1;' title='Vezi detalii task'></i></a>
            </button>
            </div>";     
            }
            else
            {
              echo "<div class='col'>
             <button class='btn'>
            <a href='VizualizareProiectFinalizat.php?proiect=$nrp&user=$user'><i class='fa-solid fa-eye' style='color: #3d79e1;' title='Vezi detalii task'></i></a>
            </button>
            </div>";  
            }
               
             echo "</li>";
          }
        }
    }
        ?> 
    </ul>
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
}
?>
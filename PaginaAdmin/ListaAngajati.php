<?php
session_start();
if (!isset($_SESSION['parolaADMIN']))
{
    header("Location: Login.php");
}
else
{
  
    ?>
    <html>
<head>
  <title>Lista angajaților</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="select.css">
    <link rel="stylesheet" href="test.css">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script> -->
   
      
</head>

<body class="sb-nav-fixed">
  <!-- Bara de navigare -->
  <nav class="sb-topnav navbar navbar-expand">
    <a class="navbar-brand ps-3" href="#">Virtual desk</a>
    <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw "></i></a>
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
            <a class="nav-link" href="Admin.php" aria-selected="true">
              Raport activitate
            </a>
            <a class="nav-link" href="AdaugareAngajat.php">
              Înregistrare angajat nou
            </a>
            <a class="nav-link active" href="ListaAngajati.php">
              Lista angajaților
            </a>
            <a class="nav-link" href="AdaugareTask.php">
              Creare task
            </a>
          </div>
        </div>
        <div class="sb-sidenav-footer position">
          <div class="small">Sunteți logat în contul admin:</div>
          <?php echo $_SESSION['userCurent']?>
      </div>
      </nav> 
    </div>
    <div id="layoutSidenav_content">
      <!-- Aici apar datele paginii si actiunile -->
      <main>
        <div class="container-fluid px-4">
        <h3 class="mt-4">Lista angajaților</h3>
        
        <div class="card mb-4 border border-info">
        <!-- <div class="card-header">
          <i class="fas fa-table me-1"></i>
         Lista tuturor angajatilor
        </div> -->
        <div class="card-body">
        <form method="POST" action="ListaAngajati.php">
          <div class="row g-2" style="margin-left: 25px;">
          <div class="col-auto">
            <label for="cautare" class="form-label">Cod angajat:</label>
          </div>
          <div class="col-auto">
            <input type="text" class="form-control form-control-sm" name="cod_form" style="width:100px;">
          </div>
          <div class="col-auto">
            <input class="btn btn-sm" style="background-color: #b4c3f2" type="SUBMIT" value="Caută" name="cauta">
          </div>
          <div class="col-auto">
             <input class="btn btn-sm" style="background-color: #b4c3f2" type="SUBMIT" value="Revenire lista" name="lista"> 
            </div>
          </div>
        </form>
       <div class="tabel" style="overflow:auto;width:70rem;height:30rem">
        <?php
        //conexiune baza de date
        $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
        if(isset($_POST['cauta']))
        {$cod=$_POST['cod_form'];
          $query=mysqli_query($con,"select CONCAT (a.prenume, ' ', upper(a.nume)), a.email, a.cod_A from angajati as a WHERE cod_A NOT IN ('A12345') AND sters='0' AND cod_A='$cod' ORDER BY a.nume");
        }
        else
        {
          $query=mysqli_query($con,"select CONCAT (a.prenume, ' ', upper(a.nume)), a.email, a.cod_A from angajati as a WHERE 
          cod_A NOT IN ('A12345') AND sters='0' ORDER BY a.nume");
        }
        $nr=@mysqli_num_rows($query);
        if($nr==0)
        {
          die ("Nu există nicio înregistrare");
        }
        else
        {
          ?>
          <html> 
          <ul class="responsive-table">
          <li class="table-header">
            <div class="col">Angajat</div>
            <div class="col">Email</div>
            <div class="col">Cod angajat</div>
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
            //preluare parametri pentru modificare/stergere
            $nume=$sir[0];
            $email=$sir[1];
            $cod_A=$sir[2];
             echo "<div class='col'><button class='btn'><a href='ModificareDate.php?cod_A=$cod_A'><i class='fa-regular fa-pen-to-square' 
             style='color: #005eff' title='Modifică date'></i></a></button>
             <button class='btn'><a href='StergereAngajat.php?cod_A=$cod_A' onclick = 'return askme();'><i class='fa-regular fa-square-minus' 
             style='color: #e60000' title='Șterge angajat'></i></a></button></div>";
             echo "</li>";
          }
        }
        ?> 
    </ul></div>
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
    var r=confirm ('Sunteți sigur că doriți să ștergeți înregistrarea selectată?');
    if(r==true){
    return href;
    }
    return false;
    }
</script>
<?php
//mysqli_close($con); 
}
?>
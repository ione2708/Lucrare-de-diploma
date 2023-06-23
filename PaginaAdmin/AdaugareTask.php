<?php
session_start();
if (!isset($_SESSION['parolaADMIN']))
{
    header("Location: Login.php");
}
else
{
  if (!isset($_POST['submit_form'])){
    ?>
    <html>
<head>
  <title>Creare task</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="select.css">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
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
            <a class="nav-link" href="ListaAngajati.php">
              Lista angajaților
            </a>
            <a class="nav-link active" href="AdaugareTask.php">
              Creare task
            </a>
          </div>
        </div>
        <div class="sb-sidenav-footer">
        <div class="small">Sunteți logat în contul admin:</div>
          <?php echo $_SESSION['userCurent']?>
      </div>
      </nav> 
    </div>
    <div id="layoutSidenav_content">
      <!-- Aici apar datele paginii si actiunile -->
      <main>
        <div class="container-fluid px-4">
          <h3 class="mt-4">Creare task</h3>
            
          <div class="card mb-4 border border-info">
            <div class="card-body">
              <div class="formular">
              <form method="post"  action="">
                <div class="card-body header">
                <center><b>Detalii task</b></center>
                </div>
                <div class="row form-group was-validated">
                        <div class="col md-4">
                          <label for="departament">Departament</label>
                          <select class="custom-select" name="departament" required>
                          <option value="">Alege departament</option>
                          <?php
                              $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
                              $query_a=mysqli_query($con,"select cod_D, denumire from departamente where cod_D NOT IN('999')");
                              while ($row = mysqli_fetch_assoc($query_a)) {
                                echo '<option value="' . $row['cod_D'] . '">' . $row['denumire'] . '</option>';
                              }
                              mysqli_close($con);
                              ?>
                            </select>
                        </div>
                        <div class="col md-4">
                          <label for="nivel">Nivel de dificultate</label>
                          <select class="custom-select" name="dificultate" required>
                                <option value="">Alege nivelul de dificultate</option>
                                <option value="1">1-Usor</option>
                                <option value="2">2-Mediu</option>
                                <option value="3">3-Dificil</option>
                          </select>
                        </div>
                    </div>
                <div class="row form-group was-validated">
                    <div class="col md-4">
                    <label for="dataI">Data începerii</label>
                    <input type="date" class="form-control" placeholder="" name="dataI" required>
                    </div>
                    <div class="col md-4">
                        <label for="dataF">Data finalizării</label>
                        <input type="date" class="form-control" placeholder="" name="dataF" required>
                    </div>
                </div>
                <div class="row form-group was-validated">
                    <label for="descriere_scurta">Descriere scurtă</label>
                    <textarea id="descreiereS" name="descriere_scurta" rows="2" cols="40" required></textarea>
                </div>
                <div class="row form-group was-validated">
                        <label for="detalii">Descriere detaliată</label>
                        <textarea id="detalii" name="detalii" rows="7" cols="50" required></textarea>
                </div>            
                <center><input class="btn btn-outline-primary w-25 mt-2" type="submit" name="submit_form" value="Adăugare"></center>
              </form>
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

<?php

}
else {
//conexiune baza de date
$con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
//preluare date personale din form
$departament=$_POST['departament'];
$dificultate=$_POST['dificultate'];
$dataI=$_POST['dataI'];
$data_formatata=date("Y-m-d",strtotime($dataI));
$dataF=$_POST['dataF'];
$data_formatata2=date("Y-m-d",strtotime($dataF));
$descriereS=$_POST['descriere_scurta'];
$detalii=$_POST['detalii'];
$data_curenta=date("Y-m-d");
//verificare data de inceput sa fie mai mare ca data curenta si data de final ca data de inceput
if($data_formatata >= $data_curenta && $data_formatata2 > $data_formatata )
{
  //inserare date
  $query=mysqli_query($con,"INSERT INTO taskuri (departament, nivel_dificultate, descriere_scurta, detalii, data_start, data_end) VALUES ($departament, $dificultate, '$descriereS', '$detalii', '$data_formatata', '$data_formatata2')");
  header('Location:http://localhost/aplicatie/PaginaAdmin/Admin.php');
  $_SESSION['cod_A']=$cod_A;
}
else{
?>
 <html>
<head>
  <title>Adaugare angajat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="select.css">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
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
            <div class="sb-sidenav-menu-heading">Actiuni</div>
            <a class="nav-link" href="Admin.php" aria-selected="true">
              Raport activitate
            </a>
            <a class="nav-link" href="AdaugareAngajat.php">
              Inregistrare angajat nou
            </a>
            <a class="nav-link" href="ListaAngajati.php">
              Lista angajati
            </a>
            <a class="nav-link active" href="AdaugareTask.php">
              Creare task
            </a>
          </div>
        </div>
        <div class="sb-sidenav-footer">
          <div class="small">Sunteti logat in contul admin:</div>
          <?php echo $_SESSION['userCurent']?>
      </div>
      </nav> 
    </div>
    <div id="layoutSidenav_content">
      <!-- Aici apar datele paginii si actiunile -->
      <main>
        <div class="container-fluid px-4">
          <h3 class="mt-4">Creare task</h3>
          <div class=" alert alert-danger alert-dismissible fade show" role="alert">
              Nu puteti introduce o data care a trecut. Reintroduceti data de start si de final!
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <div class="card mb-4 border border-info">
            <div class="card-body">
              <div class="formular">
              <form method="post"  action="">
                <div class="card-body header">
                <center><b>Detalii task</b></center>
                </div>
                <div class="row form-group was-validated">
                        <div class="col md-4">
                          <label for="departament">Departament</label>
                          <select class="custom-select" name="departament" required>
                          <option value="">Alege departament</option>
                          <?php
                              $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
                              $query_a=mysqli_query($con,"select cod_D, denumire from departamente where cod_D NOT IN('999')");
                              while ($row = mysqli_fetch_assoc($query_a)) {
                                echo '<option value="' . $row['cod_D'] . '">' . $row['denumire'] . '</option>';
                              }
                              mysqli_close($con);
                              ?>
                            </select>
                        </div>
                        <div class="col md-4">
                          <label for="nivel">Nivel de dificultate</label>
                          <select class="custom-select" name="dificultate" required>
                                <option value="">Alege nivelul de dificultate</option>
                                <option value="1">1-Usor</option>
                                <option value="2">2-Mediu</option>
                                <option value="3">3-Dificil</option>
                          </select>
                        </div>
                    </div>
                <div class="row form-group was-validated">
                    <div class="col md-4">
                    <label for="dataI">Data inceperii</label>
                    <input type="date" class="form-control" placeholder="" name="dataI" required>
                    </div>
                    <div class="col md-4">
                        <label for="dataF">Data finalizarii</label>
                        <input type="date" class="form-control" placeholder="" name="dataF" required>
                    </div>
                </div>
                <div class="row form-group was-validated">
                    <label for="descriere_scurta">Descriere scurta</label>
                    <textarea id="descreiereS" name="descriere_scurta" rows="2" cols="40" required></textarea>
                </div>
                <div class="row form-group was-validated">
                        <label for="detalii">Descriere detaliata</label>
                        <textarea id="detalii" name="detalii" rows="7" cols="50" required></textarea>
                </div>            
                <center><input class="btn btn-outline-primary w-25 mt-2" type="submit" name="submit_form" value="Adaugare"></center>
              </form>
            </div>
            </div>
          </div>
        </div>
      </main>
      <footer class="py-4 mt-auto">
        <div class="container-fluid px-4">
          <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Stefanescu Ionela 2023</div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</body>
</html>
<?php
}

}
// mysqli_close($con);
}

?>
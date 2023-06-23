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
  <title>Generare parolă</title>
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
            <a class="nav-link active" href="AdaugareAngajat.php">
              Înregistrare angajat nou
            </a>
            <a class="nav-link" href="ListaAngajati.php">
              Lista angajaților
            </a>
            <a class="nav-link" href="AdaugareTask.php">
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
            <?php
          //conexiune baza de date
          $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
          $user=$_SESSION['cod_A'];
          //functie pentru generarea unei parole
            function generatePassword($length = 12) {
              $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@$#!_*%';
              $password = '';
              for ($i = 0; $i < $length; $i++) {
                  $password .= $chars[rand(0, strlen($chars) - 1)];
              }
              return $password;
            }
            $parola=generatePassword(12);
            $parola_criptata=md5('$parola');
          //adăugare cu parametri
          $query1=mysqli_query($con,"INSERT INTO conturi_logare (tip_user,user,parola) VALUES (2,'$user','$parola_criptata')") or die ("Inserarea nu a putut avea loc!". mysqli_error($con));
          //header('Location:http://localhost/aplicatie/PaginaAdmin/GenerareParola.php');
          mysqli_close($con); 
              ?>
        <div class="container-fluid px-4">
          <h3 class="mt-4">Generare parolă angajat</h3>
            <div class=" alert alert-success alert-dismissible fade show" role="alert">
              Felicitări! Angajatul a fost adăugat cu succes în sistem.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div> 
            <center> 
           <table class="table table-bordered w-25 mt-2">
           <thead>
                <tr>
                  <th scope="col">User</th>
                  <th scope="col">Parolă</th>
                </tr>
                <tr>
                  <td><?php echo $user?> </td>
                  <td><?php echo $parola?> </td>
                </tr>
          </thead>
          </table></center>
          <center><a href="Admin.php" class="btn btn-outline-primary w-25 mt-2">OK</a></center>
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


?>
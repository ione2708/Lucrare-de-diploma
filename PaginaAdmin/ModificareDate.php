<?php
session_start();
if (!isset($_SESSION['parolaADMIN']))
{
    header("Location: Login.php");
}
else
{
  if (!isset($_POST['submit_form']) && !isset($_POST['cancel_form'])){
  //conexiune baza de date
$con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
$cod=$_GET['cod_A'];
$query=mysqli_query($con,"select a.nume, a.prenume, a.email, a.telefon, a.cnp, a.data_nastere, a.sex, a.adresa, a.cod_A, b.cod_P, c.cod_D, a.nivel from angajati as a, posturi as b, departamente as c WHERE cod_A NOT IN ('A12345') AND a.post=b.cod_P AND a.departament=c.cod_D AND a.cod_A='$cod' and sters='0' ORDER BY a.nume");
//preluare date din baza de date
while ($row = mysqli_fetch_row($query))
          {
            $contor=0;
            foreach ($row as $value)
            {
              $sir[$contor]=$value;
              $contor++;
            }
            $nume=$sir[0];
            $prenume=$sir[1];
            $email=$sir[2];
            $telefon=$sir[3];
            $cnp=$sir[4];
            $dataN=$sir[5];
            $gen=$sir[6];
            $adresa=$sir[7];
            $cod_A=$sir[8];
            $post=$sir[9];
            $departament=$sir[10];
            $nivel=$sir[11];
          }
          
    ?>
    <html>
<head>
  <title>Modificare date</title>
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
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" color="white"><i class="fas fa-user fa-fw "></i></a>
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
        <h3 class="mt-4">Modificarea datelor angajatului <?php echo$_GET['cod_A'];?> </h3>
      
      <div class="card mb-4 border border-info">
            <div class="card-body">
              <div class="formular">
              <form method="post"  action="">
                <div class="card-body header">
                <center><b>Date personale</b></center>
                </div>
                <div class="row form-group was-validated">
                    <div class="col md-4 form-group">
                      <label for="nume">Nume</label>
                      <input type="text" class="form-control" value=<?php echo $nume ?> name="nume" pattern="^[A-Z][a-zA-Z]*$">
                    </div>
                    <div class="col md-4">
                      <label for="prenume">Prenume</label>
                      <input type="text" class="form-control" value=<?php echo $prenume ?> name="prenume" pattern="^[A-Z][a-zA-Z]*(-[A-Z][a-zA-Z]*)*$">
                    </div>
                </div>
                <div class="row form-group was-validated">
                    <div class="col md-4">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" value=<?php echo $email ?> name="email" pattern="^[a-zA-Z0-9._%+-]+@company\.com$">
                    </div>
                    <div class="col md-4">
                        <label for="Telefon">Telefon</label>
                        <input type="text" class="form-control" value=<?php echo $telefon ?> name="telefon" pattern="^07[0-9]{8}">
                    </div>
                </div>
                <div class="row form-group was-validated">
                    <div class="col md-4">
                        <label for="cnp">CNP</label>
                        <input type="text" class="form-control" value=<?php echo $cnp ?> name="cnp" pattern="^[1-8]\d{2}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])\d{6}$">
                    </div>
                    <div class="col md-4">
                        <label for="dataN">Data nașterii</label>
                        <input type="date" class="form-control" value=<?php echo $dataN ?> name="dataN">
                    </div>
                </div>
                <div class="row form-group was-validated">
                    <div class="col md-4">
                        <label for="gen">Sex</label>
                        <select class="custom-select" name="gen">
                            <!-- <option value="">Alege sex</option> -->
                            <?php
                              $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
                              $query_a=mysqli_query($con,"select sex from angajati where cod_A='$cod_A'");
                              $row = mysqli_fetch_assoc($query_a);
                              if($row['gen']=='1')
                              echo '<option value="1">' . "Feminin" . '</option>';
                              else
                              echo '<option value="2">' . "Masculin" . '</option>';
                              mysqli_close($con);
                              ?>
                            <option value="1">Feminin</option>
                            <option value="2">Masculin</option>
                        </select>
                    </div>
                    <div class="col md-4">
                      <label for="adresa">Adresă</label>
                      <input type="text" class="form-control" value="<?php echo htmlspecialchars($adresa, ENT_QUOTES); ?>" name="adresa">
                    </div>
                </div>
                <div class="card-body header">
                <center><b>Informații tehnice</b></center>
                </div>
                    <div class="row form-group was-validated">
                        <div class="col md-4">
                          <label for="cod_A">Cod angajat</label>
                          <input type="text" class="form-control" value=<?php echo $cod_A; ?> name="cod_A" pattern="^A[0-9]{5}">
                        </div>
                        <div class="col md-4">
                        <label for="gen">Post</label>
                            <select class="custom-select" name="post">
                              <?php
                              $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
                              $query_a=mysqli_query($con,"select cod_P, descriere from posturi where cod_P='$post'");
                              $row = mysqli_fetch_assoc($query_a);
                              echo '<option value="' . $row['cod_P'] . '">' . $row['descriere'] . '</option>';
                              mysqli_close($con);
                              ?>
                            <!-- <option value="">Alege post</option> -->
                              <?php
                              $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
                              $query_a=mysqli_query($con,"select cod_P, descriere from posturi where cod_P NOT IN('999')");
                              while ($row = mysqli_fetch_assoc($query_a)) {
                                echo '<option value="' . $row['cod_P'] . '">' . $row['descriere'] . '</option>';
                              }
                              mysqli_close($con);
                              ?>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group was-validated">
                        <div class="col md-4">
                          <label for="departament">Departament</label>
                          <select class="custom-select" name="departament">
                          <?php
                              $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
                              $query_a=mysqli_query($con,"select cod_D, denumire from departamente where cod_D='$departament'");
                              $row = mysqli_fetch_assoc($query_a);
                              echo '<option value="' . $row['cod_D'] . '">' . $row['denumire'] . '</option>';
                              mysqli_close($con);
                              ?>
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
                          <label for="nivel">Nivel</label>
                          <select class="custom-select" name="nivel">
                          <?php
                              $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
                              $query_a=mysqli_query($con,"select nivel from angajati where cod_A='$cod_A'");
                              $row = mysqli_fetch_assoc($query_a);
                              if($row['nivel']=='1')
                              echo '<option value="1">' . "Incepator" . '</option>';
                              elseif($row['nivel']=='2')
                              echo '<option value="2">' . "Junior" . '</option>';
                              elseif($row['nivel']=='3')
                              echo '<option value="3">' . "Senior" . '</option>';
                              else
                              echo '<option value="4">' . "Expert" . '</option>';
                              mysqli_close($con);
                              ?>
                              <option value="1">Incepator</option>
                                <option value="2">Junior</option>
                                <option value="3">Senior</option>
                                <option value="4">Expert</option>
                          </select>
                        </div>
                    </div>
                
                <center><br>
                  <input class="btn btn-danger" type="submit" name="submit_form" value="Modifică">
                <input class="btn btn-dark" type="submit" name="cancel_form" value="Renunță">
              </center>
              </form>
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
else if(isset($_POST['submit_form'])) {
//mysqli_close($con); 
$con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
$nume_m=$_POST['nume'];
$prenume_m=$_POST['prenume'];
$email_m=$_POST['email'];
$telefon_m=$_POST['telefon'];
$cnp_m=$_POST['cnp'];
$data=$_POST['dataN'];
$dataN_m=date("Y-m-d",strtotime($data));
$gen_m=$_POST['gen'];
$adresa_m=$_POST['adresa'];
//preluare date tehnice din form
$cod_A_m=$_POST['cod_A'];
$post_m=$_POST['post'];
$departament_m=$_POST['departament'];
$nivel_m=$_POST['nivel'];


$update=mysqli_query($con,"UPDATE angajati SET nume='$nume_m', prenume='$prenume_m',email='$email_m', 
telefon='$telefon_m', cnp='$cnp_m', data_nastere='$dataN_m', sex='$gen_m', adresa='$adresa_m', cod_A='$cod_A_m', 
post='$post_m', departament='$departament_m', nivel='$nivel_m' WHERE cod_A='$cod_A_m' ");
header('Location:http://localhost/aplicatie/PaginaAdmin/ListaAngajati.php');

}
else if(isset($_POST['cancel_form']))
{
  
header('Location:http://localhost/aplicatie/PaginaAdmin/ListaAngajati.php');
}
return false;
 
}

?>
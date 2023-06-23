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
  <title>Administrator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="test.css">
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
            <a class="nav-link active" href="Admin.php" aria-selected="true">
              Raport activitate
            </a>
            <a class="nav-link" href="AdaugareAngajat.php">
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
        <div class="container-fluid px-4">
          <h3 class="mt-4">Rapoarte</h3>
          <div class="card mb-4">
            <div class="card-body">
            <div class="container-detalii">
                <div class="descriere"> 
                    <div class="card text-center" style="width: 70rem;height:30rem;border-radius:10px">
                        <h5>Rapoarte</h5>
                        
                        <form method="post" action="Generare_raport.php" style="margin-left:50px;margin-top:5px;">
                          <div class="row" style="justify-content: center;">
                            <div class="col-auto">
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
                            <div class="col-auto">
                              <select class="custom-select" name="luna" required>
                                  <option value="">Alege luna</option>
                                  <option value="1">Ianuarie</option>
                                  <option value="2">Februarie</option>
                                  <option value="3">Martie</option>
                                  <option value="4">Aprilie</option>
                                  <option value="5">Mai</option>
                                  <option value="6">Iunie</option>
                                  <option value="7">Iulie</option>
                                  <option value="8">August</option>
                                  <option value="9">Septembrie</option>
                                  <option value="10">Octombrie</option>
                                  <option value="11">Noiembrie</option>
                                  <option value="12">Decembrie</option>
                              </select>
                            </div>
                              <div class="col-auto">
                              <button class="btn" style="background-color: #50adff">Generează raport</button>
                            </div>
                          </div>
                        </form>
                        
                        <div class="afisare" style="overflow:auto">
                        <h5>Listă rapoarte</h5>
                        <?php
                        
                            //conexiune baza de date
                            $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
                            $a=$_SESSION['userCurent'];
                           
                            $afisare=mysqli_query($con,"select denumire_pdf, document, departament from rapoarte order by denumire_pdf");
                            $nr=@mysqli_num_rows($afisare);
                            if($nr==0)
                            {
                              die ("Nu exista niciun raport");
                            }
                            else
                            {
                              //capul de tabel
                              ?>
                            
                                <ul class="responsive-table">
                                <li class="table-header" style="background-color: #50adff">
                                  <!-- <div class="col">Departament</div> -->
                                  <div class="col">Document</div>
                                  <div class="col">Acțiuni</div> 
                              </li>
                              <?php
                                while ($row = mysqli_fetch_row($afisare))
                                {
                                  $contor=0;
                                  echo " <li class='table-row'>";
                                  
                                  foreach ($row as $value)
                                  {
                                    // echo "<div class='col'>$value</div>";
                                    $sir[$contor]=$value;
                                    $contor++;
                                  }
                                  $denumire_pdf=$sir[0];
                                  $continut=$sir[1];
                                  $dep=$sir[2];
                                  
                                  // echo "<div class='col'>$dep</div>";
                                  echo "<div class='col'>$denumire_pdf</div>";
                                  echo "<div class='col'><button class='btn'><a href='Vizualizare_pdf.php?denumire_pdf=$denumire_pdf'><i class='fa-solid fa-eye' style='color: #3d79e1;' title='Vizualizare pdf'></i></a></button>";
                                  echo "</li>";
                                }
                              }
                              ?> 
                              </ul>
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

<?php
}
?>
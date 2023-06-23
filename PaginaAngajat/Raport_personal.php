<?php
session_start();
if (!isset($_SESSION['parolaAngajat'])){
header("Location: Login.php");
}
else{

    $user=$_SESSION['userCurent'];
    $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
    $luna_curenta=date("m");
    $luna=date("F");
    $departament=mysqli_query($con,"select departament from angajati where cod_A='$user'");
    $dep=mysqli_fetch_row($departament);
    //proiecte finalizate
    $query=mysqli_query($con,"select count(*) from taskuri where status='Finalizat' AND responsabil='$user' AND MONTH(data_completare)='$luna_curenta'");
    $prf=mysqli_fetch_row($query);
    //proiecte in lucru
    $query1=mysqli_query($con,"select count(*) from taskuri where status='In lucru' AND responsabil='$user' AND MONTH(data_start)='$luna_curenta'");
    $prl=mysqli_fetch_row($query1);
    //overdue
    $query2=mysqli_query($con,"select count(*) from taskuri where status='Finalizat cu intarziere' AND responsabil='$user' AND MONTH(data_completare)='$luna_curenta'");
    $pri=mysqli_fetch_row($query2);

    $prt=$prf[0]+$prl[0]+$pri[0];

    $task1=mysqli_query($con,"select count(*) from taskuri where nivel_dificultate='1-Usor' AND responsabil='$user' AND MONTH(data_start)='$luna_curenta'");
    $t1=mysqli_fetch_row($task1);

    $task2=mysqli_query($con,"select count(*) from taskuri where nivel_dificultate='2-Mediu' AND responsabil='$user' AND MONTH(data_start)='$luna_curenta'");
    $t2=mysqli_fetch_row($task2);

    $task3=mysqli_query($con,"select count(*) from taskuri where nivel_dificultate='3-Dificil' AND responsabil='$user' AND MONTH(data_start)='$luna_curenta'");
    $t3=mysqli_fetch_row($task3);

    //task-uri angajat

    $nr=mysqli_query($con,"select count(*) from taskuri where status='In lucru' AND departament='$dep[0]' AND responsabil!='$user' AND MONTH(data_start)='$luna_curenta'");
    $n=mysqli_fetch_row($nr);

    $a=mysqli_query($con,"select count(*) from taskuri where departament='$dep[0]' AND responsabil is null AND MONTH(data_start)='$luna_curenta'");
    $rez=mysqli_fetch_row($a);
  ?>
  <html>
<head>
<title>Angajat</title>
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
          <a class="nav-link active" href="Raport_personal.php" aria-selected="true">
            Profilul meu
          </a>
          <a class="nav-link" href="Proiecte.php">
            Taskuri
          </a>
          <a class="nav-link" href="ProiecteAsignate.php">
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
        <h3 class="mt-4" style="text-align:left">Statusul activității din această lună</h3>
        <div class="card mb-4">
          <div class="card-body">
            <div class="cards">
                <div class="card text-center" style="width: 16rem;height:8rem;border-radius:10px">
                    <h5 class="card-title">Taskuri finalizate</h5>
                    <div class="card-body">
                    <span class="badge rounded-pill badge-padding-x:5px badge-padding-y:5px" style="background-color: #6fffb1;font-size: 1.75em;"><?php echo $prf[0];?></span>
                    </div>
                </div>

                <div class="card text-center" style="width: 16rem;height:8rem;border-radius:10px">
                    <h5 class="card-title">Taskuri în lucru</h5>
                    <div class="card-body">
                    <span class="badge rounded-pill badge-padding-x:5px badge-padding-y:5px" style="background-color: #ffe26f;font-size: 1.75em;"><?php echo $prl[0];?></span>
                    </div>
                </div>

                <div class="card text-center" style="width: 16rem;height:8rem;border-radius:10px">
                    <h5 class="card-title">Taskuri finalizate întârziat</h5>
                    <div class="card-body">
                    <span class="badge rounded-pill badge-padding-x:5px badge-padding-y:5px" style="background-color: #ff986f;font-size: 1.75em;"><?php echo $pri[0];?></span>
                    </div>
                </div>

                <div class="card text-center" style="width: 16rem;height:8rem;border-radius:10px">
                    <h5 class="card-title">Total taskuri</h5>
                    <div class="card-body">
                    <span class="badge rounded-pill badge-padding-x:5px badge-padding-y:5px" style="background-color: #6f8dff;font-size: 1.75em;"><?php echo $prt;?></span>
                    </div>
                </div>
            </div>
            <div class="grafice">

                <div class="card text-center" style="width: 35rem;height:25rem;border-radius:10px">
                    <h5 class="card-title">Raport dificultate</h5>
                     <div class="card-body text-center"> 
                      <div id="columnchart_values" style="width: 35rem; height: 25rem;"></div>
                     </div> 
                </div>

                <div class="card text-center" style="width: 35rem;height:25rem;border-radius:10px">
                    <h5 class="card-title">Status taskuri luate în lucru în departament</h5>
                    <div class="card-body text-center">
                    <div id="donutchart" style="width: 35rem; height: 25rem;"></div>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
  
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawChart1);

    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Nivel de dificultate", "Total", { role: "style" } ],
        ["Ușoare", <?php echo $t1[0];?>, "#ff6f6f"],
        ["Medii", <?php echo $t2[0];?>, "#6fe3ff"],
        ["Dificile", <?php echo $t3[0];?>, "#79ff5d"]
     
      ]);
      
      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Dificultatea task-urilor de luna aceasta",
        width: 500,
        height: 350,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart.draw(view, options);
  }

      function drawChart1() {
        var data = google.visualization.arrayToDataTable([
          ["Task", "Numar", { role: "style" }],
          ["Ale mele",<?php echo $prl[0];?>, "fill:#6fd3ff"],
          ["Restul colegilor", <?php echo $n[0];?>, "#f9a8ed"],
          ["Neluate in lucru", <?php echo $rez[0];?>, "pink"]
        ]);

        var options = {
          title: 'Statusul task-urilor în departament',
          width: 500,
        height: 350,
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
  </script>
<?php
}
?>
<?php
if (!isset($_POST['parola'])){
?>
    <html>
    <head>
    <title>Logare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="login">

        <h1 class="text-center">Bine ați venit!</h1>
        
        <form class="angajat" method="post" action="">
            <div class="form-group was-validated">
                <label class="form-label" for="cod_A">Cod angajat</label>
                <input class="form-control" type="text" name="cod_A" placeholder="A12345" pattern="^A[0-9]{5}" required>
            </div>
            <div class="form-group was-validated">
                <label class="form-label" for="password">Parola</label>
                <input class="form-control" type="password" name="parola" required>
            </div>
            <input class="btn btn-outline-primary w-100" type="submit" value="Logare">
        </form>

    </div>
</body>
</html>
   <?php 
}
else {
    session_start();
    //conexiune server
    $con=mysqli_connect("localhost","root","","aplicatie") or die ("Nu se poate conecta la serverul MySQL");
    //se preiau parametri de la form-ul HTML
    $user=$_POST['cod_A'];
    $parola=$_POST['parola'];
    $parola_criptata=md5($parola);
$interogare="select count(*) from conturi_logare where user='$user' and parola='$parola_criptata'";
$rezultat=mysqli_query($con, $interogare) or die(mysqli_error($con));
$row=mysqli_fetch_row($rezultat);
$numar=$row[0];
//verificam daca exista userul
if($numar==1){
    $interogare="select tip_user from conturi_logare where user='$user' and parola='$parola_criptata'";
    $rezultat=mysqli_query($con, $interogare) or
    die(mysqli_error($con));
    $row =mysqli_fetch_row($rezultat);
    $tipUser=$row[0];
    //daca user-ul este de tip admin, se va salva parola acestuia in variabila de sesiune parolaADMIN 
    if($tipUser=='1'){
    $_SESSION['parolaADMIN']=$parola;
    $_SESSION['userCurent']=$user;
    header('Location: http://localhost/aplicatie/PaginaAdmin/Admin.php');
    }
    elseif($tipUser=='2'){
        $_SESSION['parolaAngajat']=$parola;
        $_SESSION['userCurent']=$user;
        header('Location: http://localhost/aplicatie/PaginaAngajat/Raport_personal.php');
        }
}
//daca datele de autentificare introduse in form sunt gresite, pe ecran apare din nou form-ul, cerandu-i-se utilizatorului sa introduca datele de login din nou
else{
    ?>
     <html>
    <head>
    <title>Logare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="login">

        <h1 class="text-center">Bine ați venit!</h1>
        
        <form class="angajat" method="post" action="">
            <div class="form-group was-validated">
                <label class="form-label" for="cod_A">Cod angajat</label>
                <input class="form-control" type="text" name="cod_A" placeholder="A12345" required>
                <div class="invalid-feedback">
                    Reintroduceți codul!
                </div>
            </div>
            <div class="form-group was-validated">
                <label class="form-label" for="password">Parola</label>
                <input class="form-control" type="password" name="parola" required>
                <div class="invalid-feedback">
                   Reintroduceți parola!
                </div>
            </div>
            <input class="btn btn-outline-primary w-100" type="submit" value="Logare">
        </form>

    </div>
</body>
</html>
<?php
}
}
?>

<?php 
session_start(); 
require_once '../connection.php'; 

// Preveri, ali je uporabnik prijavljen in ima vlogo lektorja
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
    header("Location: ../login/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Lektor Domača stran</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="home.css">
  </head>
  <body>
    <header>
      <nav>
        <ul>
          <li><a href="home_lektor.php">Domača stran</a></li>
          <li><a href="#">Pregled prispevkov</a></li>
          <li id="logout"><a href="logout.php">Odjava</a></li>
        </ul>
      </nav>
    </header>
    <main>
      <h1>Dobrodošli, Lektor!</h1>
      <p>To je domača stran lektorja.</p>
      <p>Pregled prispevkov lahko opravite tako, da kliknete na povezavo "Pregled prispevkov" v navigacijskem meniju.</p>
    </main>
  </body>
</html>

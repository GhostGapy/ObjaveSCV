<?php 
session_start(); 
require_once '../connection.php'; 

// Preveri, ali je uporabnik prijavljen in ima vlogo pisatelja
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 2) {
    header("Location: ../login/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Pisatelj Domača stran</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="home.css">
  </head>
  <body>
    <header>
      <nav>
        <ul>
          <li><a href="home_pisatelj.php">Domača stran</a></li>
          <li><a href="#">Moji prispevki</a></li>
          <li id="logout"><a href="logout.php">Odjava</a></li>
        </ul>
      </nav>
    </header>
    <main>
      <h1>Dobrodošli, Pisatelj!</h1>
      <p>To je domača stran pisatelja.</p>
      <p>Moje prispevke lahko pregledate tako, da kliknete na povezavo "Moji prispevki" v navigacijskem meniju.</p>
    </main>
  </body>
</html>

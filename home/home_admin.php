<?php 
session_start(); 
require_once '../connection.php'; 

// Preveri, ali je uporabnik prijavljen in ima vlogo lektorja
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 0) {
    header("Location: ../login/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Admin Domača stran</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="home.css">
  </head>
  <body>
    <header>
      <nav>
        <ul>
          <li><a href="home_admin.php">Domača stran</a></li>
          <li><a href="users.php">Uporabniki</a></li>
          <li><a href="../login/logout.php">Odjava</a></li>
        </ul>
      </nav>
    </header>
    <main>
      <h1>Dobrodošli, Administrator!</h1>
      <p>To je domača stran administratorja.</p>
      <p>Uporabnike lahko upravljate tako, da kliknete na povezavo "Uporabniki" v navigacijskem meniju.</p>
    </main>
  </body>
</html>

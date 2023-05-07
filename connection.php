<?php
$DATABASE_HOST = "localhost";
$DATABASE_NAME = "objave_scv";
$DATABASE_USER = "root";
$DATABASE_PASS = "";

$link=mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME)or 
    die("NE");

    //kodiranje znakov

    mysqli_set_charset($link, "utf8");
?>

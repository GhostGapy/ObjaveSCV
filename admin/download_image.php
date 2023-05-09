<?php
session_start();
require_once '../connection.php';

// Check if user is logged in and has the role of an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 0) {
    header("Location: ../login/login.php");
    exit();
}

// Check if post ID is specified in the query string
if (!isset($_GET['id'])) {
    header("Location: home_admin.php");
    exit();
}

$id = $_GET['id'];

// Check if the post ID is valid
$stmt = mysqli_prepare($link, "SELECT COUNT(*) FROM posts WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);
if ($row[0] < 1) {
    header("Location: home_admin.php");
    exit();
}

// Create a ZIP file containing all images for the post
$zip = new ZipArchive();
$zip_file_name = "post_images_" . $id . ".zip";
$zip->open($zip_file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE);

$stmt = mysqli_prepare($link, "SELECT path FROM photos WHERE post_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_array($result)) {
    $image_data = $row[0];
    $image_file_name = uniqid() . ".jpg";
    $zip->addFromString($image_file_name, $image_data);
}


$zip->close();

// Download the ZIP file
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zip_file_name);
header('Content-Length: ' . filesize($zip_file_name));
readfile($zip_file_name);

// Delete the ZIP file
unlink($zip_file_name);
?>

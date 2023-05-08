<?php
require_once '../connection.php';

// Check if post ID is provided
if (!isset($_GET['id'])) {
    echo "Post ID not provided.";
    exit();
}

$post_id = $_GET['id'];

// Get images for the post from the database
$stmt = mysqli_prepare($link, "SELECT path FROM photos WHERE post_id = ?");
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if any images were found
if (mysqli_num_rows($result) == 0) {
    echo "No images found for this post.";
    exit();
}

// Create a zip archive
$zipname = "images_for_post_" . $post_id . ".zip";
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);

// Loop through the images and add them to the archive
while ($row = mysqli_fetch_assoc($result)) {
    $image_data = base64_decode($row['path']);
    $image_name = "image_" . uniqid() . ".jpg";
    $zip->addFromString($image_name, $image_data);
}

// Close the zip archive
$zip->close();

// Download the zip archive
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zipname);
header('Content-Length: ' . filesize($zipname));
readfile($zipname);

// Delete the zip archive
unlink($zipname);
?>

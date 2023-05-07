<?php
session_start();
require_once '../connection.php';

// Check if user is logged in and has the role of a writer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 2) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    // Check if all required fields are filled
    if (isset($_POST['title']) && isset($_POST['body']) && isset($_POST['date']) && isset($_POST['proofread'])) {
        $title = $_POST['title'];
        $body = $_POST['body'];
        $date = $_POST['date'];
        $lektorirano = $_POST['proofread'];
        $lektor_email = isset($_POST['email']) ? $_POST['email'] : '';

        //preveri ali je osnutek ali ne
        if(isset($_POST['osnutek']))
        {
            $user_id=$_SESSION['user_id'];
        }
        else if(isset($_POST['oddajte']))
        {
        if ($lektorirano == 1 && $lektor_email != '') {
            // Pridobi Id maila
            $stmt = mysqli_prepare($link, "SELECT id FROM users WHERE email = ? AND user_type = 1");
            if (!$stmt) {
                echo "<script>alert('Napaka pri pripravi poizvedbe: " . mysqli_error($link) . "')</script>";
                $user_id=$_SESSION['user_id'];
                header("Location: edit_post.php");
                exit();
            }
            mysqli_stmt_bind_param($stmt, "s", $lektor_email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $user_id = $row['id'];
            } else {
                echo "<script>alert('Uporabnika z tem emailom ni bilo najdenega')</script>";
                $user_id=$_SESSION['user_id'];
                header("Location: edit_post.php");
                exit();
            }
        } else {
            $user_id=$_SESSION['user_id'];
        }
    }

        // Check if any of the new checkboxes are checked
        $platform = isset($_POST['platform']) ? $_POST['platform'] : array();
        $org = isset($_POST['org']) ? $_POST['org'] : array();


        // Insert new post into the database
        $stmt = mysqli_prepare($link, "INSERT INTO posts (naslov, besedilo, datum, lektorirano, user_id, lektor_email) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssis", $title, $body, $date, $lektorirano, $user_id, $lektor_email);
        mysqli_stmt_execute($stmt);

        // Get ID of the new post
        $post_id = mysqli_insert_id($link);

        // Insert new checkboxes into the database
        foreach ($platform as $platform_value) {
            $stmt = mysqli_prepare($link, "INSERT INTO mesto_posts (post_id, mesto_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ii", $post_id, $platform_value);
            mysqli_stmt_execute($stmt);
        }

        foreach ($org as $org_value) {
            $stmt = mysqli_prepare($link, "INSERT INTO sola_posts (sola_id, post_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ii", $org_value, $post_id);
            mysqli_stmt_execute($stmt);
        }
        
        // Check if any images were uploaded
        if (count($_FILES['images']['name']) > 0 && $_FILES['images']['name'][0] != '') {
            $images = $_FILES['images'];

            // Loop through each uploaded image
            for ($i = 0; $i < count($images['name']); $i++) {
                $image_name = $images['name'][$i];
                $image_size = $images['size'][$i];
                $image_type = $images['type'][$i];
                $image_error = $images['error'][$i];
                $image_tmp_name = $images['tmp_name'][$i];

                // Convert image data to base64 format
                $image_data = base64_encode(file_get_contents($image_tmp_name));

                // Insert new photo into the database
                $stmt = mysqli_prepare($link, "INSERT INTO photos (path, post_id) VALUES (?, ?)");
                mysqli_stmt_bind_param($stmt, "si", $image_data, $post_id);
                mysqli_stmt_execute($stmt);
            }

            // Redirect to home page
            header("Location: ../pisatelj/home_pisatelj.php");
            exit();
        } else {
            // Redirect to home page
            header("Location: ../pisatelj/home_pisatelj.php");
            exit();
        }
    } else {
        // Required fields are missing
        echo "Required fields are missing.";
    }
}
?>

<?php
require 'db.php';

// Retrieve image by user_id
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $query = "SELECT * FROM `userImages` WHERE `user_id`=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $result = $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $user_id, $image, $content_type);
    $stmt->fetch();

    mysqli_stmt_close($stmt);
    
    echo '<img src="data:'.$content_type.';base64,'.base64_encode($image).'"/>';
    http_response_code(200);
    exit();
}

?>
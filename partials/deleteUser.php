<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    include_once("./db_connect.php");
    $query = mysqli_query($conn, "DELETE FROM users WHERE id = '$user_id'");
    if($query) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(["success" => false]);
}
?>
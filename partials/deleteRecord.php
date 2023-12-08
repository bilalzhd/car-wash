<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['id'];
    include_once("./db_connect.php");
    $query = mysqli_query($conn, "DELETE FROM records_2 WHERE id = '$user_id'");
    if($query) {
        echo json_encode(["status" => 1]);
    } else {
        echo json_encode(["status" => 0]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(["status" => 0]);
}
?>
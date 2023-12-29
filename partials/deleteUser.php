<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['id'];
    include_once("./db_connect.php");
    $query = mysqli_query($conn, "DELETE FROM users WHERE id = '$user_id'");
    if($query) {
        echo json_encode(["success" => true, "msg" => "User deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "msg" => "User not found"]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(["success" => false, "msg" => "There was an error deleting the user!"]);
}
?>
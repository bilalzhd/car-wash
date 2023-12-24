<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    include_once("./db_connect.php");


    $delete_hall_query = mysqli_query($conn, "DELETE FROM halls WHERE date = '$date'");


    if ($delete_hall_query) {
        echo json_encode(["status" => 1, "msg" => "Customer has been deleted successfully"]);
    } else {
        echo json_encode(["status" => 0, "msg" => "There has been an error deleting the customer"]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(["status" => 0, "msg" => "There has been an error deleting the customer"]);
}
?>
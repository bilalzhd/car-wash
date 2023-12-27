<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    include_once("./db_connect.php");

    $checkHalls = mysqli_query($conn, "SELECT * FROM halls");
    if(mysqli_num_rows($checkHalls) > 1) {
        $delete_hall_query = mysqli_query($conn, "DELETE FROM halls WHERE id = '$id'");
        if ($delete_hall_query) {
            echo json_encode(["status" => 1, "msg" => "Customer has been deleted successfully"]);
        } else {
            echo json_encode(["status" => 0, "msg" => "There has been an error deleting the customer"]);
        }
    } else {
        echo json_encode(["status" => 0, "msg" => "There must have atleast one entry for halls"]);
    }

    mysqli_close($conn);

} else {
    echo json_encode(["status" => 0, "msg" => "There has been an error deleting the customer"]);
}
?>
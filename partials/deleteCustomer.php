<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['id'];
    include_once("./db_connect.php");

    // Fetch customer name before deletion
    $customer_query = mysqli_query($conn, "SELECT name FROM customers WHERE id = '$user_id'");
    $customer_data = mysqli_fetch_assoc($customer_query);
    $customer_name = $customer_data['name'];

    $delete_customer_query = mysqli_query($conn, "DELETE FROM customers WHERE id = '$user_id'");
    
    // Update records with the customer name before deletion
    $update_records_query = mysqli_query($conn, "UPDATE records SET customer_name = '$customer_name' WHERE customer_id = '$user_id'");



    if ($delete_customer_query && $update_records_query) {
        echo json_encode(["status" => 1, "msg" => "Customer has been deleted successfully"]);
    } else {
        echo json_encode(["status" => 0, "msg" => "There has been an error deleting the customer"]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(["status" => 0, "msg" => "There has been an error deleting the customer"]);
}
?>
<?php
include_once("./db_connect.php");
if (($_SERVER["REQUEST_METHOD"] == "POST")) {
    $name = $_POST['edit_name'];
    $phone = $_POST['edit_phone'];
    $id = $_POST['id'];

    $userData = array(
        'edit_name' => $name,
        'edit_phone' => $phone,
    );

    $query = mysqli_query($conn, "UPDATE customers SET name = '$name', phone = '$phone' WHERE id = '$id'");
    if ($query) {
        $response = array(
            'status' => 1,
            'msg' => 'Member data has been updated successfully.',
            'data' => $userData
        );
        echo json_encode($response);
    } else {
        $error = true;
    }
}

<?php
include_once("./db_connect.php");
if (($_SERVER["REQUEST_METHOD"] == "POST")) {
    $name = $_POST['edit_name'];
    $role = $_POST['edit_role'];
    $password = $_POST['edit_password'];
    $id = $_POST['id'];

    $userData = array(
        'edit_name' => $name,
        'edit_role' => $role,
        'edit_password' => $password,
    );

    $query = mysqli_query($conn, "UPDATE users SET username = '$name', role = '$role', password = '$password' WHERE id = '$id'");
    if ($query) {
        $response = array(
            'status' => 1,
            'msg' => 'User data has been updated successfully.',
            'data' => $userData
        );
        echo json_encode($response);
    } else {
        $response = array(
            'status' => 0,
            'msg' => 'There has been an error updating the data.',
            'data' => $userData
        );
        echo json_encode($response);
    }
}

<?php
include_once("./db_connect.php");
if (($_SERVER["REQUEST_METHOD"] == "POST")) {
    $customer = $_POST['edit_name'];
    $date = $_POST['edit_date'];
    $id = $_POST['id'];
    $number_of_halls = $_POST['number_of_halls'];
    $hallValues = [];
    for ($i = 1; $i <= $number_of_halls; $i++) {
        $hallValues[] = $_POST["hall_$i"];
    }
    $userData = array(
        'name' => $customer,
        'date' => $date,
        'number_of_halls' => $number_of_halls,
    );

    for ($i = 1; $i <= $number_of_halls; $i++) {
        $userData['hall_' . $i] = $hallValues[($i - 1)];
    }

    $entryAlreadyRecordedQuery = mysqli_query($conn, "SELECT * FROM records_2 WHERE customer_id = '$customer' AND date = '$date'");

    $checkIfSameNameQuery = mysqli_query($conn, "SELECT * FROM records_2 WHERE id = '$id'");
    $sameName = false;

    while ($entry = mysqli_fetch_assoc($checkIfSameNameQuery)) {
        if ($customer == $entry['customer_id']) {
            $sameName = true;
            break;
        }
    }


    $entryAlreadyRecorded = mysqli_num_rows($entryAlreadyRecordedQuery);

    if ($entryAlreadyRecorded && $sameName) {
        $query = "UPDATE records_2 SET number_of_halls = '$number_of_halls', customer_id = '$customer', customer_name = 'Customer Name', date = '$date', ";
        for ($i = 1; $i <= $number_of_halls; $i++) {
            $_hall = $hallValues[($i - 1)];
            $query .= "hall_$i = '$_hall'";
            if ($i < $number_of_halls) {
                $query .= ", ";
            }
        }
        $query .= " WHERE id = '$id'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $response = array(
                'status' => 1,
                'msg' => 'The record has been updated successfully',
                'data' => $userData
            );
        } else {
            $response = array(
                'status' => 0,
                'msg' => 'There has been an error updating the record',
                'data' => $userData
            );
        }
    } else if (!$entryAlreadyRecorded) {
        $customer_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM customers WHERE id = '$customer'"))['name'];
        $query = "INSERT INTO records_2 (number_of_halls, customer_id, customer_name, date";
        for ($i = 1; $i <= $number_of_halls; $i++) {
            $query .= ", hall_$i";
        }
        $query .= ") VALUES ('$number_of_halls', '$customer', '$customer_name', '$date'";
        foreach ($hallValues as $value) {
            $query .= ", '$value'";
        }
        $query .= ")";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $response = array(
                'status' => 1,
                'msg' => 'The records has been added successfully!',
                'data' => $userData
            );
        } else {
            $response = array(
                'status' => 0,
                'msg' => 'There has been some error please try again later!',
                'data' => $userData
            );
        }
    } else {
        $response = array(
            'status' => 0,
            'msg' => 'There is already a record with the same name and same date try updating that',
            'data' => $userData
        );
    }
    echo json_encode($response);
}

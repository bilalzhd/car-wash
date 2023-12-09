<?php
// Assuming you have a MySQL connection established
include_once("./db_connect.php");
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["date"])) {
    $date = $_GET["date"];

    $sql = "SELECT number_of_halls, hall_1, hall_2, hall_3, hall_4, hall_5, hall_6, hall_7, hall_8, hall_9, hall_10 FROM records_2 WHERE date = '$date'";
    $result = mysqli_query($conn, $sql);
    $totalRecords = 0;
    $total = 0;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            for ($i = 1; $i <= $row['number_of_halls']; $i++) {
                $total += $row['hall_' . $i];
            }
            $totalRecords = $total;
        };

        // Return the result as JSON
        header('Content-Type: application/json');
        echo json_encode(["totalRecords" => $totalRecords]);
        // } else {
        //     // Handle no records found
        //     header('Content-Type: application/json');
        //     echo json_encode(["error" => "No records found"]);
        // }
    } else {
        // Handle query failure
        header('Content-Type: application/json');
        echo json_encode(["error" => "Query failed"]);
    }
} else {
    // Handle invalid or missing date parameter
    header('Content-Type: application/json');
    echo json_encode(["error" => "Invalid or missing date parameter"]);
}

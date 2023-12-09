<?php
// Assuming you have a database connection
include_once("./db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM records_2 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo "User not found";
    }

    $stmt->close();
}

mysqli_close($conn);
?>
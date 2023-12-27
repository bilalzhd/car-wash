<?php
session_start();
$submitted = false;
$error = false;
$no_records = false;
$page_title = "All Records";
$title = "All Record";
include 'partials/head.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$role = $_SESSION['role'];
include_once("./partials/db_connect.php");
$today_date = $_GET['date'];
$records = mysqli_query($conn, "SELECT * FROM records_2 WHERE date = '$today_date'");
if (mysqli_num_rows($records) < 1) {
    $no_records = true;
}
$totalCars = 0;
$total = 0;
while ($car = mysqli_fetch_assoc($records)) {
    for ($i = 1; $i <= $car['number_of_halls']; $i++) {
        $total += $car['hall_' . $i];
    }
    $totalCars = $total;
}
if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["edit_record"]))) {

    $customer = $_POST['edit_name'];
    $date = $_POST['edit_date'];
    $id = $_POST['user_id'];
    $number_of_halls = $_POST['number_of_halls'];
    $hallValues = [];
    for ($i = 1; $i <= $number_of_halls; $i++) {
        $hallValues[] = $_POST["hall_$i"];
    }
    echo $customer;
    echo $date;
    echo $id;
    echo $number_of_halls;
    echo $hallValues;
    die();
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

    if (!$entryAlreadyRecorded || ($entryAlreadyRecorded && $sameName)) {
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
            $submitted = true;
            $totalCars = 0;
            $total = 0;
            mysqli_data_seek($records, 0);
            while ($car = mysqli_fetch_assoc($records)) {
                for ($i = 1; $i <= $car['number_of_halls']; $i++) {
                    $total += $car['hall_' . $i];
                }
                $totalCars += $total;
            }
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }
}
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
<style>
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_processing,
    .dataTables_wrapper .dataTables_paginate {
        margin-bottom: 10px !important;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid indigo !important;
    }
</style>

<body>
    <?php include("./partials/header.php") ?>

    <?php
    if ($submitted || $error) { ?>
        <div class="<?php echo $submitted ? 'bg-green-500' : 'bg-red-500' ?> alert rounded-b text-white px-4 py-3 shadow-md text-xl" role="alert">
            <div class="flex items-center">
                <div class="py-1 rounded-full border-2 p-1 border-white">
                    <?php echo $submitted ? ('<svg height="18px" version="1.1" viewBox="0 0 18 15" width="18px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#fff" id="Core" transform="translate(-423.000000, -47.000000)"><g id="check" transform="translate(423.000000, 47.500000)"><path d="M6,10.2 L1.8,6 L0.4,7.4 L6,13 L18,1 L16.6,-0.4 L6,10.2 Z" id="Shape"/></g></g></g></svg>') : ('<svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 384 512"><path fill="#fff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>')
                    ?>
                </div>
                <div>
                    <p class="mx-4"><?php echo $error ? "There was an error while updating the record, try again later, or contact your developer." : "Record has been updated successfully!" ?></p>
                </div>
            </div>
        </div>
    <?php } ?>

    <main class="bg-gray-100">
        <div class="bg-white container shadow-lg rounded-md p-5 md:p-10 mx-auto mt-10">
            <div class="overflow-x-auto">
                <table id="example" class="display nowrap " style="width:100%">
                    <thead class="text-white bg-indigo-500">
                        <tr>
                            <?php mysqli_data_seek($records, 0); ?>
                            <th>Customer</th>
                            <?php
                            $num_halls;
                            $halls_query = mysqli_query($conn, "SELECT count FROM halls WHERE date <= '$today_date' ORDER by date DESC LIMIT 1");
                            $no_records_halls = mysqli_fetch_assoc($halls_query);
                            if (!$no_records) {
                                $num_halls = mysqli_fetch_assoc($records)['number_of_halls'];
                            } else {
                                if (isset($no_records_halls)) {
                                    $num_halls = $no_records_halls['count'];
                                } else {

                                    // if ($no_records_halls == NULL && $no_records) {
                                    $num_halls = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count FROM halls WHERE date >= '$today_date' ORDER by date ASC LIMIT 1"))['count'];
                                    // } else {
                                    //     $num_halls = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count FROM halls ORDER BY date DESC LIMIT 1;"))['count'];
                                    // }
                                }
                            }

                            for ($i = 1; $i <= $num_halls; $i++) {
                                echo '<th>Hall ' . $i . '</th>';
                            }
                            ?>
                            <th>Date</th>
                            <th>Total</th>
                            <?php
                            $today_dte = date("Y-m-d");
                            if ($role == 0 || ($role == 2 && $_GET['date'] >= $today_dte)) {
                                echo '<th>Actions</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // mysqli_data_seek($records, 0);
                        $date = $_GET['date'];
                        $query = "SELECT 
                        c.id AS customer_id,
                        c.name AS customer_name, c.delete_on,";
                        for($i=1; $i<$num_halls; $i++) {
                            $query .= "r.hall_".$i.",";
                        };
                        $query .= "r.number_of_halls,
                        r.date
                    FROM customers c
                    LEFT JOIN records_2 r ON c.id = r.customer_id AND r.date = '$date'
                    WHERE c.timestamp <= '$date' AND (c.delete_on = 0 OR c.delete_on >= '$date');
                    ";
                    // $customers = mysqli_query($conn, "SELECT * FROM customers WHERE timestamp <= '$date' AND delete_on <= '$date'");
                    $customers = mysqli_query($conn,$query);
                    while ($customer = mysqli_fetch_assoc($customers)) {
                            // echo var_dump($customer);
                            $customer_id = $customer['customer_id'];
                            $customer_name = $customer['customer_name'];
                            // echo "DELETE ON ". $customer['delete_on'];
                            $records = mysqli_query($conn, "SELECT * FROM records_2 WHERE customer_id = $customer_id AND date = '$date'");
                            $total = 0;
                            $record = mysqli_fetch_assoc($records);
                            for ($i = 1; $i <= $num_halls; $i++) {
                                $total +=  isset($record['hall_' . $i]) ? $record['hall_' . $i] : 0;
                            };
                            if($customer['delete_on'] != '0') {
                                if($total < 1) {
                                    continue;
                                }
                            }
                            $record_id = isset($record['id']) ? $record['id'] : rand(0, 10); ?>
                            <tr id="<?php echo $record_id ?>" data-id="<?php echo $record_id ?>">
                                <td>
                                    <span class="name"><?php echo $customer_name ?></span>
                                    <input type="hidden" class="editInput" name="edit_name" id="edit_name" value="<?php echo $customer_id ?>">
                                    <!-- <select name="edit_name" class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 editInput" id="edit_name" required style="display: none">
                                        <?php
                                        $customers_query = mysqli_query($conn, "SELECT * FROM customers WHERE delete_on <= '$date'");
                                        while ($cus = mysqli_fetch_assoc($customers_query)) { ?>
                                            <option <?php echo $customer_id == $cus['id'] ? "selected" : "" ?> value="<?php echo $cus['id'] ?>"><?php echo $cus['name'] ?></option>
                                        <?php } ?>
                                    </select> -->
                                </td>
                            <?php
                            for ($i = 1; $i <= $num_halls; $i++) {
                                $hall = isset($record['hall_' . $i]) ? $record['hall_' . $i] : 0;
                                echo '<td>
                                        <input type="hidden" name="number_of_halls" class="editInput" value="' . $num_halls . '">
                                        <span class="editSpan hall_' . $i . '">' . $hall . '</span>
                                        <input class="editInput block w-full px-4 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="number" name="hall_' . $i . '" id="hall_' . $i . '" required style="display: none" value="' . $hall . '" min="0">
                                    </td>';
                            }
                            // if ($role == 2 && $record['date'] >= $today_date) {
                            //     echo '<td>
                            //             <span class="editSpan date">' . $record['date'] . '</span>
                            //             <input class="editInput px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="date" style="display: none" value="' . $record['date'] . '" disabled id="edit_date">
                            //             <input type="hidden" class="editInput" name="edit_date" value="' . $record['date'] . '">
                            //             </td>';
                            // } else {
                                echo '<td>
                                    <span class="date">' . $_GET['date'] . '
                                    <input type="hidden" name="edit_date" class="editInput" value="'.$_GET['date'].'"></span>
                                    </td>';
                                    // };
                                    // <input class="editInput px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="date" name="edit_date" id="edit_date" required value="' . $_GET['date'] . '" style="display: none">

                            echo '<td>' . $total . '</td>';
                            if ($role == 0 || ($role == 2 && $record['date'] >= $today_date)) {
                                $id = isset($record['id']) ? $record['id'] : rand(0, 1);
                                echo '
                                <td class="w-1/4 flex whitespace-nowrap">
                                <div class="flex space-x-2">
                                <button data-id="' . $id . '" class="editBtn flex items-center hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg">Edit</button>
                                <button data-id="' . $id . '" class="deleteBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg">Delete</button>
                                <button data-id="' . $id . '" class="saveBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Save</button>
                                <button data-id="' . $id . '" class="confirmBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Confirm</button>
                                <button data-id="' . $id . '" class="cancelBtn flex hover:bg-indigo-700 transition-all duration-300 bg-gray-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Cancel</button>
                                </div>
                                </td>
                                </tr>';
                            }
                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <?php
                            $records = mysqli_query($conn, "SELECT * FROM records_2 WHERE date = '$date'");
                            $totalInHalls = array();
                            for ($i = 1; $i <= $num_halls; $i++) {
                                $result = mysqli_query($conn, "SELECT * FROM records_2 WHERE date = '$date'");
                                $totalInHalls['hall_' . $i] = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $totalInHalls['hall_' . $i] += $row['hall_' . $i];
                                }
                            }
                            for ($i = 1; $i <= $num_halls; $i++) {
                                echo '<th>' . $totalInHalls['hall_' . $i] . '</th>';
                            }
                            ?>
                            <th></th>
                            <th><?php echo $totalCars; ?></th>
                            <th></th>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </main>
</body>

<!-- edit popup -->

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('.editBtn').on('click', function() {
            //hide edit span
            $(this).closest("tr").find(".editSpan").hide();

            //show edit input
            $(this).closest("tr").find(".editInput").show();

            //hide edit button
            $(this).closest("tr").find(".editBtn").hide();

            //hide delete button
            $(this).closest("tr").find(".deleteBtn").hide();

            //show save button
            $(this).closest("tr").find(".saveBtn").show();

            //show cancel button
            $(this).closest("tr").find(".cancelBtn").show();

        });

        $('.saveBtn').on('click', function() {
            $('#userData').css('opacity', '.5');

            var trObj = $(this).closest("tr");
            var ID = $(this).closest("tr").attr('id');
            var inputData = $(this).closest("tr").find(".editInput").serialize();
            $.ajax({
                type: 'POST',
                url: 'partials/editRecord.php',
                dataType: "json",
                data: 'action=edit&id=' + ID + '&' + inputData,
                success: function(response) {
                    if (response.status == 1) {
                        console.log(response)
                        trObj.find(".editSpan.name").text(response.data.name);
                        trObj.find(".editSpan.date").text(response.data.date);
                        for (let i = 1; i <= response.data.number_of_halls; i++) {
                            trObj.find(`.editSpan.hall_${i}`).text(response.data[`hall_${i}`]);
                        }

                        trObj.find(".editInput.name").val(response.data.name);
                        trObj.find(".editInput.date").val(response.data.date);
                        for (let i = 1; i <= response.data.number_of_halls; i++) {
                            trObj.find(`.editSpan.hall_${i}`).val(response.data[`hall_${i}`]);
                        }
                        trObj.find(".editInput").hide();
                        trObj.find(".editSpan").show();
                        trObj.find(".saveBtn").hide();
                        trObj.find(".cancelBtn").hide();
                        trObj.find(".editBtn").show();
                        trObj.find(".deleteBtn").show();
                        window.location.reload();
                    } else {
                        alert(response.msg);
                    }
                    $('#userData').css('opacity', '');
                }
            });
        });

        $('.cancelBtn').on('click', function() {
            //hide & show buttons
            $(this).closest("tr").find(".saveBtn").hide();
            $(this).closest("tr").find(".cancelBtn").hide();
            $(this).closest("tr").find(".confirmBtn").hide();
            $(this).closest("tr").find(".editBtn").show();
            $(this).closest("tr").find(".deleteBtn").show();

            //hide input and show values
            $(this).closest("tr").find(".editInput").hide();
            $(this).closest("tr").find(".editSpan").show();
        });

        $('.deleteBtn').on('click', function() {
            //hide edit & delete button
            $(this).closest("tr").find(".editBtn").hide();
            $(this).closest("tr").find(".deleteBtn").hide();

            //show confirm & cancel button
            $(this).closest("tr").find(".confirmBtn").show();
            $(this).closest("tr").find(".cancelBtn").show();
        });

        $('.confirmBtn').on('click', function() {
            $('#userData').css('opacity', '.5');

            var trObj = $(this).closest("tr");
            var ID = $(this).closest("tr").attr('id');
            $.ajax({
                type: 'POST',
                url: 'partials/deleteRecord.php',
                dataType: "json",
                data: 'action=delete&id=' + ID,
                success: function(response) {
                    if (response.status == 1) {
                        trObj.remove();
                        window.location.reload();
                    } else {
                        trObj.find(".confirmBtn").hide();
                        trObj.find(".cancelBtn").hide();
                        trObj.find(".editBtn").show();
                        trObj.find(".deleteBtn").show();
                        alert(response.msg);
                    }
                    $('#userData').css('opacity', '');
                }
            });
        });
    });
</script>

<script>
    let table = new DataTable('#example');

    function getCurrentDate() {
        const today = new Date();
        const year = today.getFullYear();
        let month = today.getMonth() + 1;
        let day = today.getDate();

        month = month < 10 ? '0' + month : month;
        day = day < 10 ? '0' + day : day;

        return `${year}-${month}-${day}`;
    }
</script>
<script src="functions.js"></script>
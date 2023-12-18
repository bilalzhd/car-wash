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
// $records = mysqli_query($conn, "SELECT * FROM records");
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
        // foreach ($hallValues as $value) {
        //     $query .= ", '$value'";
        // }
        // $query .= ")";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $submitted = true;
            $records = mysqli_query($conn, "SELECT * FROM records_2 WHERE date = '$today_date'");
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

                            $num_halls = !$no_records ? mysqli_fetch_assoc($records)['number_of_halls'] : mysqli_fetch_assoc(mysqli_query($conn, "SELECT count FROM halls"))['count'];

                            for ($i = 1; $i <= $num_halls; $i++) {
                                echo '<th>Hall ' . $i . '</th>';
                            }
                            ?>
                            <th>Date</th>
                            <th>Total</th>
                            <?php
                            $today_date = date("Y-m-d");
                            if ($role == 0 || ($role == 2 && $_GET['date'] >= $today_date)) {
                                echo '<th>Actions</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        mysqli_data_seek($records, 0);
                        // if (!$no_records) {
                            while ($record = mysqli_fetch_assoc($records)) {
                                $total = 0;
                                for ($i = 1; $i <= $record['number_of_halls']; $i++) {
                                    $total +=  $record['hall_' . $i];
                                };
                                $customer_id = $record['customer_id'];
                                $customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM customers WHERE id='$customer_id'"));
                                if ($customer && isset($customer['name'])) {
                                    $customer_name = $customer['name'];
                                } else {
                                    $customer_name = $record['customer_name'];
                                } ?>
                                <tr id="<?php echo $record['id'] ?>" data-id="<?php echo $record['id'] ?>">
                                    <td>
                                        <span class="editSpan name"><?php echo $customer_name ?></span>
                                        <select name="edit_name" class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 editInput" id="edit_name" required style="display: none">
                                            <?php
                                            $customers_query = mysqli_query($conn, "SELECT * FROM customers");
                                            while ($cus = mysqli_fetch_assoc($customers_query)) { ?>
                                                <option <?php echo $customer_id == $cus['id'] ? "selected" : "" ?> value="<?php echo $cus['id'] ?>"><?php echo $cus['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <?php
                                    for ($i = 1; $i <= $num_halls; $i++) {
                                        $hall = !$no_records ? $record['hall_' . $i] : 0;
                                        echo '<td>
                                                <input type="hidden" name="number_of_halls" class="editInput" value="' . $record['number_of_halls'] . '">
                                                <span class="editSpan hall_' . $i . '">' . $hall . '</span>
                                                <input class="editInput block w-full px-4 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="number" name="hall_' . $i . '" id="hall_' . $i . '" required style="display: none" value="' . $hall . '">
                                                </td>';
                                    }
                                    if($role == 2 && $record['date'] >= $today_date) {
                                        echo '<td>
                                        <span class="editSpan date">' . $record['date'] . '</span>
                                        <input class="editInput px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="date" style="display: none" value="' . $record['date'] . '" disabled id="edit_date">
                                        <input type="hidden" class="editInput" name="edit_date" value="' . $record['date'] . '">
                                        </td>';
                                    } else {
                                    echo '<td>
                                    <span class="editSpan date">' . $record['date'] . '</span>
                                    <input class="editInput px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="date" name="edit_date" id="edit_date" required value="' . $record['date'] . '" style="display: none">
                                    </td>';
                                };

                                    echo '<td>' . $total . '</td>';
                                    if ($role == 0 || ($role == 2 && $record['date'] >= $today_date)) {
                                        echo '
                                <td class="w-1/4 flex whitespace-nowrap">
                                <div class="flex space-x-2">
                                <button data-id="' . $record['id'] . '" class="editBtn flex items-center hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg">Edit</button>
                                <button data-id="' . $record['id'] . '" class="deleteBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg">Delete</button>
                                <button data-id="' . $record['id'] . '" class="saveBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Save</button>
                                <button data-id="' . $record['id'] . '" class="confirmBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Confirm</button>
                                <button data-id="' . $record['id'] . '" class="cancelBtn flex hover:bg-indigo-700 transition-all duration-300 bg-gray-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Cancel</button>
                                </div>
                                </td>
                                </tr>';
                                    }
                                }
                            // } else {
                            //     $customers = mysqli_query($conn, "SELECT * FROM customers");
                            //     while ($customer = mysqli_fetch_assoc($customers)) {    ?>
                            <!--     <tr>
                            //         <td>
                            //             <span class="editSpan name"><?php echo $customer['name'] ?></span>
                            //             <select name="edit_name" class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 editInput" id="edit_name" required style="display: none">
                            //                 <?php
                            //                 $customers_query = mysqli_query($conn, "SELECT * FROM customers");
                            //                 while ($cus = mysqli_fetch_assoc($customers_query)) { ?>
                            //                     <option <?php 
                            // echo (isset($customer_id) && $customer_id == $cus['id']) ? "selected" : "" ?> value="<?php echo $cus['id'] ?>"><?php // echo $cus['name'] ?></option>
                            //                 <?php // } ?>
                            //             </select>
                            //         </td>
                             <?php
                            //         for ($i = 1; $i <= $num_halls; $i++) {
                            //             $hall = !$no_records  ? $record['hall_' . $i] : $i;
                            //             echo '<td>
                            //                     <input type="hidden" name="number_of_halls" class="editInput" value="' . $num_halls . '">
                            //                     <span class="editSpan hall_' . $i . '">' . 0 . '</span>
                            //                     <input class="editInput block w-full px-4 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="number" name="hall_' . $i . '" id="hall_' . $i . '" required style="display: none" value="0">
                            //                     </td>';
                            //         }
                            //         echo '<td>
                            //                 <span class="editSpan date">' . $_GET['date'] . '</span>
                            //                 <input class="editInput px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="date" name="edit_date" id="edit_date" required value="' . $_GET['date'] . '" style="display: none">
                            //                 </td>
                            //                 <td>' . $total . '</td>';
                            //         if ($role == 0 || ($role == 2 && $_GET['date'] >= $today_date)) {
                            //             echo '
                            //                 <td class="w-1/4 flex whitespace-nowrap">
                            //                 <div class="flex space-x-2">
                            //                 <button data-id="' . (isset($record) ? $record['id'] : 0) . '" class="editBtn flex items-center hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg">Edit</button>
                            //                 <button data-id="' . (isset($record) ? $record['id'] : 0)  . '" class="deleteBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg">Delete</button>
                            //                 <button data-id="' . (isset($record) ? $record['id'] : 0 ) . '" class="saveBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Save</button>
                            //                 <button data-id="' . (isset($record) ? $record['id'] : 0 ) . '" class="confirmBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Confirm</button>
                            //                 <button data-id="' . (isset($record) ? $record['id'] : 0 ) . '" class="cancelBtn flex hover:bg-indigo-700 transition-all duration-300 bg-gray-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Cancel</button>
                            //                 </div>
                            //             </td>
                            //         </tr>';
                            //         }
                            //     }
                            // }
                            // ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <?php
                            $today_date = $_GET['date'];
                            $records = mysqli_query($conn, "SELECT * FROM records_2 WHERE date = '$today_date'");
                            // $num_halls = mysqli_fetch_assoc($records)['number_of_halls'];
                            $totalInHalls = array();
                            for ($i = 1; $i <= $num_halls; $i++) {
                                $result = mysqli_query($conn, "SELECT * FROM records_2 WHERE date = '$today_date'");
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
<!-- <script>
        let minDate, maxDate;

        // Custom filtering function which will search data in column four between two values
        DataTable.ext.search.push(function(settings, data, dataIndex) {
            let min = minDate.val();
            let max = maxDate.val();
            let date = new Date(data[4]);

            if (
                (min === null && max === null) ||
                (min === null && date <= max) ||
                (min <= date && max === null) ||
                (min <= date && date <= max)
            ) {
                return true;
            }
            return false;
        });

        // Create date inputs
        minDate = new DateTime('#min', {
            format: 'MMMM Do YYYY'
        });
        maxDate = new DateTime('#max', {
            format: 'MMMM Do YYYY'
        });

        // DataTables initialisation
        
        // Refilter the table
        document.querySelectorAll('#min, #max').forEach((el) => {
            el.addEventListener('change', () => table.draw());
        });
    </script> -->
<script>
    let table = new DataTable('#example');
    // Get the current date in the format "YYYY-MM-DD"
    function getCurrentDate() {
        const today = new Date();
        const year = today.getFullYear();
        let month = today.getMonth() + 1;
        let day = today.getDate();

        // Ensure month and day are always two digits
        month = month < 10 ? '0' + month : month;
        day = day < 10 ? '0' + day : day;

        return `${year}-${month}-${day}`;
    }

    // Set the default value for the date input
    // document.getElementById('min').value = "<?php echo $_GET['date'] ?>";
    // document.getElementById('max').value = "<?php echo $_GET['date'] ?>";
</script>
<script src="functions.js"></script>

<?php 
    if($no_records) {
        $date = $_GET['date'];
        $customers_query = mysqli_query($conn, "SELECT * FROM customers");
        while ($customer = mysqli_fetch_assoc($customers_query)) {
            $customer_id = $customer['id'];
            $customer_name = $customer['name'];
            $result = mysqli_query($conn, "INSERT INTO `records_2` (`hall_1`, `hall_2`, `hall_3`, `hall_4`, `hall_5`, `hall_6`, `hall_7`, `hall_8`, `hall_9`, `hall_10`, `number_of_halls`, `customer_id`, `customer_name`, `date`) VALUES ('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '$num_halls', '$customer_id', '$customer_name', '$date');");
        }
        echo "<script>window.location.reload()</script>";
    }
?>


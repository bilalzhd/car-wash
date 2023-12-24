<?php
session_start();
$page_title = "Add New Customer";
$title = "Add New Customer";
include 'partials/head.php';
include 'partials/db_connect.php'; ?>


<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$role = $_SESSION['role'];

if ($role != 0) {
    header("Location: dashboard.php");
    exit();
}

?>
<?php
$submitted = false;
$error = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $date = date("Y-m-d");
    $query = mysqli_query($conn, "INSERT INTO customers (name, phone) VALUES ('$name', '$phone')");
    $customer_id = mysqli_insert_id($conn);
    $num_halls = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count FROM halls"))['count'];
    $records_query = "INSERT INTO `records_2` (`hall_1`, `hall_2`, `hall_3`, `hall_4`, `hall_5`, `hall_6`, `hall_7`, `hall_8`, `hall_9`, `hall_10`, `number_of_halls`, `customer_id`, `customer_name`, `date`) VALUES ('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '$num_halls', '$customer_id', '$name', '$date'";
    $records_result = mysqli_query($conn, $records_query);
    if ($query) {
        $submitted = true;
    } else {
        $error = true;
    }
}
$users = mysqli_query($conn, "SELECT * FROM customers ORDER BY id ASC");
// if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["edit_user"]))) {
//     $name = $_POST['edit_name'];
//     $phone = $_POST['edit_phone'];
//     $id = $_POST['user_id'];


//     $query = mysqli_query($conn, "UPDATE customers SET name = '$name', phone = '$phone' WHERE id = '$id'");
//     if ($query) {
//         $submitted = true;
//         $users = mysqli_query($conn, "SELECT * FROM customers ORDER BY id ASC");
//     } else {
//         $error = true;
//     }
// }
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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


<?php include("./partials/header.php") ?>

<?php if ($submitted || $error) { ?>
    <div class="<?php echo $submitted ? 'bg-green-500' : 'bg-red-500' ?> alert rounded-b text-white px-4 py-3 shadow-md text-xl" role="alert">
        <div class="flex items-center">
            <div class="py-1 rounded-full border-2 p-1 border-white">
                <?php echo $submitted ? ('<svg height="18px" version="1.1" viewBox="0 0 18 15" width="18px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#fff" id="Core" transform="translate(-423.000000, -47.000000)"><g id="check" transform="translate(423.000000, 47.500000)"><path d="M6,10.2 L1.8,6 L0.4,7.4 L6,13 L18,1 L16.6,-0.4 L6,10.2 Z" id="Shape"/></g></g></g></svg>') : ('<svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 384 512"><path fill="#fff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>')
                ?>
            </div>
            <div>
                <p class="mx-4"><?php echo $error ? "There was an error while adding the customer, try again later, or contact your developer." : "Customer has been added successfully!" ?></p>
            </div>
        </div>
    </div>
<?php } ?>

<body class="bg-gray-100 pb-10">
    <form class="py-6 px-5 md:px-0" method="POST" action="?">
        <div class="max-w-5xl mx-auto border-gray-900/10 pb-12">
            <div class="mt-10 md:flex justify-center items-center gap-x-6 gap-y-4">
                <div class="mb-4 md:mb-0">
                    <label for="name" class="block text-xl font-medium leading-6 text-gray-900">Customer Name</label>
                    <div class="mt-2">
                        <input class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="text" name="name" id="name" placeholder="Enter name" required>

                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-xl font-medium leading-6 text-gray-900">Phone Number</label>
                    <div class="mt-2">
                        <input class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="text" name="phone" id="phone" placeholder="Enter phone number" required>
                    </div>
                </div>
                <div class="sm:col-span-12 flex items-end">
                    <button name="add_user" class="mt-[27.5px] hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg">Add Customer</button>
                </div>
            </div>
        </div>
    </form>
    <div class="bg-white container shadow-lg rounded-md p-5 md:p-10 max-w-[60rem] mx-auto">
        <h1 class="text-3xl md:text-4xl text-center font-bold mb-4">Customers List</h1>
        <div class="overflow-x-auto">
            <table id="example" class="display" style="width:100%">
                <thead class="text-white bg-indigo-500">
                    <tr>
                        <th>Customer name</th>
                        <th>Phone number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="customer-data">
                    <?php
                    while ($user = mysqli_fetch_assoc($users)) {
                        echo '<tr id="' . $user['id'] . '" data-id="' . $user['id'] . '">
                            <td class="w-1/3">
                                <span class="editSpan name">' . $user['name'] . '</span>
                                <input class="editInput px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="text" name="edit_name" id="edit_name" placeholder="Enter name" required style="display: none" value="' . $user['name'] . '">
                            </td>
                            <td class="w-1/3">
                            <span class="editSpan phone">' . $user['phone'] . '</span>
                            <input class="editInput px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="text" name="edit_phone" id="edit_phone" value="' . $user['phone'] . '" required style="display: none">
                            </td>
                            <td class="w-1/3 flex whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <button data-id="' . $user['id'] . '" class="editBtn flex items-center hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg">Edit</button>
                                    <button data-id="' . $user['id'] . '" class="deleteBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg">Delete</button>
                                    <button data-id="' . $user['id'] . '" class="saveBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Save</button>
                                    <button data-id="' . $user['id'] . '" class="confirmBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Confirm</button>
                                    <button data-id="' . $user['id'] . '" class="cancelBtn flex hover:bg-indigo-700 transition-all duration-300 bg-gray-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Cancel</button>
                            
                                </div>
                            </td>
                        </tr>';
                    } ?>
                </tbody>

            </table>
        </div>
    </div>


</body>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script defer src="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"></script>
<script>
    let table = new DataTable('#example');
</script>
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
                url: 'partials/editUser.php',
                dataType: "json",
                data: 'action=edit&id=' + ID + '&' + inputData,
                success: function(response) {
                    if (response.status == 1) {
                        trObj.find(".editSpan.name").text(response.data.edit_name);
                        trObj.find(".editSpan.phone").text(response.data.edit_phone);
                         
                        trObj.find(".editInput.first_name").val(response.data.edit_name);
                        trObj.find(".editInput.last_name").val(response.data.edit_phone);

                        trObj.find(".editInput").hide();
                        trObj.find(".editSpan").show();
                        trObj.find(".saveBtn").hide();
                        trObj.find(".cancelBtn").hide();
                        trObj.find(".editBtn").show();
                        trObj.find(".deleteBtn").show();
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
                url: 'partials/deleteCustomer.php',
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
<script src="functions.js"></script>
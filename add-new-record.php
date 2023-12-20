<?php

session_start();
$manager = false;
$page_title = "Add New Record";
$title = "Add New Record";
include 'partials/head.php';
include 'partials/db_connect.php'; ?>
<?php if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$role = $_SESSION['role'];

if ($role == 1) {
    if (!headers_sent()) {
        header("Location: dashboard.php");
        exit();
    }
}
if ($role == 2) {
    $manager = true;
}
if (isset($_GET['date']) && ($_GET['date'] != date("Y-m-d")) && $manager) {
    header("Location: add-new-record.php?date=" . date("Y-m-d"));
}
$customers = mysqli_query($conn, "SELECT * FROM customers");
$number_of_halls = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count from halls"))['count'];
$submitted = false;
$error = false;
$already_recorded_error = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $customer = $_POST['customer'];
    $customer_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM customers WHERE id = '$customer'"))['name'];
    $number_of_halls = $_POST['number_of_halls'];

    $hallValues = [];
    for ($i = 1; $i <= $number_of_halls; $i++) {
        $hallValues[] = $_POST["hall_$i"];
    }

    $entryAlreadyRecordedQuery = mysqli_query($conn, "SELECT * FROM records_2 WHERE customer_id = '$customer' AND date = '$date'");
    $daysTotal = mysqli_query($conn, "SELECT * FROM records_2 WHERE date = '$date'");
    $entryAlreadyRecorded = mysqli_num_rows($entryAlreadyRecordedQuery);
    $entryAlreadyRecordedTotal = 0;
    $overallTotal = 0;
    $entry = mysqli_fetch_assoc($entryAlreadyRecordedQuery);
    if($entryAlreadyRecorded) {
        for($i = 1; $i < $number_of_halls; $i++) {
            $entryAlreadyRecordedTotal += $entry['hall_'.$i];
        }
    }

    if ($entryAlreadyRecorded || $entryAlreadyRecordedTotal < 1) {
        while ($entryTotal = mysqli_fetch_assoc($daysTotal)) {
            for ($i = 1; $i <= $entryTotal['number_of_halls']; $i++) {
                $overallTotal += $entryTotal['hall_' . $i];
            }
        }
    }



    if (!$entryAlreadyRecorded) {
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
            $submitted = true;
        } else {
            $error = true;
        }
        if ($overallTotal < 1 && $date >= date("Y-m-d")) {
            $customers = mysqli_query($conn, "SELECT * FROM customers WHERE id != '$customer'");
            while ($customer = mysqli_fetch_assoc($customers)) {
                $customer_id = $customer['id'];
                $customer_name = $customer['name'];
                $other_query = mysqli_query($conn, "INSERT INTO `records_2` (`hall_1`, `hall_2`, `hall_3`, `hall_4`, `hall_5`, `hall_6`, `hall_7`, `hall_8`, `hall_9`, `hall_10`, `number_of_halls`, `customer_id`, `customer_name`, `date`) VALUES ('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '$number_of_halls', '$customer_id', '$customer_name', '$date')");
            }
        }
    } else if ($entryAlreadyRecorded && $entryAlreadyRecordedTotal == 0) {
        $id = $entry['id'];
        $query = "UPDATE records_2 SET ";
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
        }
    } else {
        $already_recorded_error = true;
    }
}
?>
<?php include("./partials/header.php") ?>

<?php if ($submitted || $error || $already_recorded_error) { ?>
    <div class="<?php echo $submitted ? 'bg-green-500' : 'bg-red-500' ?> rounded-b text-white px-4 py-3 shadow-md text-xl" role="alert">
        <div class="flex items-center">
            <div class="py-1 rounded-full border-2 p-1 border-white">
                <?php echo $submitted ? ('<svg height="18px" version="1.1" viewBox="0 0 18 15" width="18px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#fff" id="Core" transform="translate(-423.000000, -47.000000)"><g id="check" transform="translate(423.000000, 47.500000)"><path d="M6,10.2 L1.8,6 L0.4,7.4 L6,13 L18,1 L16.6,-0.4 L6,10.2 Z" id="Shape"/></g></g></g></svg>') : ('<svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 384 512"><path fill="#fff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>')
                ?>
            </div>
            <div>
                <p class="mx-4"><?php
                                if ($error) {
                                    echo "There was an error while adding the customer, try again later, or contact your developer.";
                                } else if ($already_recorded_error) {
                                    echo "There is already an entry recorded for the customer on this date, try editing from the table.";
                                } else {
                                    echo "Your record has been added successfully!";
                                } ?>
                </p>
            </div>
        </div>
    </div>
<?php } ?>

<body>
    <form method="POST" action="?" class="overflow-y-auto">
        <div class="pt-6 pb-10 space-y-12">
            <div class="border-b border-gray-900/10 pb-12">

                <div class="border-b max-w-lg mx-auto border-gray-900/10 pb-12">

                    <div class="mt-10 grid gap-x-6 gap-y-4 px-4">
                        <div class="sm:col-span-12">
                            <label for="first-name" class="block text-xl font-m um leading-6 text-gray-900">Date</label>
                            <div class="mt-2">
                                <input type="date" name="date" id="birthdate" autocomplete="given-name" class="block w-full px-4 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                                <input type="hidden" name="date" id="hidden-date" disabled>
                            </div>
                        </div>
                        <div class="sm:col-span-12">
                            <label for="email" class="block text-xl font-medium leading-6 text-gray-900">Customer</label>
                            <div class="mt-2">
                                <select id="customer" name="customer" class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                                    <option value="" disabled selected>Select customer</option>
                                    <?php
                                    while ($customer = mysqli_fetch_assoc($customers)) {
                                        echo '<option value="' . $customer['id'] . '">' . $customer['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="number_of_halls" value="<?php echo $number_of_halls; ?>" id="number_of_halls">
                        <?php
                        for ($i = 1; $i <= $number_of_halls; $i++) {
                            echo '<div class="sm:col-span-6">
                            <label for="hall_' . $i . '" class="block text-xl font-medium leading-6 text-gray-900">Hall ' . $i . '</label>
                            <div class="mt-2">
                                <input class="block w-full px-4 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="number" name="hall_' . $i . '" id="hall_' . $i . '"  placeholder="Number of cars">
                            </div>
                        </div>';
                        };
                        ?>

                    </div>
                    <div class="sm:col-span-12 mt-4">
                        <button class="hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg">Submit</button>
                    </div>

                </div>
            </div>

        </div>

        </div>
    </form>
    <script>
        function getCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            let month = today.getMonth() + 1;
            let day = today.getDate();

            month = month < 10 ? "0" + month : month;
            day = day < 10 ? "0" + day : day;

            return `${year}-${month}-${day}`;
        }
    </script>
    <?php
    if (!isset($_GET['date']) && !$manager) {
        echo '<script>
        // Set the default value for the date input
        document.getElementById("birthdate").value = getCurrentDate();
    </script>';
    } else {
        $date = date("Y-m-d", strtotime($_GET['date']));
        echo '<script>
            const today_date = new Date("' . date("Y-m-d") . '");
            document.getElementById("birthdate").value = today_date.toISOString().split("T")[0];
        </script>';
    }

    if ($manager) {
        echo '<script>
        dateInput = document.getElementById("birthdate")
        hiddenDate = document.getElementById("hidden-date")
            dateInput.value = "' . date("Y-m-d") . '";
            dateInput.disabled = true;
            hiddenDate.disabled = false;
            hiddenDate.value = "' . date("Y-m-d") . '"
            </script>';
    }
    ?>

</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="functions.js"></script>
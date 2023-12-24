<?php
session_start();
$page_title = "Add New Hall Entry";
$title = "Add New Hall Entry";
$role = $_SESSION['role'];
include("./partials/head.php");
include("./partials/header.php");
include("./partials/db_connect.php");
$submitted = false;
$error = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_halls = $_POST['number_of_halls'];
    $date = $_POST['date'];

    $query = mysqli_query($conn, "SELECT count FROM halls WHERE date = '$date'");
    if (mysqli_num_rows($query) < 1) {
        $result = mysqli_query($conn, "INSERT INTO halls (count, date) VALUES ('$num_halls', '$date')");
        if ($result) {
            $submitted = true;
        } else {
            $error = true;
        }
    }
}
?>
<?php
if ($submitted || $error) { ?>
    <div class="<?php echo $submitted ? 'bg-green-500' : 'bg-red-500' ?> alert rounded-b text-white px-4 py-3 shadow-md text-xl" role="alert">
        <div class="flex items-center">
            <div class="py-1 rounded-full border-2 p-1 border-white">
                <?php echo $submitted ? ('<svg height="18px" version="1.1" viewBox="0 0 18 15" width="18px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#fff" id="Core" transform="translate(-423.000000, -47.000000)"><g id="check" transform="translate(423.000000, 47.500000)"><path d="M6,10.2 L1.8,6 L0.4,7.4 L6,13 L18,1 L16.6,-0.4 L6,10.2 Z" id="Shape"/></g></g></g></svg>') : ('<svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 384 512"><path fill="#fff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>')
                ?>
            </div>
            <div>
                <p class="mx-4"><?php echo $error ? "You already have changed the halls for the date try updating from the table." : "Halls has been updated for the date successfully!" ?></p>
            </div>
        </div>
    </div>
<?php } ?>
<form method="POST" action="?" class="overflow-y-auto max-w-md mx-auto">
    <input type="hidden" id="user_id" name="user_id">
    <div class="max-w-5xl mx-auto border-gray-900/10 pb-0 md:pb-12">
        <div class="mt-10 gap-x-6 space-y-6">
            <div>
                <label for="name" class="block text-xl font-medium leading-6 text-gray-900">Number of Halls</label>
                <div class="mt-2">
                    <input class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="number" min="1" max="10" name="number_of_halls" id="number_of_halls" placeholder="Enter number of halls" required>

                </div>
            </div>
            <div>
                <label for="phone" class="block text-xl font-medium leading-6 text-gray-900">For Date</label>
                <div class="mt-2">
                    <input class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="date" name="date" id="date" required>
                </div>
            </div>
            <button class="hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg" type="submit">Submit</button>
        </div>
    </div>
</form>
<script>
    function getCurrentDate() {
        const today = new Date();
        const year = today.getFullYear();
        let month = today.getMonth() + 1;
        let day = today.getDate();

        month = month < 10 ? '0' + month : month;
        day = day < 10 ? '0' + day : day;

        return `${year}-${month}-${day}`;
    }

    const todayDate = getCurrentDate();
    document.getElementById('date').value = todayDate
</script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    if ($(".alert")) {
        $(".alert").fadeOut(5000);
    }
</script>
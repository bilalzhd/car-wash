<?php

$title = "Home";
include "partials/head.php";
include_once "./partials/db_connect.php";
session_start();
$error = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $checkUser = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND password = '$password'");

  if (mysqli_num_rows($checkUser) > 0) {
    // Fetch user data
    $userData = mysqli_fetch_assoc($checkUser);

    // Assign fetched values to variables
    $id = $userData['id']; // Assuming 'id' is the column name for user ID
    $fetchedUsername = $userData['username'];
    $role = $userData['role'];

    // Set session variables
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $fetchedUsername;
    $_SESSION['role'] = $role;

    echo "User Logged In";
    header("Location: ./dashboard.php");
    exit();
  } else {
    $error = true;
  }
}

?>

<body>
  <?php
  if ($error) { ?>
    <div class="bg-red-500 alert rounded-b text-white px-4 py-3 shadow-md text-xl" role="alert">
      <div class="flex items-center">
        <div class="py-1 rounded-full border-2 p-1 border-white">
          <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 384 512"><path fill="#fff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
        </div>
        <div>
          <p class="mx-4">Wrong Credentials</p>
        </div>
      </div>
    </div>
  <?php } ?>
  <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Log in to your account</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" action="" method="POST">
        <div>
          <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
          <div class="mt-2">
            <input id="username" name="username" type="text" required class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>

          </div>
          <div class="mt-2">
            <input id="password" name="password" type="password" required class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
          </div>
        </div>

        <div>
          <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Log in</button>
        </div>
      </form>


    </div>
  </div>


</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="functions.js"></script>
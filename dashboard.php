<?php
session_start();
$title = "Dashboard";
$page_title = "Dashboard";
include "partials/head.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit(); 
}
$role = $_SESSION['role'];
?>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
<link rel="stylesheet" href="style.css">
<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .calendar-container {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        max-width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 1rem;
    }

    .day-button {
        border: 1px solid gray;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .day-button:hover {
        background-color: #f0f0f0;
    }
</style>
<?php include("./partials/header.php") ?>
<body>
    <div class="min-h-full">
        <main>
            <div class="mx-auto max-w-5xl py-6 sm:px-6 lg:px-8">

                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
                </head>

                <body>
                    <div class="container">
                        <div class="calendar">
                            <div class="header">
                                <div class="month"></div>
                                <div class="btns">
                                    <div class="btn today-btn">
                                        <i class="fas fa-calendar-day"></i>
                                    </div>
                                    <div class="btn prev-btn">
                                        <i class="fas fa-chevron-left"></i>
                                    </div>
                                    <div class="btn next-btn">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="weekdays">
                                <div class="day">Sun</div>
                                <div class="day">Mon</div>
                                <div class="day">Tue</div>
                                <div class="day">Wed</div>
                                <div class="day">Thu</div>
                                <div class="day">Fri</div>
                                <div class="day">Sat</div>
                            </div>
                            <div class="days">
                                <!-- lets add days using js -->
                            </div>
                        </div>
                    </div>
                    <script src="script.js"></script>
                </body>

                </html>
            </div>
        </main>
    </div>

</body>

<script src="functions.js"></script>
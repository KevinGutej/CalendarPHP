<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calendar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .calendar-container {
            width: 80%;
            max-width: 800px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .calendar-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        td {
            background-color: #fff;
            vertical-align: top;
            position: relative;
            cursor: pointer;
        }
        .today {
            background-color: #ffeb3b;
        }
        .event {
            background-color: #ffecb3;
        }
        .navigation {
            text-align: center;
            margin-bottom: 20px;
        }
        .navigation select {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin: 0 10px;
        }
        .navigation button {
            padding: 10px 15px;
            border: none;
            background-color: #333;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .navigation button:hover {
            background-color: #555;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
        .add-event-form {
            margin-top: 20px;
        }
        .add-event-form input,
        .add-event-form textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .add-event-form button {
            padding: 10px 15px;
            border: none;
            background-color: #333;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .add-event-form button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="calendar-container">
        <h1>PHP Calendar</h1>
        <div class="navigation">
            <?php
            $currentDate = getdate();
            $month = isset($_GET['month']) ? $_GET['month'] : $currentDate['mon'];
            $year = isset($_GET['year']) ? $_GET['year'] : $currentDate['year'];
            $prevMonth = $month - 1;
            $nextMonth = $month + 1;
            $prevYear = $year;
            $nextYear = $year;

            if ($prevMonth == 0) {
                $prevMonth = 12;
                $prevYear--;
            }

            if ($nextMonth == 13) {
                $nextMonth = 1;
                $nextYear++;
            }

            echo '<button onclick="navigateToMonth(' . $prevMonth . ', ' . $prevYear . ')">&laquo; Previous</button>';
            echo '<select id="month" onchange="changeDate()">';
            for ($m = 1; $m <= 12; $m++) {
                $selected = ($m == $month) ? 'selected' : '';
                echo '<option value="' . $m . '" ' . $selected . '>' . date('F', mktime(0, 0, 0, $m, 10)) . '</option>';
            }
            echo '</select>';
            echo '<select id="year" onchange="changeDate()">';
            for ($y = 1970; $y <= 2100; $y++) {
                $selected = ($y == $year) ? 'selected' : '';
                echo '<option value="' . $y . '" ' . $selected . '>' . $y . '</option>';
            }
            echo '</select>';
            echo '<button onclick="navigateToMonth(' . $nextMonth . ', ' . $nextYear . ')">Next &raquo;</button>';
            ?>
        </div>
        <?php include 'calendar.php'; ?>
        <div class="add-event-form">
            <h2>Add New Event</h2>
            <form action="add_event.php" method="post">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
                <label for="title">Event Title:</label>
                <input type="text" id="title" name="title" required>
                <label for="description">Event Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
                <button type="submit">Add Event</button>
            </form>
        </div>
    </div>
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Event Details</h2>
            <p id="eventDetails"></p>
        </div>
    </div>
    <script>
        function navigateToMonth(month, year) {
            window.location.href = `?month=${month}&year=${year}`;
        }
        function changeDate() {
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;
            navigateToMonth(month, year);
        }
        function showModal(eventDetails) {
            document.getElementById('eventDetails').innerText = eventDetails;
            document.getElementById('eventModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('eventModal').style.display = 'none';
        }
    </script>
</body>
</html>

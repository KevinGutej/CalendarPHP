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
            max-width: 600px;
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
        }
        .today {
            background-color: #ffeb3b;
        }
        .event {
            background-color: #ffecb3;
            position: relative;
        }
        .event::after {
            content: '';
            display: block;
            width: 8px;
            height: 8px;
            background: #f44336;
            border-radius: 50%;
            position: absolute;
            top: 5px;
            right: 5px;
        }
        .navigation {
            text-align: center;
            margin-bottom: 20px;
        }
        .navigation a {
            text-decoration: none;
            color: #333;
            background-color: #e0e0e0;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .navigation a:hover {
            background-color: #d5d5d5;
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

            echo '<a href="?month=' . $prevMonth . '&year=' . $prevYear . '">&laquo; Previous</a>';
            echo ' | ';
            echo '<a href="?month=' . $nextMonth . '&year=' . $nextYear . '">Next &raquo;</a>';
            ?>
        </div>
        <?php include 'calendar.php'; ?>
    </div>
</body>
</html>

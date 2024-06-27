<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calendar</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .today {
            background-color: #ff0;
        }
        .event {
            background-color: #0f0;
        }
        .navigation {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
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
</body>
</html>

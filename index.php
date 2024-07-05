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
        .add-event-form select,
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
        .edit-event-form {
            margin-top: 20px;
        }
        .edit-event-form input,
        .edit-event-form select,
        .edit-event-form textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .edit-event-form button {
            padding: 10px 15px;
            border: none;
            background-color: #333;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .edit-event-form button:hover {
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
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="Work">Work</option>
                    <option value="Personal">Personal</option>
                    <option value="Others">Others</option>
                </select>
                <label for="recurrence">Recurrence:</label>
                <select id="recurrence" name="recurrence" required>
                    <option value="None">None</option>
                    <option value="Daily">Daily</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                    <option value="Yearly">Yearly</option>
                </select>
                <button type="submit">Add Event</button>
            </form>
        </div>
        <div id="editEventForm" class="edit-event-form" style="display: none;">
            <h2>Edit Event</h2>
            <form action="edit_event.php" method="post">
                <input type="hidden" id="editDate" name="date">
                <label for="editTitle">Event Title:</label>
                <input type="text" id="editTitle" name="title" required>
                <label for="editDescription">Event Description:</label>
                <textarea id="editDescription" name="description" rows="4" required></textarea>
                <label for="editCategory">Category:</label>
                <select id="editCategory" name="category" required>
                    <option value="Work">Work</option>
                    <option value="Personal">Personal</option>
                    <option value="Others">Others</option>
                </select>
                <label for="editRecurrence">Recurrence:</label>
                <select id="editRecurrence" name="recurrence" required>
                    <option value="None">None</option>
                    <option value="Daily">Daily</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                    <option value="Yearly">Yearly</option>
                </select>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="eventTitle"></h2>
            <p id="eventDescription"></p>
            <p id="eventCategory"></p>
            <p id="eventRecurrence"></p>
            <button onclick="showEditForm()">Edit</button>
            <button onclick="deleteEvent()">Delete</button>
        </div>
    </div>
    <script>
        function changeDate() {
            var month = document.getElementById('month').value;
            var year = document.getElementById('year').value;
            window.location.href = `index.php?month=${month}&year=${year}`;
        }

        function navigateToMonth(month, year) {
            window.location.href = `index.php?month=${month}&year=${year}`;
        }

        function showModal(eventTitle, eventDescription, eventCategory, eventRecurrence, eventDate) {
            document.getElementById('eventTitle').innerText = eventTitle;
            document.getElementById('eventDescription').innerText = eventDescription;
            document.getElementById('eventCategory').innerText = 'Category: ' + eventCategory;
            document.getElementById('eventRecurrence').innerText = 'Recurrence: ' + eventRecurrence;
            document.getElementById('editDate').value = eventDate;
            document.getElementById('editTitle').value = eventTitle;
            document.getElementById('editDescription').value = eventDescription;
            document.getElementById('editCategory').value = eventCategory;
            document.getElementById('editRecurrence').value = eventRecurrence;
            document.getElementById('eventModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('eventModal').style.display = 'none';
        }

        function showEditForm() {
            document.getElementById('editEventForm').style.display = 'block';
            closeModal();
        }

        function deleteEvent() {
            var date = document.getElementById('editDate').value;
            window.location.href = `delete_event.php?date=${date}`;
        }
    </script>
</body>
</html>

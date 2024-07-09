<?php
session_start();
$loggedIn = isset($_SESSION['username']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === 'password') {
        $_SESSION['username'] = $username;
        $loggedIn = true;
    } else {
        $error = 'Invalid username or password';
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
}

$currentDate = getdate();
$month = isset($_GET['month']) ? $_GET['month'] : $currentDate['mon'];
$year = isset($_GET['year']) ? $_GET['year'] : $currentDate['year'];
$view = isset($_GET['view']) ? $_GET['view'] : 'month';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filterCategory = isset($_GET['filterCategory']) ? $_GET['filterCategory'] : '';
$filterRecurrence = isset($_GET['filterRecurrence']) ? $_GET['filterRecurrence'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced PHP Calendar</title>
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
            max-width: 1000px;
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
        .work {
            background-color: #8bc34a;
        }
        .personal {
            background-color: #03a9f4;
        }
        .others {
            background-color: #ff9800;
        }
        .navigation {
            text-align: center;
            margin-bottom: 20px;
        }
        .navigation select, .navigation input {
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
        .login-form {
            text-align: center;
        }
        .login-form input {
            padding: 10px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .login-form button {
            padding: 10px 15px;
            border: none;
            background-color: #333;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-form button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="calendar-container">
        <h1>Advanced PHP Calendar</h1>
        <?php if (!$loggedIn): ?>
            <div class="login-form">
                <h2>Login</h2>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?= $error ?></p>
                <?php endif; ?>
                <form action="index.php" method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
            </div>
        <?php else: ?>
            <p>Welcome, <?= $_SESSION['username'] ?>! <a href="?logout">Logout</a></p>
            <div class="navigation">
                <?php
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
                for ($y = $year - 10; $y <= $year + 10; $y++) {
                    $selected = ($y == $year) ? 'selected' : '';
                    echo '<option value="' . $y . '" ' . $selected . '>' . $y . '</option>';
                }
                echo '</select>';
                echo '<button onclick="navigateToMonth(' . $nextMonth . ', ' . $nextYear . ')">Next &raquo;</button>';
                ?>
                <select id="view" onchange="changeView()">
                    <option value="month" <?= $view == 'month' ? 'selected' : '' ?>>Month</option>
                    <option value="week" <?= $view == 'week' ? 'selected' : '' ?>>Week</option>
                    <option value="day" <?= $view == 'day' ? 'selected' : '' ?>>Day</option>
                </select>
                <input type="text" id="search" placeholder="Search events..." value="<?= $search ?>" onkeyup="searchEvents()">
                <select id="filterCategory" onchange="filterEvents()">
                    <option value="">All Categories</option>
                    <option value="Work" <?= $filterCategory == 'Work' ? 'selected' : '' ?>>Work</option>
                    <option value="Personal" <?= $filterCategory == 'Personal' ? 'selected' : '' ?>>Personal</option>
                    <option value="Others" <?= $filterCategory == 'Others' ? 'selected' : '' ?>>Others</option>
                </select>
                <select id="filterRecurrence" onchange="filterEvents()">
                    <option value="">All Recurrences</option>
                    <option value="None" <?= $filterRecurrence == 'None' ? 'selected' : '' ?>>None</option>
                    <option value="Daily" <?= $filterRecurrence == 'Daily' ? 'selected' : '' ?>>Daily</option>
                    <option value="Weekly" <?= $filterRecurrence == 'Weekly' ? 'selected' : '' ?>>Weekly</option>
                    <option value="Monthly" <?= $filterRecurrence == 'Monthly' ? 'selected' : '' ?>>Monthly</option>
                    <option value="Yearly" <?= $filterRecurrence == 'Yearly' ? 'selected' : '' ?>>Yearly</option>
                </select>
            </div>
            <div id="calendar">
                <?php include 'calendar.php'; ?>
            </div>
            <div class="add-event-form">
                <h2>Add Event</h2>
                <form action="add_event.php" method="post">
                    <input type="date" name="date" required>
                    <input type="text" name="title" placeholder="Event Title" required>
                    <textarea name="description" placeholder="Event Description" required></textarea>
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
            <div id="editEventForm" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Edit Event</h2>
                    <form action="edit_event.php" method="post">
                        <input type="hidden" id="editDate" name="date" required>
                        <input type="text" id="editTitle" name="title" placeholder="Event Title" required>
                        <textarea id="editDescription" name="description" placeholder="Event Description" required></textarea>
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
        <?php endif; ?>
    </div>
    <script>
        function changeDate() {
            var month = document.getElementById('month').value;
            var year = document.getElementById('year').value;
            var view = document.getElementById('view').value;
            window.location.href = `index.php?month=${month}&year=${year}&view=${view}`;
        }

        function navigateToMonth(month, year) {
            var view = document.getElementById('view').value;
            window.location.href = `index.php?month=${month}&year=${year}&view=${view}`;
        }

        function changeView() {
            var month = document.getElementById('month').value;
            var year = document.getElementById('year').value;
            var view = document.getElementById('view').value;
            window.location.href = `index.php?month=${month}&year=${year}&view=${view}`;
        }

        function searchEvents() {
            var search = document.getElementById('search').value;
            var filterCategory = document.getElementById('filterCategory').value;
            var filterRecurrence = document.getElementById('filterRecurrence').value;
            var month = document.getElementById('month').value;
            var year = document.getElementById('year').value;
            var view = document.getElementById('view').value;
            window.location.href = `index.php?month=${month}&year=${year}&view=${view}&search=${search}&filterCategory=${filterCategory}&filterRecurrence=${filterRecurrence}`;
        }

        function filterEvents() {
            var filterCategory = document.getElementById('filterCategory').value;
            var filterRecurrence = document.getElementById('filterRecurrence').value;
            var search = document.getElementById('search').value;
            var month = document.getElementById('month').value;
            var year = document.getElementById('year').value;
            var view = document.getElementById('view').value;
            window.location.href = `index.php?month=${month}&year=${year}&view=${view}&search=${search}&filterCategory=${filterCategory}&filterRecurrence=${filterRecurrence}`;
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

        document.addEventListener('DOMContentLoaded', (event) => {
            const draggableElements = document.querySelectorAll('.event');
            draggableElements.forEach(element => {
                element.draggable = true;
                element.addEventListener('dragstart', handleDragStart);
                element.addEventListener('dragover', handleDragOver);
                element.addEventListener('drop', handleDrop);
            });
        });

        function handleDragStart(e) {
            e.dataTransfer.setData('text/plain', e.target.id);
        }

        function handleDragOver(e) {
            e.preventDefault();
        }

        function handleDrop(e) {
            e.preventDefault();
            const id = e.dataTransfer.getData('text/plain');
            const draggableElement = document.getElementById(id);
            const dropzone = e.target;
            dropzone.appendChild(draggableElement);
            const newDate = dropzone.getAttribute('data-date');
            const eventData = JSON.parse(localStorage.getItem(id));
            eventData.date = newDate;
            localStorage.setItem(id, JSON.stringify(eventData));
        }
    </script>
</body>
</html>

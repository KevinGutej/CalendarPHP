<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    $date = $_POST['date'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $recurrence = $_POST['recurrence'];

    $events = json_decode(file_get_contents('events.json'), true);

    $events[$date] = [
        'title' => $title,
        'description' => $description,
        'category' => $category,
        'recurrence' => $recurrence
    ];

    file_put_contents('events.json', json_encode($events));

    header('Location: index.php');
}
?>

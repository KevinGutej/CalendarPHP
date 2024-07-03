<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $events = json_decode(file_get_contents('events.json'), true);

    $events[$date] = [
        'title' => $title,
        'description' => $description
    ];

    file_put_contents('events.json', json_encode($events));

    header('Location: index.php');
}
?>

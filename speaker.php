<?php
require('config.php');
session_start();

if (mysqli_connect_error()) {
    exit('Error connecting to the database: ' . mysqli_connect_error());
}

if (!isset($_POST['partner_name'], $_POST['partner_description'], $_POST['contact_email'], $_POST['contact_phone'])) {
    exit('Empty Field(s)');
}

if (empty($_POST['partner_name']) || empty($_POST['partner_description']) || empty($_POST['contact_email']) || empty($_POST['contact_phone'])) {
    exit('Empty Field(s)');
}

if ($stmt = $con->prepare('SELECT partner_name, partner_description FROM partners WHERE partner_name = ?')) {
    $stmt->bind_param('s', $_POST['partner_name']);
    $stmt->execute();
    $stmt->store_result();
}

if ($stmt->num_rows > 0) {
    echo 'Partner already exists.';
} else {
    if ($stmt = $con->prepare('INSERT INTO partners (partner_name, partner_description, contact_email, contact_phone) VALUES (?, ?, ?, ?)')) {
        $stmt->bind_param('ssss', $_POST['partner_name'], $_POST['partner_description'], $_POST['contact_email'], $_POST['contact_phone']);
        $stmt->execute();
        echo 'Partner added';
    } else {
        echo 'Error occurred';
    }

    $stmt->close();
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speaker Registration Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="register">
        <h1>Speaker Registration Form</h1>
        <form action="Speakers.php" method="post">
            <h4>Nume Speaker</h4>
            <label for="speaker-name"></label>
            <input type="text" name="speaker_name" placeholder="speaker_name" id="speaker_name" required>
            <h4>Subiect</h4>
            <label for="speaker-topic"></label>
            <input type="text" name="speaker_topic" placeholder="speaker_topic" id="speaker_topic" required>
            <input type="submit" value="Înregistrează Speaker">
        </form>
    </div>
</body>

</html>
<?php
require('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obține notificările utilizatorului din baza de date
$query_notificari = "SELECT ID_notificare, mesaj FROM Notificari WHERE ID_user = ?";
$stmt_notificari = $conn->prepare($query_notificari);
$stmt_notificari->bind_param("i", $user_id);
$stmt_notificari->execute();
$stmt_notificari->bind_result($id_notificare, $mesaj);

?>

<!DOCTYPE html>
<html>

<head>
    <style>
        /* Stilizare pentru pagina de notificări */
    </style>
</head>

<body>
    <h2>Notificări</h2>

    <?php
    // Afișează notificările utilizatorului
    while ($stmt_notificari->fetch()) {
        echo "<div class='notificare'>";
        echo "<p>Mesaj: " . $mesaj . "</p>";
        echo "</div>";
    }
    ?>

    <a href="index.php">Înapoi la Pagina Principală</a>
</body>

</html>
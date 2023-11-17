<?php
require('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Verifica dacă a fost trimis formularul pentru ștergerea notificării
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sterge_notificare"])) {
    $notificare_id = $_POST["notificare_id"];

    // Realizează ștergerea din baza de date
    $query_sterge_notificare = "DELETE FROM Notificari WHERE ID_notificare = ?";
    $stmt_sterge_notificare = $conn->prepare($query_sterge_notificare);
    $stmt_sterge_notificare->bind_param("i", $notificare_id);
    $stmt_sterge_notificare->execute();
}

// Obține notificările utilizatorului din baza de date
$query_notificari = "SELECT ID_notificare, mesaj FROM Notificari WHERE ID_user = ? ORDER BY data_creare DESC";
$stmt_notificari = $conn->prepare($query_notificari);
$stmt_notificari->bind_param("i", $user_id);
$stmt_notificari->execute();
$stmt_notificari->bind_result($id_notificare, $mesaj);

?>

<!DOCTYPE html>
<html>

<head>
    <link href="notificari.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="title">
        <h1>Notificări</h1>
    </div>

    <?php
    // Afișează notificările utilizatorului
    while ($stmt_notificari->fetch()) {
        echo "<div class='notificare'>";
        echo "<p>  $mesaj </p>";

        // Adaugă formularul pentru ștergerea notificării
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='notificare_id' value='" . $id_notificare . "'>";
        echo "<input type='submit' name='sterge_notificare' value='Șterge'>";
        echo "</form>";

        echo "</div>";
    }
    ?>

    <a href="index.php" class="footer-link">Înapoi la pagina principală</a>
</body>

</html>
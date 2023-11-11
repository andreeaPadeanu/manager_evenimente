<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require('config.php');

$user_id = $_SESSION['user_id'];
$query = "SELECT Nume, Prenume FROM User WHERE ID_user = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nume, $prenume);
$stmt->fetch();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Pagina de Succes</title>
</head>

<body>
    <h2>Bun venit,
        <?php echo $nume . ' ' . $prenume; ?>!
    </h2>
    <p>Ați fost autentificat cu succes.</p>
    <a href="logout.php">Deconectare</a>
</body>

</html>
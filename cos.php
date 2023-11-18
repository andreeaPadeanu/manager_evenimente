<?php
require('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['adauga_cos']) && isset($_POST['eveniment_id'])) {
    $eveniment_id = $_POST['eveniment_id'];

    if (!isset($_SESSION['cos'])) {
        $_SESSION['cos'] = array();
    }

    $_SESSION['cos'][] = $eveniment_id;
}

?>

<!DOCTYPE html>
<html>

<head>
<link href="cos.css" rel="stylesheet" type="text/css">
    <title class="title">Cosul meu</title>
    <style>

    </style>
</head>

<body>
<header class="header">
        <h3 class="title">Eventica</h3>
        <div class="top-right-menu">
            <a href="index.php">Acasă</a>
            <a href="cos.php">Coș</a>
            <a href="notificari.php">Notificări</a>
            <a href="logout.php">Deconectare</a>
        </div>
    </header>
    <div class="container">
        <div class="content">
            <h2>Coșul tău</h2>
            <form method="post" action="achizitie.php">
                <ul>
                    <?php
                    if (isset($_SESSION['cos'])) {
                        foreach ($_SESSION['cos'] as $eveniment_id) {
                            // Obține informații despre eveniment din baza de date
                            $query = "SELECT Nume_eveniment, Data, Ora, Locatie, Descriere_eveniment, ID_tip_bilet
                                      FROM Eveniment
                                      WHERE ID_eveniment = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("i", $eveniment_id);
                            $stmt->execute();
                            $stmt->bind_result($nume_eveniment, $data, $ora, $locatie, $descriere, $id_tip_bilet);
                            $stmt->fetch();

                            echo "<li>";
                            echo "<h3>$nume_eveniment</h3>";
                            echo "<p>Data: $data, Ora: $ora</p>";
                            echo "<p>Locație: $locatie</p>";
                            echo "<p>Descriere: $descriere</p>";
                            echo "<p>Tip bilet: $id_tip_bilet</p>";
                            echo "<input type='number' name='cantitate_$eveniment_id' value='1'>";
                            echo "<input type='hidden' name='eveniment_id' value='$eveniment_id'>";
                            echo "<button type='submit' name='achizitie'>Achiziționează</button>";
                            echo "</li>";
                        }
                    } else {
                        echo "<p>Coșul tău este gol.</p>";
                    }
                    ?>
                </ul>
            </form>
        </div>
    </div>
    <br><br><br>
    <div class="button-container">
    <button class="custom-btn btn-7" onclick="redirectToIndex()"><span>Continua  cumparaturile</span></button>
    </div>
<script>
    function redirectToIndex() {
        window.location.href = "index.php";
    }
</script>
    <div class="footer">
        <p>&copy;2023 Eventica. Toate drepturile rezervate</p>
    </div>
</body>

</html>
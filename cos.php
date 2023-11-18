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
    <script>
        function updateCost(evenimentId) {
            var cantitate = document.getElementById('cantitate_' + evenimentId).value;
            var pret = document.getElementById('pret_' + evenimentId).value;
            var costEveniment = cantitate * pret;
            document.getElementById('cost_' + evenimentId).innerHTML = "Costul biletelor: " + costEveniment + " RON";
        }
    </script>
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
                    // Inițializează costul total
                    $cost_total = 0;

                    if (isset($_SESSION['cos'])) {
                        foreach ($_SESSION['cos'] as $eveniment_id) {
                            // Obține informații despre eveniment din baza de date
                            $query = "SELECT Nume_eveniment, Data, Ora, Locatie, Descriere_eveniment, Pret
                                      FROM Eveniment
                                      WHERE ID_eveniment = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("i", $eveniment_id);
                            $stmt->execute();
                            $stmt->bind_result($nume_eveniment, $data, $ora, $locatie, $descriere, $pret);
                            $stmt->fetch();

                            echo "<li>";
                            echo "<h3>$nume_eveniment</h3>";
                            echo "<p>Data: $data, Ora: $ora</p>";
                            echo "<p>Locație: $locatie</p>";
                            echo "<p>Descriere: $descriere</p>";
                            echo "<p>Pret bilet: $pret RON</p>";

                            // Adăugați un câmp pentru a selecta numărul de bilete
                            echo "<label for='cantitate_$eveniment_id'>Selectează numărul de bilete:</label>";
                            echo "<select id='cantitate_$eveniment_id' name='cantitate_$eveniment_id' onchange='updateCost($eveniment_id)'>";
                            for ($i = 1; $i <= 10; $i++) { // Modificați limita superioară la numărul maxim de bilete pe care le permiteți
                                echo "<option value='$i'>$i</option>";
                            }
                            echo "</select>";

                            // Adăugați un câmp hidden pentru eveniment_id și preț
                            echo "<input type='hidden' id='eveniment_id_$eveniment_id' name='eveniment_id' value='$eveniment_id'>";
                            echo "<input type='hidden' id='pret_$eveniment_id' value='$pret'>";

                            // Afișați costul inițial
                            echo "<p id='cost_$eveniment_id'>Costul biletelor: $pret RON</p>";

                            echo "</li>";
                        }
                    } else {
                        echo "<p>Coșul tău este gol.</p>";
                    }
                    // echo "<button type='submit' name='achizitie'">Achiziționează</button>";
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
        window.location.href = "achizitie.php";
    }
</script>
    <div class="footer">
        <p>&copy;2023 Eventica. Toate drepturile rezervate</p>
    </div>
</body>

</html>
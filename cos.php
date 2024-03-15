<?php
require ('config.php');
session_start();

if (!isset ($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset ($_POST['adauga_cos']) && isset ($_POST['eveniment_id'])) {
    $eveniment_id = $_POST['eveniment_id'];

    if (!isset ($_SESSION['cos'])) {
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
        function toggleMenu() {
            var menu = document.querySelector('.hamburger-menu');
            menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
            console.log("Butonul a fost apăsat!")
        }
    </script>
</head>

<body>
    <header class="header">
        <h3 class="title">Eventica</h3>
        <h3 class="title">Coșul tău</h3>
        <div class="icon" onclick="toggleMenu()">
            <div class="bar "></div>
            <div class="bar "></div>
            <div class="bar "></div>
        </div>
        <div class="hamburger-menu">
            <ul id="hamburger-menu">
                <li><a href="categorii.php">Categorii</a></li>
                <li><a href="cos.php">Coșul meu</a></li>
                <li><a href="notificari.php">Notificări</a></li>
            </ul>
        </div>

        <div class="top-right-menu">
            <a href="index.php">Acasă</a>
            <a href="contul_meu.php">Contul meu</a>
            <a href="logout.php">Deconectare</a>
        </div>
    </header>
    <div class="container">
        <div class="content">
            <ul>
                <?php
                $cost_total = 0;

                if (isset ($_SESSION['cos'])) {
                    foreach ($_SESSION['cos'] as $eveniment_id) {
                        $query = "SELECT Nume_eveniment, Data, Ora, Locatie, Descriere_eveniment, Pret
                                      FROM Eveniment
                                      WHERE ID_eveniment = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $eveniment_id);
                        $stmt->execute();
                        $stmt->bind_result($nume_eveniment, $data, $ora, $locatie, $descriere, $pret);
                        $stmt->fetch();

                        echo "<li class='event-item'>";
                        echo "<p> $nume_eveniment</p>";
                        echo "<p>Data: $data, Ora: $ora</p>";
                        echo "<p>Locație: $locatie</p>";
                        echo "<p>Descriere: $descriere</p>";
                        echo "<p>Pret bilet: $pret RON</p>";
                        echo "<label for='cantitate_$eveniment_id' class='quantity-label'>Selectează numărul de bilete:</label>";
                        echo "<select id='cantitate_$eveniment_id' name='cantitate_$eveniment_id' onchange='updateCost($eveniment_id)' class='quantity-select'>";
                        for ($i = 1; $i <= 10; $i++) {
                            echo "<option value='$i'>$i</option>";
                        }
                        echo "</select>";
                        echo "<input type='hidden' id='eveniment_id_$eveniment_id' name='eveniment_id' value='$eveniment_id'>";
                        echo "<input type='hidden' id='pret_$eveniment_id' value='$pret'>";
                        echo "<p id='cost_$eveniment_id'>Costul total al biletelor dumneavoastră este de: $pret RON</p>";

                        echo "</li>";
                    }
                    echo "<div class='button-container'>";
                    echo "<form method='post' action='achizitie.php'>";
                    echo "<button type='submit' class='custom-btn btn-7' name='achizitie'>Achiziționează</button>";
                    echo "</form>";
                    echo "</div>";
                } else {
                    echo "<p class='gol'>Coșul tău este gol.</p>";
                    echo "<div class='button-container'>";
                    echo "<form method='post' action='index.php'>";
                    echo "<button type='submit' class='custom-btn btn-7' name='continua'>Continuă cumpărăturile</button>";
                    echo "</form>";
                    echo "</div>";
                }
                ?>
            </ul>

        </div>
    </div>
    <br><br><br>
    <div class="footer">
        <p>&copy;2023 Eventica. Toate drepturile rezervate</p>
    </div>

</body>

</html>
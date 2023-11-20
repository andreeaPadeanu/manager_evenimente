<?php
require('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query_categorii = "SELECT DISTINCT Tip FROM Eveniment";
$result_categorii = $conn->query($query_categorii);

$categorie_selectata = isset($_GET['categorie']) ? $_GET['categorie'] : null;

if (!$categorie_selectata && $result_categorii->num_rows > 0) {
    $row = $result_categorii->fetch_assoc();
    $categorie_selectata = $row['Tip'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Categorii Evenimente</title>
    <link href="index.css" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        function afiseazaEvenimente(categorie) {
            console.log(categorie);
            $.ajax({
                url: 'categorii.php',
                type: 'GET',
                data: { categorie: categorie },
                success: function (data) {
                    $('body').html(data);
                    $('head').append('<link rel="stylesheet" type="text/css" href="index.css">');
                }
            });
        }
        function toggleMenu() {
            var menu = document.querySelector('.hamburger-menu');
            menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
            console.log("Butonul a fost apăsat!")
        }
        function afiseazaEvenimente(categorie) {
            $.ajax({
                url: 'categorii.php',
                type: 'GET',
                data: { categorie: categorie },
                success: function (data) {
                    $('body').html(data);
                    $('head').append('<link rel="stylesheet" type="text/css" href="index.css">');
                }
            });
        }
    </script>

</head>

<body>
    <header class="header">
        <h3 class="title">Eventica</h3>
        <br>
        <h3 class="title"> Evenimente pe categorii</h3>
        <div class="icon" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
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
            <ul class="category-list">
                <?php
                $result_categorii->data_seek(0);
                while ($row = $result_categorii->fetch_assoc()) {
                    $selectedClass = ($categorie_selectata == $row['Tip']) ? 'selected' : '';
                    echo "<li><a href='javascript:void(0)' class='category-button $selectedClass add-to-cart-button' onclick='afiseazaEvenimente(\"" . $row['Tip'] . "\")'>" . $row['Tip'] . "</a></li>";
                }


                ?>
            </ul>
            <div class="eveniment-container">
                <?php
                if ($categorie_selectata) {
                    $stmt = $conn->prepare("SELECT * FROM Eveniment WHERE Tip = ?");
                    $stmt->bind_param("s", $categorie_selectata);
                    $stmt->execute();
                    $result_evenimente = $stmt->get_result();
                    $stmt->close();

                    if ($result_evenimente->num_rows > 0) {
                        echo "<h3>Evenimentele în categoria \"$categorie_selectata\"</h3>";
                        while ($row_eveniment = $result_evenimente->fetch_assoc()) {
                            // Display event details
                            echo "<div class='eveniment'>";
                            echo "<img src='" . $row_eveniment['Imagine_eveniment'] . "' alt='" . $row_eveniment['Nume_eveniment'] . "'>";
                            echo "<h3>" . $row_eveniment['Nume_eveniment'] . "</h3>";
                            echo "<p>Data: " . $row_eveniment['Data'] . ", Ora: " . $row_eveniment['Ora'] . "</p>";
                            echo "<p>Locație: " . $row_eveniment['Locatie'] . "</p>";
                            echo "<p>Descriere: " . $row_eveniment['Descriere_eveniment'] . "</p>";
                            echo "<a href='javascript:void(0)' onclick='adaugaInCos(" . $row_eveniment['ID_eveniment'] . ")'>Adaugă în coș</a>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Nu există evenimente pentru categoria selectată.</p>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy;2023 Eventica. Toate drepturile rezervate</p>
    </div>
</body>

</html>
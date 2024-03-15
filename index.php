<?php
require('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query_evenimente = "SELECT E.ID_eveniment, E.Nume_eveniment, E.Data, E.Ora, E.Locatie, E.Descriere_eveniment, E.Imagine_eveniment
FROM Eveniment E ORDER BY E.Data ASC, E.Ora ASC";

$result_evenimente = $conn->query($query_evenimente);


$limba_curenta = isset($_COOKIE['limba']) ? $_COOKIE['limba'] : 'ro';
$texte = json_decode(file_get_contents("texts_$limba_curenta.json"), true);
?>

<!DOCTYPE html>
<html lang="<?php echo $limba_curenta; ?>">

<head>
    <title>
        <?php echo $texte['titlu_pagina']; ?>
    </title>
    <link href="index.css" rel="stylesheet" type="text/css">
    <link href="popup.css" rel="stylesheet" type="text/css">
</head>

<body>
    <header class="header">
        <h3 class="title">Eventica</h3>
        <div class="icon" onclick="toggleMenu()">
            <div class="bar "></div>
            <div class="bar "></div>
            <div class="bar "></div>
        </div>
        <div class="hamburger-menu">
            <ul id="hamburger-menu">
                <li><a href="categorii.php">
                        <?php echo $texte['buton_categorii']; ?>
                    </a></li>
                <li><a href="cos.php">
                        <?php echo $texte['buton_cos']; ?>
                    </a></li>
                <li><a href="notificari.php">
                        <?php echo $texte['buton_notificari']; ?>
                    </a></li>
                <li><a href="#" id="ajutor">
                        <?php echo $texte['buton_ajutor']; ?>
                    </a></li>
                <li><a href="#" id="schimba-limba">
                        <?php echo $texte['buton_limba']; ?>
                    </a></li>
            </ul>
        </div>

        <div class="top-right-menu">
            <a href="index.php">
                <?php echo $texte['buton_acasa']; ?>
            </a>
            <a href="contul_meu.php">
                <?php echo $texte['buton_contul_meu']; ?>
            </a>
            <a href="logout.php">
                <?php echo $texte['buton_deconectare']; ?>
            </a>
        </div>
    </header>
    <div class="container">
        <div class="content">
            <img src="backdrop.jpg" id="backdrop">
            <div class="eveniment-container">
                <?php
                while ($row = $result_evenimente->fetch_assoc()) {
                    echo "<div class='eveniment'>";
                    echo "<img src='" . $row['Imagine_eveniment'] . "' alt='" . $row['Nume_eveniment'] . "'>";
                    echo "<h3>" . $row['Nume_eveniment'] . "</h3>";
                    echo "<p>" . $texte['data'] . ": " . $row['Data'] . ", " . $texte['ora'] . ": " . $row['Ora'] . "</p>";
                    echo "<p>" . $texte['locatie'] . ": " . $row['Locatie'] . "</p>";
                    echo "<p>" . $texte['descriere'] . ": " . $row['Descriere_eveniment'] . "</p>";
                    echo "<a href='javascript:void(0)' onclick='adaugaInCos(" . $row['ID_eveniment'] . ")'>" . $texte['buton_adauga_in_cos'] . "</a>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        <div class="contact">
            <div class="social-media">
                <a href="https://www.facebook.com/" target="_blank">Facebook</a>
                <a href="https://www.instagram.com/" target="_blank">Instagram</a>
            </div>
            <div class="contact-info">
                <p>
                    <?php echo $texte['contacteaza_ne']; ?>
                </p>
                <p>
                    <?php echo $texte['adresa']; ?>
                </p>
                <p>
                    <?php echo $texte['telefon']; ?>
                </p>
                <p>
                    <?php echo $texte['email']; ?>
                </p>
            </div>
        </div>

        <div class="footer">
            <p>
                <?php echo $texte['drepturi_rezervate']; ?>
            </p>
        </div>

    </div>

    <div id="popup-container">
        <div id="popup-content">
            <h2>
                <?php echo $texte['popup_titlu']; ?>
            </h2>
            <p>
                <?php echo $texte['popup_mesaj']; ?>
            </p>
            <button id="close-popup">
                <?php echo $texte['popup_buton_inchide']; ?>
            </button>
        </div>
    </div>

    <script>
        function adaugaInCos(evenimentId) {
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "adauga_cos.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    alert(this.responseText);
                }
            };
            xhttp.send("eveniment_id=" + evenimentId);
        }

        function toggleMenu() {
            var menu = document.querySelector('.hamburger-menu');
            menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
            console.log("Butonul a fost apăsat!")
        }

        document.getElementById("ajutor").addEventListener("click", function () {
            document.getElementById("popup-container").style.display = "block";
        });

        document.getElementById("close-popup").addEventListener("click", function () {
            document.getElementById("popup-container").style.display = "none";
        });

        document.getElementById("schimba-limba").addEventListener("click", function () {
            var limbaCurenta = document.documentElement.lang;
            var nouaLimba = (limbaCurenta === "ro") ? "en" : "ro";
            document.cookie = "limba=" + nouaLimba;
            location.reload();
        });
    </script>

</body>

</html>
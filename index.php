<?php
require('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obține evenimentele și tipurile de bilete din baza de date
$query_evenimente = $query_evenimente = "SELECT E.ID_eveniment, E.Nume_eveniment, E.Data, E.Ora, E.Locatie, E.Descriere_eveniment
FROM Eveniment E";

$result_evenimente = $conn->query($query_evenimente);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Eventica</title>
    <link href="index.css" rel="stylesheet" type="text/css">
    
    <script>
        // Script JavaScript pentru adăugarea în coș fără a naviga la altă pagină
        function adaugaInCos(evenimentId) {
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "adauga_cos.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // Afișează răspunsul primit de la server
                    alert(this.responseText);
                }
            };
            xhttp.send("eveniment_id=" + evenimentId);
        }
        
        // function toggleHamburgerMenu() {
        //     var menu = document.getElementById("hamburger-menu");
        //     menu.style.display = (menu.style.display === "flex") ? "none" : "flex";
        // }
        
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
<div class="icon" onclick="toggleMenu()">
        <div class="bar "></div>
        <div class="bar "></div>
        <div class="bar "></div>
</div>
<div class="hamburger-menu">
    
    <!-- <button onclick="toggleHamburgerMenu()">☰</button> -->

    <ul id="hamburger-menu">
        <li><a href="#">Evenimente viitoare</a></li>
        <li><a href="#">Categorii</a></li>
        <li><a href="#">Oferte speciale</a></li>
        <li><a href="contul_meu.php">Contul Meu</a></li>
    </ul>
</div>

<!-- <div class="hamburger-icon" onclick="toggleHamburgerMenu()">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div> -->
        
        
        <div class="top-right-menu">
        <a href="index.php">Acasă</a>
        <a href="cos.php">Coș</a>
        <a href="notificari.php">Notificări</a>
        <a href="logout.php">Deconectare</a>
    </div>
    </header>
    <div class="container">
    
        <div class="content">
            <!-- <h2>Evenimente disponibile</h2> -->
            <img src="backdrop.jpeg" id="backdrop">

            <div class="container">
                <?php
                // Afișează evenimentele
                while ($row = $result_evenimente->fetch_assoc()) {
                    echo "<div class='eveniment'>";
                    echo "<h3>" . $row['Nume_eveniment'] . "</h3>";
                    echo "<p>Data: " . $row['Data'] . ", Ora: " . $row['Ora'] . "</p>";
                    echo "<p>Locație: " . $row['Locatie'] . "</p>";
                    echo "<p>Descriere: " . $row['Descriere_eveniment'] . "</p>";

                    // Link pentru a adăuga în coș folosind scriptul JavaScript
                    echo "<a href='javascript:void(0)' onclick='adaugaInCos(" . $row['ID_eveniment'] . ")'>Adaugă în coș</a>";

                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="contact">
    <div class="social-media">
            <a href="https://www.facebook.com/" target="_blank">Facebook</a>
            <a href="https://www.instagram.com/" target="_blank">Instagram</a>
        </div>
        <div class="contact-info">
            <p>Ia legatura cu noi aici</p>
            <p>Str x 23 , Cluj Napoca</p>
            <p>0123 456 789</p>
            <p>eventica@test.com</p>
        </div>
    </div>

    <div class="footer">
        <p>&copy;2023 Eventica. Toate drepturile rezervate</p>
    </div>

</body>

</html>
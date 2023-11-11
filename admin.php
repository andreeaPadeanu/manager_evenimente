<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Verificați dacă a fost trimisă o cerere pentru adăugarea, editarea sau ștergerea de evenimente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['adauga_eveniment'])) {
        // Procesați adăugarea de evenimente
        // Aici puteți adăuga codul pentru adăugarea evenimentelor în baza de date
    } elseif (isset($_POST['editeaza_eveniment'])) {
        // Procesați editarea de evenimente
        // Aici puteți adăuga codul pentru editarea evenimentelor din baza de date
    } elseif (isset($_POST['sterge_eveniment'])) {
        // Procesați ștergerea de evenimente
        // Aici puteți adăuga codul pentru ștergerea evenimentelor din baza de date
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Panou de Control pentru Administrator</title>
</head>

<body>
    <h2>Panou de Control pentru Administrator</h2>
    <p>Bine ați venit, Administrator!</p>

    <!-- Formular pentru adăugarea de evenimente -->
    <h3>Adaugare Eveniment</h3>
    <form method="post" action="admin.php">
        <!-- Adăugați aici câmpurile necesare pentru adăugarea unui eveniment -->
        <input type="submit" name="adauga_eveniment" value="Adaugă Eveniment">
    </form>

    <!-- Formular pentru editarea de evenimente -->
    <h3>Editare Eveniment</h3>
    <form method="post" action="admin.php">
        <!-- Adăugați aici câmpurile necesare pentru editarea unui eveniment -->
        <input type="submit" name="editeaza_eveniment" value="Editează Eveniment">
    </form>

    <!-- Formular pentru ștergerea de evenimente -->
    <h3>Stergere Eveniment</h3>
    <form method="post" action="admin.php">
        <!-- Adăugați aici câmpurile necesare pentru ștergerea unui eveniment -->
        <input type="submit" name="sterge_eveniment" value="Șterge Eveniment">
    </form>
    <h3>Adaugare administrator</h3>
    <form method="post" action="adauga_ad.php">
        <!-- Adăugați aici câmpurile necesare pentru ștergerea unui eveniment -->
        <input type="submit" name="Adauga administrator nou" value="Adauga administrator nou">
    </form>
    <h3>Adaugare Speaker</h3>
    <form method="post" action="speaker.php">
        <!-- Adăugați aici câmpurile necesare pentru ștergerea unui eveniment -->
        <input type="submit" name="Adauga administrator nou" value="Adauga administrator nou">
    </form>

    <a href="logout.php">Deconectare</a>

</body>

</html>
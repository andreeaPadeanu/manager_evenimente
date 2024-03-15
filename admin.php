<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['adauga_eveniment'])) {

    } elseif (isset($_POST['editeaza_eveniment'])) {

    } elseif (isset($_POST['sterge_eveniment'])) {

    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link href="admin.css" rel="stylesheet" type="text/css">
    <title>Panoul de control al administratorilor</title>
</head>

<body>
    <h2>Panoul de control al administratorilor</h2>
    <p>Bine ați venit !</p>

    <form method="post" action="adauga_eveniment.php">
        <input type="submit" name="adauga_eveniment" value="Adăugă un eveniment nou">
    </form>
    <br>

    <form method="post" action="editare_eveniment.php">
        <input type="submit" name="editeaza_eveniment" value="Editează un eveniment">
    </form>
    <br>
    <form method="post" action="sterge_eveniment.php">
        <input type="submit" name="sterge_eveniment" value="Sterge un eveniment">
    </form>
    <br>
    <form method="post" action="adauga_ad.php">
        <input type="submit" name="Adauga administrator nou" value="Adaugă un administrator nou">
    </form>
    <br>
    <form method="post" action="adauga_speaker.php">
        <input type="submit" name="Adauga speaker nou" value="Adaugă un speaker nou">
    </form>
    <br>
    <form method="post" action="adauga_sponsor.php">
        <input type="submit" name="Adauga sponsor nou" value="Adaugă un sponsor nou">
    </form>
    <br>
    <form method="post" action="adauga_partener.php">
        <input type="submit" name="Adauga partener nou" value="Adaugă un partener nou">
    </form>
    <br>
    <form method="post" action="trimite_invitatie.php">
        <input type="submit" name="Trimite invitatii " value="Trimite invitatii la evenimente ">
    </form>
    <a href="logout.php">Deconectare</a>

</body>

</html>
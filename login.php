<?php
require('config.php');
session_start();

// Verificați dacă utilizatorul este deja autentificat
if (isset($_SESSION['user_id'])) {
    header("Location:index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['conectare'])) {
        $email = $_POST['email'];
        $parola = $_POST['parola'];

        // Verificați dacă utilizatorul este un administrator
        $query = "SELECT ID_admin, Nume, Prenume FROM Admin WHERE Email = ? AND Parola = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $email, $parola);
        $stmt->execute();
        $stmt->bind_result($admin_id, $nume, $prenume);

        if ($stmt->fetch()) {
            $_SESSION['admin_id'] = $admin_id;
            header("Location: admin.php");
            exit();
        }

        // Dacă utilizatorul nu este administrator, verificați în tabelul User
        $query = "SELECT ID_user, Nume, Prenume, Parola FROM User WHERE Email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $nume, $prenume, $parola_db);
        $stmt->fetch();

        if (password_verify($parola, $parola_db)) {
            // Parola este corectă
            $_SESSION['user_id'] = $user_id;
            header("Location: index.php");
            exit();
        } else {
            $mesaj_eroare = "Autentificare eșuată. Vă rugăm să verificați emailul și parola.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<html>

<head>
    <link href="login.css" rel="stylesheet" type="text/css">
    <title>Conectare și Înregistrare</title>

</head>

<body>
    <div class="login">
        <h2>LOG IN</h2>
        <?php
        if (isset($mesaj_eroare)) {
            echo '<p style="color: red;">' . $mesaj_eroare . '</p>';
        }
        ?>
        <form method="post" action="login.php">
            <input type="text" name="email" placeholder="email" required><br>
            <input type="password" name="parola" placeholder="parola" required><br>
            <input type="submit" name="conectare" value="Conectare">
        </form>

        <!-- Buton pentru înregistrare -->
        <a href="register.php" class="register">Înregistrează-te</a>
    </div>
</body>

</html>
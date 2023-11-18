<?php
session_start();
require('config.php'); // Conectarea la baza de date

if (!isset($_SESSION['user_id'])) {
    // Dacă utilizatorul nu este autentificat, redirecționează-l către pagina de login
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Inițializează variabilele pentru datele utilizatorului
$nume = $prenume = $email = $telefon = "";
$nume_err = $prenume_err = $email_err = $telefon_err = "";

// Procesează datele atunci când formularul este trimis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validează numele
    if (empty(trim($_POST["nume"]))) {
        $nume_err = "Te rugăm să completezi numele.";
    } else {
        $nume = trim($_POST["nume"]);
    }

    // Validează prenumele
    if (empty(trim($_POST["prenume"]))) {
        $prenume_err = "Te rugăm să completezi prenumele.";
    } else {
        $prenume = trim($_POST["prenume"]);
    }

    // Validează emailul
    if (empty(trim($_POST["email"]))) {
        $email_err = "Te rugăm să completezi adresa de email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validează numărul de telefon
    $telefon = trim($_POST["telefon"]);

    // Actualizează datele în baza de date dacă nu există erori
    if (empty($nume_err) && empty($prenume_err) && empty($email_err)) {
        $query = "UPDATE User SET Nume = ?, Prenume = ?, Email = ?, Telefon = ? WHERE ID_user = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ssssi", $nume, $prenume, $email, $telefon, $user_id);
            if ($stmt->execute()) {
                // Actualizarea a fost reușită
                header("Location: contul_meu.php");
            } else {
                echo "Ceva nu a mers bine. Te rugăm să încerci mai târziu.";
            }
            $stmt->close();
        }
    }
}

// Obține datele utilizatorului din baza de date
$query = "SELECT Nume, Prenume, Email, Telefon FROM User WHERE ID_user = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($nume, $prenume, $email, $telefon);
    $stmt->fetch();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <link href="contul_meu.css" rel="stylesheet" type="text/css">
    <title class="title">Contul meu</title>
</head>

<body>
    <header class="header">
        <h3 class="title">Eventica</h3>
        <div class="top-right-menu">
            <a href="index.php">Acasă</a>
            <a href="contul_meu.php">Contul meu</a>
            <a href="logout.php">Deconectare</a>
        </div>
    </header>

    <div class="content">
        <h2>Datele contului meu</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nume:</label>
                <input type="text" name="nume" value="<?php echo $nume; ?>">
                <span class="help-block">
                    <?php echo $nume_err; ?>
                </span>
            </div>
            <div class="form-group">
                <label>Prenume:</label>
                <input type="text" name="prenume" value="<?php echo $prenume; ?>">
                <span class="help-block">
                    <?php echo $prenume_err; ?>
                </span>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="text" name="email" value="<?php echo $email; ?>">
                <span class="help-block">
                    <?php echo $email_err; ?>
                </span>
            </div>
            <div class="form-group">
                <label>Telefon:</label>
                <input type="text" name="telefon" value="<?php echo $telefon; ?>">
            </div>
            <!-- !!! echo input <input type="text" name="telefon" value="$telefon"> -->
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Actualizare">
            </div>
        </form>
    </div>

</body>

</html>
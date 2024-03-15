<?php
session_start();
require('config.php');

class Utilizator
{
    private $conn;
    private $nume;
    private $prenume;
    private $email;
    private $telefon;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function verificaAutentificare()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit();
        }
    }

    public function obtineDetaliiUtilizator($userID)
    {
        $query = "SELECT Nume, Prenume, Email, Telefon FROM User WHERE ID_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->bind_result($this->nume, $this->prenume, $this->email, $this->telefon);
        $stmt->fetch();
        $stmt->close();

        return [
            'nume' => $this->nume,
            'prenume' => $this->prenume,
            'email' => $this->email,
            'telefon' => $this->telefon,
        ];
    }

    public function actualizeazaDetaliiUtilizator($userID, $nume, $prenume, $email, $telefon)
    {
        $query = "UPDATE User SET Nume = ?, Prenume = ?, Email = ?, Telefon = ? WHERE ID_user = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssssi", $nume, $prenume, $email, $telefon, $userID);
            $success = $stmt->execute();
            $stmt->close();

            return $success;
        }

        return false;
    }
}

$utilizatorManager = new Utilizator($conn);
$utilizatorManager->verificaAutentificare();

$nume = $prenume = $email = $telefon = "";
$nume_err = $prenume_err = $email_err = $telefon_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["nume"]))) {
        $nume_err = "Te rugăm să completezi numele.";
    } else {
        $nume = trim($_POST["nume"]);
    }

    if (empty(trim($_POST["prenume"]))) {
        $prenume_err = "Te rugăm să completezi prenumele.";
    } else {
        $prenume = trim($_POST["prenume"]);
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Te rugăm să completezi adresa de email.";
    } else {
        $email = trim($_POST["email"]);
    }

    $telefon = trim($_POST["telefon"]);

    if (empty($nume_err) && empty($prenume_err) && empty($email_err)) {
        if ($utilizatorManager->actualizeazaDetaliiUtilizator($user_id, $nume, $prenume, $email, $telefon)) {
            header("Location: contul_meu.php");
            exit();
        } else {
            echo "Ceva nu a mers bine. Te rugăm să încerci mai târziu.";
        }
    }
}

$detalii_utilizator = $utilizatorManager->obtineDetaliiUtilizator($user_id);

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
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Actualizare">
            </div>
        </form>
    </div>

</body>

</html>
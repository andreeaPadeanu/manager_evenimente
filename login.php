<?php
require('config.php');
session_start();
class Autentificare
{
    private $conn;
    private $admin_id = '';
    private $nume = '';
    private $prenume = '';
    private $user_id = '';
    private $parola_db = '';
    private $mesaj_eroare = '';

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function verificaAutentificare()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location:index.php");
            exit();
        }
    }
    public function verificaPostare()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['conectare'])) {
                $email = $_POST['email'];
                $parola = $_POST['parola'];
                $this->verificaAdmin($email, $parola);
                $this->verificaUtilizator($email, $parola);
            }
        }
    }
    private function verificaAdmin($email, $parola)
    {
        $query = "SELECT ID_admin, Nume, Prenume FROM Admin WHERE Email = ? AND Parola = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $email, $parola);
        $stmt->execute();
        $stmt->bind_result($this->admin_id, $this->nume, $this->prenume);
        if ($stmt->fetch()) {
            $_SESSION['admin_id'] = $this->admin_id;
            header("Location: admin.php");
            exit();
        }
    }
    private function verificaUtilizator($email, $parola)
    {
        $query = "SELECT ID_user, Nume, Prenume, Parola FROM User WHERE Email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($this->user_id, $this->nume, $this->prenume, $this->parola_db);
        $stmt->fetch();
        if (password_verify($parola, $this->parola_db)) {
            $_SESSION['user_id'] = $this->user_id;
            header("Location: index.php");
            exit();
        } else {
            $mesaj_eroare = "Autentificare eșuată. Vă rugăm să verificați emailul și parola.";
        }
    }
}
$autentificare = new Autentificare($conn);
$autentificare->verificaAutentificare();
$autentificare->verificaPostare(); ?>

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
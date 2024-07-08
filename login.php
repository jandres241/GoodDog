<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "GoodDog";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Login as Visitor
session_start();
$_SESSION["user_id"] = 1;
$_SESSION["account_type"] = 'customer';

// User registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["action"] == "register") {
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $name = $_POST["name"];

    $sql = "INSERT INTO users (email, password, name, account_type) VALUES ('$email', '$password', '$name', 'customer')";
    if ($conn->query($sql) === TRUE) {
        echo "Cuenta Creada!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// User login
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["action"] == "login") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Login successful, store user session
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["account_type"] = $row["account_type"];
            echo "Login successful!";
        } else {
            echo "Correo o contraseña invalida.";
        }
    } else {
        echo "Correo o contraseña invalida.";
    }
}

// Create employee account (admin only)
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["action"] == "create_employee" && $_SESSION["account_type"] == "admin") {
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $name = $_POST["name"];

    $sql = "INSERT INTO users (email, password, name, account_type) VALUES ('$email', '$password', '$name', 'employee')";
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo empleado agregado!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Good Dog</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <header>
            <a class="logo" href="index.php">
                <img src="img/logo.png">
                <span> Good Dog </span>
            </a>
        </header>

        <main>
            <section>
                <h2>Inicio de Sesion</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="hidden" name="action" value="login">
                    Correo electronico: <input type="email" name="email" required><br>
                    Contraseña: <input type="password" name="password" required><br>
                    <input type="submit" name="submit" value="Iniciar sesion">
                </form>
            </section>

            <section>
                <h2>Registrarse</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="hidden" name="action" value="register">
                    Correo Electronico: <input type="email" name="email" required><br>
                    Contraseña: <input type="password" name="password" required><br>
                    Nombre: <input type="text" name="name" required><br>
                    <input type="submit" name="submit" value="Registrarse">
                </form>
            </section>

            <?php if ($_SESSION["account_type"] == "admin") { ?>
                <section>
                    <h2>Crear un empleado</h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input type="hidden" name="action" value="create_employee">
                            Correo: <input type="email" name="email" required><br>
                            Contraseña: <input type="password" name="password" required><br>
                            Nombre: <input type="text" name="name" required><br>
                        <input type="submit" name="submit" value="Añadir empleado">
                    </form>
                </section>
            <?php } ?>
    </main>
</body>
</html>
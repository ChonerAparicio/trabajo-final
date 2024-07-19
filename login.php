<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == 'admin' && $password == 'admin123') {
        $_SESSION['admin'] = true;
        header("location: admin.php");
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W-220!</title>
    <link rel="icon" type="image/png" href="./recursos/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/misEstilos.css">
    <style>
      body {
          margin: 0;
          font-family: 'Roboto', sans-serif;
          background: url('./recursos/imagenFondo_Mesa\ de\ trabajo\ 1.png') no-repeat center center fixed;
          background-size: cover;
      }
      .error-message {
          color: red;
          font-weight: bold;
          margin-top: 10px;
      }
  </style>
</head>
<body>
    <div class="background"></div>
        <div class="login-container">
            <h1>Bienvenido</h1>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="login.php" method="post" autocomplete="off">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Iniciar Sesión</button>
                <a href="./index.php">Regresar</a>
            </form>
        </div>
    </div>    
</body>
</html>
<?php
include('db.php'); 

$sql = "SELECT * FROM inventario";
$result = $conn->query($sql);
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
    <link rel="stylesheet" href="./css/estilo.css">
    <style>
      body {
          margin: 0;
          font-family: 'Roboto', sans-serif;
          background: url('./recursos/imagenFondo_Mesa de trabajo 1.png') no-repeat center center fixed;
          background-size: cover;
      }
    </style>
</head>
<body>
    <header>
        <h1>TIENDA DE PRODUCTOS</h1>
        <a href="./login.php" class="admin-button">Administrar Productos</a>
    </header>
    <main>
        <div class="product-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="product-item">';
                    echo '<img src="./recursos/' . htmlspecialchars($row['producto_imagen']) . '" alt="' . htmlspecialchars($row['producto_nombre']) . '">';
                    echo '<h2>' . htmlspecialchars($row['producto_nombre']) . '</h2>';
                    echo '<p>Descripción del producto</p>'; 
                    echo '<p class="price">$' . htmlspecialchars($row['producto_precio']) . '</p>';
                    echo '<p class="stock">Stock: ' . htmlspecialchars($row['producto_stock']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo 'No hay productos disponibles.';
            }

            $conn->close();
            ?>
        </div>
    </main>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
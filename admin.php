<?php
session_start();
include('db.php');

if (!isset($_SESSION['admin'])) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $stock = (int)$_POST['stock'];
        $precio = (float)$_POST['precio'];

        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $file_tmp_name = $_FILES['imagen']['tmp_name'];
            $file_name = $_FILES['imagen']['name'];
            $file_destination = './recursos/' . $file_name;

            if (move_uploaded_file($file_tmp_name, $file_destination)) {
                $imagen = $file_name;
            }
        }

        $stmt = $conn->prepare("INSERT INTO inventario (producto_nombre, producto_stock, producto_precio, producto_imagen) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sids', $nombre, $stock, $precio, $imagen);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['update'])) {
        $id = (int)$_POST['id'];
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $stock = (int)$_POST['stock'];
        $precio = (float)$_POST['precio'];

        $stmt = $conn->prepare("SELECT producto_imagen FROM inventario WHERE producto_id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($imagen_actual);
        $stmt->fetch();
        $stmt->close();

        $imagen = $imagen_actual; 
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $file_tmp_name = $_FILES['imagen']['tmp_name'];
            $file_name = $_FILES['imagen']['name'];
            $file_destination = './recursos/' . $file_name;

            if (move_uploaded_file($file_tmp_name, $file_destination)) {
                $imagen = $file_name; 
            }
        }

        $stmt = $conn->prepare("UPDATE inventario SET producto_nombre=?, producto_stock=?, producto_precio=?, producto_imagen=? WHERE producto_id=?");
        $stmt->bind_param('sidsi', $nombre, $stock, $precio, $imagen, $id);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM inventario WHERE producto_id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }
}

$sql = "SELECT * FROM inventario";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Productos</title>
    <link rel="icon" type="image/png" href="./recursos/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/estiloAdmin.css">
    <style>
      body {
          margin: 0;
          font-family: 'Roboto', sans-serif;
          background: url('./recursos/imagenFondo_Mesa\ de\ trabajo\ 1.png') no-repeat center center fixed;
          background-size: cover;
      }
    </style>
</head>
    <body>
        <div class="adm-container">
            <h1>Listado de Productos</h1>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['producto_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['producto_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['producto_stock']); ?></td>
                    <td><?php echo htmlspecialchars($row['producto_precio']); ?></td>
                    <td><img src="./recursos/<?php echo htmlspecialchars($row['producto_imagen']); ?>" alt="<?php echo htmlspecialchars($row['producto_nombre']); ?>" width="100"></td>
                    <td>
                        <form method="post" enctype="multipart/form-data" action="admin.php" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['producto_id']); ?>">
                            <input type="text" name="nombre" value="<?php echo htmlspecialchars($row['producto_nombre']); ?>">
                            <input type="number" name="stock" value="<?php echo htmlspecialchars($row['producto_stock']); ?>">
                            <input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($row['producto_precio']); ?>">
                            <input type="file" name="imagen">
                            <button type="submit" name="update">Actualizar</button>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['producto_id']); ?>">
                            <button type="submit" name="delete">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>

            <h2>Agregar Nuevo Producto</h2>
                <form class="add-product-form" method="post" enctype="multipart/form-data" action="admin.php" autocomplete="off">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock:</label>
                        <input type="number" id="stock" name="stock" required>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio:</label>
                        <input type="number" step="0.01" id="precio" name="precio" required>
                    </div>
                    <div class="form-group">
                        <label for="imagen">Imagen:</label>
                        <input type="file" id="imagen" name="imagen" required>
                    </div>
                    <div class="form-group">
                        <button class="agregar" type="submit" name="add">Agregar</button>
                    </div>
                </form>
            
                <form class="cerrar-sesion" method="post" action="logout.php">
                    <button class="cerrar" type="submit">Cerrar sesión</button>
                </form>
        </div>
    </body>
</html>

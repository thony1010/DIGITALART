
<?php
include "db.php";

$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
  <title>PowerArt - Tu cargador único</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>⚡ PowerArt</h1>
    <nav>
      <a href="index.php">Inicio</a>
      <a href="productos.php">Productos</a>
      <a href="contacto.php">Contacto</a>
    </nav>
  </header>
  <main>
    <h2>Nuestros productos</h2>
    <div class="productos">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="producto">
          <img src="<?= $row['imagen'] ?>" alt="<?= $row['nombre'] ?>">
          <h3><?= $row['nombre'] ?></h3>
          <p>$<?= $row['precio'] ?></p>
          <a href="personalizar.php?id=<?= $row['id'] ?>">Personalizar</a>
         
        </div>
      <?php endwhile; ?>
    </div>
  </main>
</body>
</html>

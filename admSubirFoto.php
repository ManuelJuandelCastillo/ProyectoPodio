<?php
session_start();
if ($_SESSION['tipo_usuario'] != 'admin') {
    header('location:index.php');
}

if (isset($_POST['subir-foto'])) {
    $dni = $_POST['dni'];
    $tipoImagen = $_POST['tipo-imagen'];
    $formato = $_FILES['imagen']['type'];
    $tamanio = $_FILES['imagen']['size'];

    // crear el blob
    $archivodestino = $_FILES['imagen']['tmp_name'];
    $imagen = fopen($archivodestino, 'r');
    $img_blob = fread($imagen, $tamanio);

    require_once 'Conexion.php';
    $dbh = new Conexion;

    $sth = $dbh->prepare('select * from personas_imagenes where documento=:dni and tipo:imagen=:tipo');
    $sth->execute([':dni' => $dni, ':tipo' => $tipoImagen]);
    $dataImagen = $sth->fetch(PDO::FETCH_ASSOC);

    if ($dataImagen != '') {
        $sth = $dbh->prepare('update personas_imagenes set imagen = :imagen, formato = :formato where documento=:dni and tipo_imagen=:tipo, estado = 0');
        $sth->execute([':imagen' => $img_blob, ':formato' => $formato, ':dni' => $dni, ':tipo' => $tipoImagen]);
    } else {
        $sth = $dbh->prepare('insert into personas_imagenes (documento, tipo_imagen, imagen, formato, estado) values (:dni, :tipo, :imagen, :formato, :estado)');
        $sth->execute([':dni'=>$dni, ':tipo'=>$tipoImagen, ':imagen'=>$img_blob, ':formato'=>$formato, ':estado'=>0]);
    }

    header("location:admDatosJugadora.php?dni=$dni");
}

// se llega a la pagina desde admDatosJugadora
if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];
    $tipoImagen = $_GET['tipo-imagen'];
}

require_once 'include/header.php';
?>
<link rel="stylesheet" href="css/admSubirFoto.css">
<?php
require_once 'include/navbar.php';
?>

<section class="main-container">
    <h2><?= $tipoImagen ?></h2>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
        <div class="card-container">
            <input type="text" name="dni" class="input-oculto" value="<?= $dni ?>" readonly>
            <input type="text" name="tipo-imagen" class="input-oculto" value="<?= $tipoImagen ?>" readonly>
            <input type="file" accept="image/*" name="imagen" class="input-foto" required>
        </div>
        <div class="btn-container">
            <button type="submit" class="form-btn" name="subir-foto">subir foto</button>
        </div>
    </form>
</section>
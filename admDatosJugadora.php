<?php
session_start();
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
    header('location:index.php');
}
require_once 'Conexion.php';
    $dbh = new Conexion;
if(isset($_GET['guardar-admOK'])){
    $dni = $_GET['dni'];
    $foto4x4 = 'FALTA';
    $dni_f = 'FALTA';
    $dni_d = 'FALTA';
    $ficha = 'FALTA';
    if(isset($_GET['foto_4x4']) && $_GET['foto_4x4'] == 'on'){
        $foto4x4 = 'OK';
    }
    if(isset($_GET['dni_f']) && $_GET['dni_f'] == 'on'){
        $dni_f = 'OK';
    }
    if(isset($_GET['dni_d']) && $_GET['dni_d'] == 'on'){
        $dni_d = 'OK';
    }
    if(isset($_GET['ficha_ok']) && $_GET['ficha_ok'] == 'on'){
        $ficha = 'OK';
    }
    
    $sth = $dbh->prepare('update personas set ficha_ok = :ficha, foto_4x4_ok = :foto, dni_frente_ok = :dni_f, dni_dorso_ok = :dni_d where documento = :dni');
    $sth->execute([':ficha'=>$ficha, ':foto'=>$foto4x4, ':dni_f'=>$dni_f, ':dni_d'=>$dni_d, ':dni'=>$dni]);
}

if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];
    $sth = $dbh->prepare('select * from personas where documento = :dni');
    $sth->execute([':dni' => $dni]);
    $campo_data = $sth->fetch(PDO::FETCH_ASSOC);

    // recuperar imagenes
    $res = $dbh->prepare('select * from personas_imagenes where documento= :dni');
    $res->execute([':dni' => $dni]);

    while ($campo_img = $res->fetch(PDO::FETCH_ASSOC)) {
        if ($campo_img['tipo_imagen'] == 'foto') {
            $img_foto = $campo_img['imagen'];
            $form_foto = $campo_img['formato'];
        }
        if ($campo_img['tipo_imagen'] == 'dni_f') {
            $img_dni_f = $campo_img['imagen'];
            $form_dni_f = $campo_img['formato'];
        }
        if ($campo_img['tipo_imagen'] == 'dni_d') {
            $img_dni_d = $campo_img['imagen'];
            $form_dni_d = $campo_img['formato'];
        }
    }
}

// incluir cabeceras
require_once 'include/header.php';
?>
<link rel="stylesheet" href="css/admDatosJugadora.css">
<?php
// incluir navbar
require_once 'include/navbar.php';
?>
<section class="main-container">
    <form action="<?=$_SERVER['PHP_SELF']?>" method="GET" class="form-selector">
    <div class="card-container">
        <label for="dni">buscar por DNI:</label>    
        <input type="text" name="dni" id="dni">
        <button type="submit" class="form-btn">ver info jugadora</button>
    </div>
    </form>
</section>

<?php
if(isset($_GET['dni'])){
?>
<section class="main-container">
    <h2>Carnet Podio: <?= $campo_data['carnet'] ?> - Carnet fmv: <?= $campo_data['carnet_fmv'] ?> </h2>
    <div class="card-container">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" readonly value="<?= $campo_data['nombres'] ?>">
        </div>
        <div class="form-group">
            <label for="apel">Apellido</label>
            <input type="text" readonly value="<?= $campo_data['apellidos'] ?>">
        </div>
        <div class="form-group">
            <label for="nac">Fecha de nacimiento</label>
            <input type="date" readonly value="<?= $campo_data['fecha_nacimiento'] ?>">
        </div>
    </div>
    <p class="form-section-title">telefonos</p>
    <div class="card-container">
        <div class="form-group">
            <label for="part">Particular</label>
            <input type="text" readonly value="<?= $campo_data['telefono_particular'] ?>">
        </div>
        <div class="form-group">
            <label for="celular">Celular</label>
            <input type="text" readonly value="<?= $campo_data['telefono_celular'] ?>">
        </div>
        <div class="form-group">
            <label for="emergencia">Emergencias</label>
            <input type="text" readonly value="<?= $campo_data['telefono_emergencias'] ?>">
        </div>
    </div>
    <p class="form-section-title">datos de contacto</p>
    <div class="card-container">
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" readonly value="<?= $campo_data['correo_electronico'] ?>">
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" readonly value="<?= $campo_data['domicilio'] ?>">
        </div>
        <div class="form-group">
            <label for="localidad">Localidad</label>
            <input type="text" readonly value="<?= $campo_data['localidad'] ?>">
        </div>
        <div class="check-container">
            <label for="check">Acepto reglamento y condiciones (<a href="">leer</a>)</label>
            <input type="checkbox" readonly <?php if ($campo_data['ficha_ok'] == 'OK') {
                                                echo 'checked';
                                            } ?>>
        </div>
    </div>
</section>

<!-- seccion imagenes -->
<section class="img-section">
    <h2>imagenes</h2>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="GET">
    <input type="text" name="dni" value="<?=$_GET['dni'] ?>">
        <div class="check-container check-ficha">
            <label for="ficha_ok">Ficha: </label>
            <input type="checkbox" name="ficha_ok" id="ficha_ok" <?php if ($campo_data['ficha_ok'] == 'OK') {
                                                                        echo 'checked';
                                                                    } ?>>
        </div>

        <div class="img-container" id="img-container">
            <div class="img-card">
                <?php
                if (isset($img_foto)) {
                    echo "<img src='data:" . $form_foto . "; base64," . base64_encode($img_foto) . "' style='width:200px'>";
                } else { ?>
                    <img src="img/avatar.jpg" alt="" style="width:220px">
                <?php
                }
                ?>
                <div class="check-container">
                    <label for="foto_4x4">Foto 4x4</label>
                    <input type="checkbox" name="foto_4x4" id="foto_4x4" <?php if ($campo_data['foto_4x4_ok'] == 'OK') {
                                                                                echo 'checked';
                                                                            } ?>>
                </div>
            </div>

            <div class="img-card">
                <?php
                if (isset($img_dni_f)) {
                    echo "<img src='data:" . $form_dni_f . "; base64," . base64_encode($img_dni_f) . "' style='width:200px'>";
                } else { ?>
                    <img src="img/dni.png" alt="" style="width:220px">
                <?php
                }
                ?>
                <div class="check-container">
                    <label for="dni_f">DNI frente</label>
                    <input type="checkbox" name="dni_f" id="dni_f" <?php if ($campo_data['dni_frente_ok'] == 'OK') {
                                                                        echo 'checked';
                                                                    } ?>>
                </div>
            </div>

            <div class="img-card">
                <?php
                if (isset($img_dni_d)) {
                    echo "<img src='data:" . $form_dni_d . "; base64," . base64_encode($img_dni_d) . "' style='width:200px'>";
                } else { ?>
                    <img src="img/dni.png" alt="" style="width:220px">
                <?php
                }
                ?>
                <div class="check-container">
                    <label for="dni_d">DNI dorso</label>
                    <input type="checkbox" name="dni_d" id="dni_d" <?php if ($campo_data['dni_dorso_ok'] == 'OK') {
                                                                        echo 'checked';
                                                                    } ?>>
                </div>
            </div>
            <button type="submit" class="form-btn" name="guardar-admOK">guardar cambios</button>
        </div>
    </form>
</section>

<?php
// cierre del if
} 
// incluir script navbar y cierre de etiquetas body y html
require_once 'include/footer.php';
?>
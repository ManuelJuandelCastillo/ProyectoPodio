<?php
session_start();
if (isset($_SESSION['dni'])) {
    require_once 'Conexion.php';
    $dbh = new Conexion;
    $dni = $_SESSION['dni'];

    if (isset($_POST['actualiza'])) {
        $ficha = (isset($_POST['condiciones'])) ? 'OK' : 'FALTA';
           
        $sth = $dbh->prepare('update personas set nombres=:nombre, apellidos=:apellido,
                        fecha_nacimiento=:fnac, telefono_particular=:particular,
                        telefono_celular=:celular, telefono_emergencias=:emergencias,
                        correo_electronico=:email, domicilio=:direccion,
                        localidad=:localidad, ficha_ok=:ficha where documento = :dni');
        $sth->execute([':nombre'=>$_POST['name'],
                        ':apellido'=>$_POST['apel'],
                        ':fnac'=>$_POST['nac'],
                        ':particular'=>$_POST['part'],
                        ':celular'=>$_POST['celular'],
                        ':emergencias'=>$_POST['emergencia'],
                        ':email'=>$_POST['email'],
                        ':direccion'=>$_POST['direccion'],
                        ':localidad'=>$_POST['localidad'],
                        ':ficha'=>$ficha,
                        ':dni'=>$dni]);
        header('location:index.php');
    }


    $sth = $dbh->prepare('select * from personas where documento = :dni');
    $sth->execute([':dni' => $dni]);
    $campo_data = $sth->fetch(PDO::FETCH_ASSOC);
}else{
    // no hay session se redirige a index
    header('location:index.php');
}
?>

<?php
// incluir head
require_once 'include/header.php';
?>

<!-- css custom -->
<link rel="stylesheet" href="css/actualizarDatos.css">

    
<?php
// incluir cierre head, apertura body y navbar
require_once 'include/navbar.php';
?>

    <!-- cuerpo de pagina -->
    <form class="main-container" id="form-datos" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
        <h2>Actualizar información personal</h2>
        <div class="card-container">
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" name="name" id="name" autofocus value="<?= $campo_data['nombres'] ?>">
            </div>
            <div class="form-group">
                <label for="apel">Apellido:</label>
                <input type="text" name="apel" id="apel" value="<?= $campo_data['apellidos'] ?>">
            </div>
            <div class="form-group">
                <label for="nac">Fecha de nacimiento</label>
                <input type="date" name="nac" id="nac" value="<?= $campo_data['fecha_nacimiento'] ?>">
            </div>
        </div>
        <p class="form-section-title">telefonos</p>
        <div class="card-container">
            <div class="form-group">
                <label for="part">Particular</label>
                <input type="text" name="part" id="part" value="<?= $campo_data['telefono_particular'] ?>">
            </div>
            <div class="form-group">
                <label for="celular">Celular</label>
                <input type="text" name="celular" id="celular" value="<?= $campo_data['telefono_celular'] ?>">
            </div>
            <div class="form-group">
                <label for="emergencia">Emergencias</label>
                <input type="text" name="emergencia" id="emergencia" value="<?= $campo_data['telefono_emergencias'] ?>">
            </div>
        </div>
        <p class="form-section-title">datos de contacto</p>
        <div class="card-container">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" value="<?= $campo_data['correo_electronico'] ?>">
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" value="<?= $campo_data['domicilio'] ?>">
            </div>
            <div class="form-group">
                <label for="localidad">Localidad</label>
                <input type="text" name="localidad" id="localidad" value="<?= $campo_data['localidad'] ?>">
            </div>
            <div class="check-container">
                <label for="check">Acepto reglamento y condiciones (<a href="">leer</a>)</label>
                <input type="checkbox" name="condiciones" id="check" <?php if($campo_data['ficha_ok']=='OK'){echo 'checked';} ?>>
            </div>
        </div>
        <div class="form-btn-container">
            <button class="boton-guardar form-btn" type="submit" name="actualiza">Guardar cambios</button>
        </div>
    </form>
    <!-- fin cuerpo de pagina -->

    <?php
    // incluir js navbar y cierre d pagina
    require_once 'include/footer.php';
    ?>
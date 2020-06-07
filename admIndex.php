<?php
session_start();
if (!isset($_SESSION['tipo_usuario'])) {
    header('location:index.php');
} else if ($_SESSION['tipo_usuario'] != 'admin') {
    header('location:index.php');
}

// login como admin correcto
require_once 'Conexion.php';
$dbh = new Conexion;
$sth = $dbh->prepare("SELECT `torneo` FROM `torneos` ORDER BY id DESC LIMIT 1");
$sth->execute();
$torneo = $sth->fetch(PDO::FETCH_ASSOC);
$sth = $dbh->prepare("select nombre_equipo from equipos where torneo = :torneo");
$sth->execute([':torneo' => $torneo['torneo']]);
$equipos = $sth->fetchAll(PDO::FETCH_ASSOC);

// incluir cabeceras
require_once 'include/header.php';
?>
<link rel="stylesheet" href="css/admIndex.css">
<?php
// incluir barra de navegacion y apertura de body
require_once 'include/navbar.php';
?>

<!-- inicio selector de quipo -->
<section class="main-container selector-equipo-container">
    <h2 id="torneo-js"><?= $torneo['torneo'] ?></h2>
    <div class="form-selector">
        <select name="selector-equipo" class="selector-equipo" id="selector-equipo">
            <option value="default">-- Seleccionar equipo --</option>

            <?php foreach ($equipos as $equipo) { ?>

                <option value="<?= $equipo['nombre_equipo'] ?>">
                    <?php echo $equipo['nombre_equipo'] ?>
                </option>

            <?php } ?>
        </select>
    </div>
    <div class="card-container">
        <div class="form-group">
            <label for="">institución</label>
            <input type="text" id="input-institucion" readonly value="">
        </div>
        <div class="form-group">
            <label for="">Dirección declarada</label>
            <input type="text" id="input-direccion" readonly value="">
        </div>
        <div class="form-group">
            <label for="">localidad</label>
            <input type="text" id="input-localidad" readonly value="">
        </div>
    </div>
</section>
<!-- fin selector equipo -->

<!-- lista de buena fe -->
<section class="main-container">
    <h2>lista de buena fe</h2>
    <table id="tabla-lista">
        <thead><tr><th>dni</th><th>apellido, nombre (carnet)</th></tr></thead>
        <tbody></tbody>
    </table>
</section>

<script src="scripts/admIndex.js"></script>
<?php
// incluir js de navbar y cierre de etiquetas
require_once 'include/footer.php';
?>
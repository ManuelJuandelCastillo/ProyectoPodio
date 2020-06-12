<?php
session_start();
if (!isset($_SESSION['equipo'])) {
    header('location:index.php');
} else {
    $equipo_torneo = $_SESSION['equipo'];
    require_once 'Conexion.php';
    $dbh = new Conexion;

    // obtener fecha de cierre de modificacion de lista de buena fe y responsables
    $sth = $dbh->prepare('select fecha_cierre_lista_buena_fe from torneos where torneo = :torneo');
    $sth->execute([':torneo' => $equipo_torneo[1]]);
    $fecha_limite = $sth->fetch(PDO::FETCH_ASSOC);
    $fecha_limite = strtotime($fecha_limite['fecha_cierre_lista_buena_fe']);
    $fecha_actual = strtotime(date('d-m-Y', time()));
    // comparando las fechas se comprueba si se pueden realizar modificaciones o no

    // codigo para eliminar jugadora de la lista
    if (isset($_GET['dni-delete'])) {
        $fecha_baja = date('Y-m-d');
        $sth = $dbh->prepare('update lista_buena_fe set marcado_baja = :baja where torneo = :torneo and nombre_equipo = :equipo and documento = :dni');
        $sth->execute([':baja' => $fecha_baja, ':torneo' => $equipo_torneo[1], ':equipo' => $equipo_torneo[0], ':dni' => $_GET['dni-delete']]);
        header('location:' . $_SERVER['PHP_SELF']);
    }

    // aca iria el ccodigo para agregar una jugadora si se presiono el boton submit

    // codigo que trae los datos de equipo y jugadoras
    $sth_lbf = $dbh->prepare('SELECT p.apellidos, p.nombres, p.documento, p.carnet, p.carnet_fmv FROM lista_buena_fe as t join personas as p on t.documento=p.documento WHERE t.nombre_equipo = :equipo and torneo = :torneo and t.marcado_baja is null');
    $sth_lbf->execute([':equipo' => $equipo_torneo[0], ':torneo' => $equipo_torneo[1]]);
}

// incluir header
require_once 'include/header.php';
?>
<link rel="stylesheet" href="css/listaBuenaFe.css">
<?php
// incluir navbar, cierre head y apertura body
require_once 'include/navbar.php';
?>
<section class="main-container">
    <h2>Lista de buena fe</h2>
    <div class="card-container">
        <div class="form-group">
            <label for="name">equipo</label>
            <input type="text" readonly value="<?= $equipo_torneo[0] ?>">
        </div>
        <div class="form-group">
            <label for="apel">categoría</label>
            <input type="text" readonly value="MAXIVOLEY">
        </div>
        <div class="form-group">
            <label for="nac">torneo</label>
            <input type="text" readonly value="<?= $equipo_torneo[1] ?>">
        </div>
    </div>
    <div class="card-container group-links-equipo">
        <div class="group-links">
            <a class="form-btn links-equipo" href="areaResponsables.php">información de equipo</a>
        </div>
        <div class="group-links">
            <a class="form-btn links-equipo" href="responsablesEquipo.php">ver responsables</a>
        </div>
        <div class="group-links">
            <a class="form-btn links-equipo" href="">preferencias hora/cancha</a>
        </div>
    </div>
</section>
<section class="main-container">
    <table>
        <tr id="cabecera-tabla">
            <th>DNI</th>
            <th>Apellido, nombre (carnet - fmv)</th>
        </tr>
        <?php
        while ($jugadora = $sth_lbf->fetch(PDO::FETCH_ASSOC)) {
        ?>
            <tr>
                <td class="columna-dni"><?= $jugadora['documento'] ?></td>
                <td class="columna-nombre"><?= $jugadora['apellidos'] ?>, <?= $jugadora['nombres'] ?> (<?= $jugadora['carnet'] ?> - <?=$jugadora['carnet_fmv']?>)</td>

                <?php
                if ($fecha_actual < $fecha_limite) {
                ?>
                    <td class="columna-eliminar">
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?dni-delete=<?= $jugadora['documento'] ?>">
                            <img class="btn-eliminar-jugadora" src="img/borrar.png">
                        </a>
                    </td>
                <?php
                }
                ?>
            </tr>

        <?php
        }
        ?>
    </table>

    <div class="card-container group-links-equipo">
        <div class="group-links">
            <?php
            if ($fecha_actual < $fecha_limite) {
            ?>
                <button class="form-btn btn-agregar-jugadora" id="btn-agregar-jugadora">jugadora +</button>
            <?php
            }
            ?>
            <a href="lbfpdf.php" target="_blank" rel="noopener noreferrer" class="form-btn btn-agregar-jugadora">imprimir lista</a>
            <a href="planillaEntrenador.php" class="form-btn btn-agregar-jugadora">generar planilla</a>
        </div>
    </div>

</section>

<!-- form modal -->
<section class="seccion-modal">
    <div id="modal-container">
        <div class="modal-form-container">
            <div class="form-card-container">
                <button id="btn-cerrar">
                    <div id="cerrarModal-line1"></div>
                    <div id="cerrarModal-line2"></div>
                </button>
                <form>
                    <div class="card-container">
                        <div class="form-group">
                            <label for="dni">dni</label>
                            <input type="text" name="dni" id="dni">
                        </div>
                        <div class="form-group">
                            <label for="nombre">nombre</label>
                            <input type="text" name="nombre" id="nombre">
                        </div>
                        <div class="form-group">
                            <label for="apellido">apellido</label>
                            <input type="text" name="apellido" id="apellido">
                        </div>
                    </div>
                    <div class="form-btn-container">
                        <button type="submit" class="form-btn">guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="scripts/listaBuenaFe.js"></script>
<?php
// incluir footer con js navbar y cierre de body y html
require_once 'include/footer.php';
?>
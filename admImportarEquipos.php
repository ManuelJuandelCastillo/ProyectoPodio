<?php
session_start();
if ($_SESSION['tipo_usuario'] != 'admin') {
    header('location:index.php');
}

require_once 'Conexion.php';
$dbh = new Conexion;

// si se presiona el boton de importar equipos
if (isset($_POST['importar-equipos'])) {
    $torneoOrigen = $_POST['torneo-origen'];
    $torneoDestino = $_POST['torneo-destino'];

    if (isset($_POST['equipos'])) {
        $equipos = $_POST['equipos'];
        foreach ($equipos as $equipo) {
            // comprobar si existe el equipo en el torneo destino
            $sth = $dbh->prepare('select * from equipos where torneo = :torneo and nombre_equipo = :equipo');
            $sth->execute([':torneo' => $torneoDestino, ':equipo' => $equipo]);
            $existeEquipo = $sth->fetch(PDO::FETCH_ASSOC);

            if (!isset($existeEquipo['nombre_equipo'])) {
                // recuperar datos del equipo a importar
                $sth = $dbh->prepare('select * from equipos where torneo = :torneo and nombre_equipo = :equipo');
                $sth->execute([':torneo' => $torneoOrigen, ':equipo' => $equipo]);
                $dataEquipo = $sth->fetch(PDO::FETCH_ASSOC);

                // recuperar datos de torneo destino
                $sth = $dbh->prepare('select * from torneos where torneo = :torneo');
                $sth->execute([':torneo' => $torneoDestino]);
                $dataTorneoDestino = $sth->fetch(PDO::FETCH_ASSOC);

                // insertar los datos del equipo en el torneo destino
                $sth = $dbh->prepare('insert into equipos (categoria, torneo, anio, nombre_institucion, nombre_equipo, documento_delegada_1, documento_delegada_2, documento_delegada_3, documento_entrenador, direccion, localidad, nivel, dni_ultima_modificacion, fecha_ultima_modificacion) values (:categoria, :torneo, :anio, :institucion, :equipo, :delegada1, :delegada2, :delegada3, :entrenador, :direccion, :localidad, :nivel, :dniMod, :fechaMod)');
                $sth->execute([':categoria'=>$dataTorneoDestino['categoria'], ':torneo'=>$torneoDestino, ':anio'=>$dataTorneoDestino['anio'], ':institucion'=>$dataEquipo['nombre_institucion'], ':equipo'=>$dataEquipo['nombre_equipo'], ':delegada1'=>$dataEquipo['documento_delegada_1'], ':delegada2'=>$dataEquipo['documento_delegada_2'], ':delegada3'=>$dataEquipo['documento_delegada_3'], ':entrenador'=>$dataEquipo['documento_entrenador'], ':direccion'=>$dataEquipo['direccion'], ':localidad'=>$dataEquipo['localidad'], ':nivel'=>$dataEquipo['nivel'], ':dniMod'=>$dataEquipo['dni_ultima_modificacion'], ':fechaMod'=>date('Y-m-d H:i:s')]);

                // recuperar las preferencias del torneo origen
                $sth = $dbh->prepare('select * from preferencias_horarias where torneo = :torneo and equipo = :equipo');
                $sth->execute([':torneo'=>$torneoOrigen, ':equipo'=>$equipo]);
                $preferencias = $sth->fetch(PDO::FETCH_ASSOC);

                // agregar las preferencias en el torneo destino
                $sth = $dbh->prepare('insert into preferencias_horarias (categoria, torneo, equipo, turno_1, turno_2, turno_3, turno_4, turno_5, turno_6, cancha_hora, idcancha, quien_cambio, fecha_hora_cambio) values (:categoria, :torneo, :equipo, :t1, :t2, :t3, :t4, :t5, :t6, :hora, :cancha, :quien, :fechaMod)');
                $sth->execute([':categoria'=>$dataTorneoDestino['categoria'], ':torneo'=>$torneoDestino, ':equipo'=>$equipo, ':t1'=>$preferencias['turno_1'], ':t2'=>$preferencias['turno_2'], ':t3'=>$preferencias['turno_3'], ':t4'=>$preferencias['turno_4'], ':t5'=>$preferencias['turno_5'], ':t6'=>$preferencias['turno_6'], ':hora'=>$preferencias['cancha_hora'], ':cancha'=>$preferencias['idcancha'], ':quien'=>$preferencias['quien_cambio'], ':fechaMod'=>date('Y-m-d H:i:s')]);
            }
        }
    }
    if (isset($_POST['listasBF'])) {
        // recuperar datos de torneo destino
        $sth = $dbh->prepare('select * from torneos where torneo = :torneo');
        $sth->execute([':torneo'=>$torneoDestino]);
        $dataTorneoDestino = $sth->fetch(PDO::FETCH_ASSOC);

        $listaEquipo = $_POST['listasBF'];
        foreach ($listaEquipo as $lista) {
            // recuperar lista de buena fe de torneo origen
            $sth = $dbh->prepare('select * from lista_buena_fe where torneo = :torneo and nombre_equipo = :equipo');
            $sth->execute([':torneo'=>$torneoOrigen, ':equipo'=>$lista]);
            $dataLista = $sth->fetchAll(PDO::FETCH_ASSOC);

            // insertar lista de buena fe para torneo destino
            foreach ($dataLista as $jugadora) {
                $sth = $dbh->prepare('insert into lista_buena_fe (categoria, torneo, nombre_equipo, documento, documento_alta, fecha_alta) values (:categoria, :torneo, :equipo, :dni, :dniAlta, :fechaAlta)');
                $sth->execute([':categoria'=>$dataTorneoDestino['categoria'], ':torneo'=>$dataTorneoDestino['torneo'], ':equipo'=>$lista, ':dni'=>$jugadora['documento'], ':dniAlta'=>$jugadora['documento_alta'], ':fechaAlta'=>date('Y-m-d H:i:s')]);
            }
        }
    }
}

$sth = $dbh->prepare('select * from torneos order by id desc');
$sth->execute();
$listaTorneos = $sth->fetchAll(PDO::FETCH_ASSOC);


// incluir head
require_once 'include/header.php';
?>
<!-- estilo propio de pagina -->
<link rel="stylesheet" href="css/admImportarEquipos.css">
<?php
// incluir cierre del head, apertura de body y navbar
require_once 'include/navbar.php';
?>

<section class="main-container">
    <h2>importar equipos</h2>

    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">

        <div class="card-container">
            <label for="">torneo origen</label>
            <select name="torneo-origen" id="torneo-origen">
                <?php
                foreach ($listaTorneos as $torneo) {
                ?>
                    <option value="<?= $torneo['torneo'] ?>"><?= $torneo['torneo'] ?></option>
                <?php
                }
                ?>
            </select>
            <div class="form-group tabla-container">
                <table>
                    <thead>
                        <th>equipos</th>
                        <th>importar</th>
                        <th>lista buena fe</th>
                    </thead>
                    <tbody id="tabla-import">

                    </tbody>
                </table>
            </div>
        </div>


        <div class="card-container">
            <div class="form-group">
                <label for="">torneo destino</label>
            </div>
            <div class="form-group">
                <select name="torneo-destino" id="torneo-destino">
                    <?php
                    foreach ($listaTorneos as $torneo) {
                    ?>
                        <option value="<?= $torneo['torneo'] ?>"><?= $torneo['torneo'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="btn-container">
            <button type="submit" class="form-btn" name="importar-equipos">importar equipo/s</button>
        </div>
    </form>

</section>

<script src="scripts/admImportarEquipos.js"></script>
<?php
// incluir js propio de pagina y cierre de body
require_once 'include/footer.php';
?>
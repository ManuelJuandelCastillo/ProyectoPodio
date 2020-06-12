<?php
session_start();
if (!isset($_SESSION['equipo'])) {
    header('location:index.php');
} else {
    if (isset($_GET['action'])) {
        require_once 'Conexion.php';
        $dbh = new Conexion;
        $equipo_torneo = $_SESSION['equipo'];

        // obtener fecha de cierre de modificacion de lista de buena fe y responsables
        $sth = $dbh->prepare('select fecha_fin from torneos where torneo = :torneo');
        $sth->execute([':torneo' => $equipo_torneo[1]]);
        $fecha_limite = $sth->fetch(PDO::FETCH_ASSOC);
        $fecha_limite = strtotime($fecha_limite['fecha_fin']);
        $fecha_actual = strtotime(date('d-m-Y', time()));

        if ($_GET['action'] == 'delete' && $fecha_actual<$fecha_limite) {
            switch ($_GET['id']) {
                case '1':
                    $sth = $dbh->prepare('update equipos set documento_delegada_1 = 0 where nombre_equipo = :equipo and torneo = :torneo');
                    $sth->execute([':equipo' => $equipo_torneo[0], ':torneo' => $equipo_torneo[1]]);
                    header('location:responsablesEquipo.php#r1');
                    break;

                case '2':
                    $sth = $dbh->prepare('update equipos set documento_delegada_2 = 0 where nombre_equipo = :equipo and torneo = :torneo');
                    $sth->execute([':equipo' => $equipo_torneo[0], ':torneo' => $equipo_torneo[1]]);
                    header('location:responsablesEquipo.php#r2');
                    break;

                case '3':
                    $sth = $dbh->prepare('update equipos set documento_delegada_3 = 0 where nombre_equipo = :equipo and torneo = :torneo');
                    $sth->execute([':equipo' => $equipo_torneo[0], ':torneo' => $equipo_torneo[1]]);
                    header('location:responsablesEquipo.php#r3');
                    break;

                case '4':
                    $sth = $dbh->prepare('update equipos set documento_entrenador = 0 where nombre_equipo = :equipo and torneo = :torneo');
                    $sth->execute([':equipo' => $equipo_torneo[0], ':torneo' => $equipo_torneo[1]]);
                    header('location:responsablesEquipo.php#r4');
                    break;

                default:
                    header('location:areaResponsables.php');
                    break;
            }
        } elseif ($_GET['action'] == 'update') {
            // codigo para actualizacion de responsable
        } else {
            // no se especifica accion a realizar (entrada no valida a la pagina)
            header('location:areaResponsables.php');
        }
    } else {
        // no existe la variable action en el array get
        header('location:areaResponsables.php');
    }
}

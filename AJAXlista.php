<?php
session_start();
if(!isset($_SESSION['tipo_usuario']) || !isset($_GET['equipo'])){
    header('location:index.php');
}
$equipo = $_GET['equipo'];
$torneo = $_GET['torneo'];

require_once 'Conexion.php';
$dbh = new Conexion;

$sth = $dbh->prepare('SELECT p.apellidos, p.nombres, p.documento, p.carnet FROM lista_buena_fe as t join personas as p on t.documento=p.documento WHERE t.nombre_equipo = :equipo and torneo = :torneo and t.marcado_baja is null');
$sth->execute([':torneo'=> $torneo, ':equipo'=>$equipo]);

$lista = $sth->fetchAll(PDO::FETCH_ASSOC);
$listaJSON = json_encode($lista);

echo $listaJSON;
$dbh = null;
?>
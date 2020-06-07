<?php
session_start();
if(!isset($_SESSION['tipo_usuario']) || !isset($_GET['equipo'])){
    header('location:index.php');
}
$equipo = $_GET['equipo'];
$torneo = $_GET['torneo'];

require_once 'Conexion.php';
$dbh = new Conexion;

$sth = $dbh->prepare('select * from equipos where torneo = :torneo and nombre_equipo= :equipo');
$sth->execute([':torneo'=> $torneo, ':equipo'=>$equipo]);

$equipos = $sth->fetchAll(PDO::FETCH_ASSOC);
$equiposJSON = json_encode($equipos);

echo $equiposJSON;
$dbh = null;
?>
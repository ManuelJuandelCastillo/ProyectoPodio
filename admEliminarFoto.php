<?php
session_start();
if($_SESSION['tipo_usuario']!='admin'){
    header('location:index.php');
}

$dni = $_GET['dni'];
$tipo = $_GET['tipo-imagen'];

require_once 'Conexion.php';
$dbh = new Conexion;
$sth = $dbh->prepare('delete from personas_imagenes where documento = :dni and tipo_imagen = :tipo');
$sth->execute([':dni'=>$dni, ':tipo'=>$tipo]);

if ($tipo == 'foto'){
    $sth = $dbh->prepare("update personas set foto_4x4_ok = 'FALTA' where documento = :dni");
    $sth->execute([':dni'=>$dni]);
}
if ($tipo == 'dni_f'){
    $sth = $dbh->prepare("update personas set dni_frente_ok = 'FALTA' where documento = :dni");
    $sth->execute([':dni'=>$dni]);
}
if ($tipo == 'dni_d'){
    $sth = $dbh->prepare("update personas set dni_dorso_ok = 'FALTA' where documento = :dni");
    $sth->execute([':dni'=>$dni]);
}

header("location:admDatosJugadora.php?dni=$dni");
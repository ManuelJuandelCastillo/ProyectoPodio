<?php
session_start();
if ($_SESSION['tipo_usuario']!='admin'){
    header('location:index.php');
}

$dni = strtoupper($_GET['dni']).'%';
require_once 'Conexion.php';
$dbh = new Conexion;
$sth = $dbh->prepare('select documento, apellidos, nombres, correo_electronico, fecha_nacimiento from personas where documento like :dni or apellidos like :apel');
$sth->execute([':dni'=>$dni, ':apel'=>$dni]);
$lista = $sth->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($lista);
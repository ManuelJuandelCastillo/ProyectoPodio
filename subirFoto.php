<?php
session_start();
if (!isset($_SESSION['dni'])){
    header('location:index.php');
}else{
    if ($_FILES['imagen']['name'] != '') {
         // existe el archivo entonces se generan las variables
         $dni = $_SESSION['dni'];
         $formato = $_FILES['imagen']['type'];
         $tipo = $_POST['tipo_imagen'];
         $tamanio = $_FILES['imagen']['size'];
     
        //  crear el blob
         $archivodestino = $_FILES['imagen']['tmp_name'];
         $imagen = fopen($archivodestino, 'r');
         $img_blob = fread($imagen, $tamanio);

         // se crea la conexion
        require_once 'Conexion.php';
        $dbh = new Conexion;

         // consulta para saber si es primera vez o un cambio de imagen
         $query_consulta = 'select * from personas_imagenes where documento= :dni and tipo_imagen = :tipo';
         $sth = $dbh->prepare($query_consulta);
         $sth->execute([':dni' => $dni, ':tipo' => $tipo]);
         $registro = $sth->fetch(PDO::FETCH_ASSOC);

         if ($registro['tipo_imagen'] == $tipo){
             $sth = $dbh->prepare('update personas_imagenes set imagen=:imagen where documento=:dni and tipo_imagen=:tipo');
             $sth->execute([':imagen'=> $img_blob, ':dni'=>$dni, ':tipo'=>$tipo]);
             echo 'actualizada ' . $registro['tipo_imagen'];
         }else{
             $sth = $dbh->prepare('insert into personas_imagenes(documento, tipo_imagen, imagen, formato) values(:dni, :tipo, :img, :formato)');
             $sth->execute([':dni' => $dni, ':tipo' => $tipo, ':img' => $img_blob, ':formato' => $formato]);
             echo 'alta ' . $registro['tipo_imagen'];
         }
    }
    header('location:index.php');
}
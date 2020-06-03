<?php
session_start();
// comprueba si existe sesion
if (isset($_SESSION['dni'])) {
    header('location:datosPersonales.php');
} else {
    // si no hay sesion, se fija si se envio el form de login
    if (isset($_POST['logear'])) {
        $dni = $_POST['dni'];
        $clave = md5($_POST['pass']);

        require_once 'Conexion.php';
        $conexion = new Conexion;
        $res = $conexion->prepare('select documento, clave from personas where documento = :dni');
        $res->execute([':dni' => $dni]);
        $campo = $res->fetch(PDO::FETCH_ASSOC);

        if (isset($campo['clave'])) {
            if ($clave == $campo['clave']) {
                $_SESSION['dni'] = $dni;

                // logeo correcto - ahora consulta si figura como delegada de algun equipo
                $query = 'select count(*) from equipos where documento_delegada_1 = :dni1 or documento_delegada_2 = :dni2 or documento_delegada_3 = :dni3';
                $resp = $conexion->prepare($query);               
                $resp->execute([':dni1'=>$dni, ':dni2'=>$dni, ':dni3'=>$dni]);
                $reg = $resp->fetch(PDO::FETCH_ASSOC);
                
                // si figura como delegada se le asigna el valor responsable
                $_SESSION['filas']=$reg['count(*)'];
                if ($reg['count(*)'] > 0) {
                    $_SESSION['tipo_usuario']='responsable';
                }else{
                    $_SESSION['tipo_usuario']='no responsable';
                }
                header('location:datosPersonales.php');
            } else {
                // contraseña incorrecta
                 $error = 'usuario y/o contraseña incorrecta';
            }
        } else {
            // usuario incorrecto
            $error = 'usuario y/o contraseña incorrecta';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="icon" href="img/favicon.png" type="image/png" />
    <title>Podio</title>
</head>

<body>
    <section id="main">

        <div class="logo">
            <img src="img/podioWhiteSmoke.png" alt="">
        </div>

        <?php
        if (isset($error)) {
        ?>
            <span id="error"><?= $error ?></span>
        <?php
        }
        ?>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">

            <div class="form-group">
                <label for="dni">usuario</label>
                <input type="text" name="dni" id="dni" autofocus>
            </div>

            <div class="form-group">
                <label for="pass">contraseña</label>
                <input type="password" name="pass" id="pass">
            </div>

            <div class="form-group" id="btn-container">
                <button type="submit" name="logear">iniciar sesión</button>
            </div>

        </form>
        <div class="links">
            <a href="#">Olvidaste tu contraseña?</a>
            <a href="http://www.podio.org.ar">Volver a Podio.org.ar</a>
        </div>
    </section>
</body>

</html>
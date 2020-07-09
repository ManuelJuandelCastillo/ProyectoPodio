<?php
session_start();
if ($_SESSION['tipo_usuario'] != 'admin') {
    header('location:index.php');
}

require_once 'include/header.php';
?>
<?php
require_once 'include/navbar.php';
?>

<section class="main-container">
    <h2>alta/modificación de equipo</h2>
    <div class="card-container">
        <div class="form-group">
            <label for="">categoría</label>
            <select name="categoria" id="">
                <option value="MAXIVOLEY">MAXIVOLEY</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">torneo</label>
            <input type="text" name="torneo" id="">
        </div>
        <div class="form-group">
            <label for="">año</label>
            <input type="text" name="anio" id="">
        </div>
        <div class="form-group">
            <label for="">nombre</label>
            <input type="text" name="nombre">
        </div>
        <div class="form-group">
            <label for="">institucción</label>
            <input type="text" name="institucion">
        </div>
        <div class="form-group">
            <label for="">localidad</label>
            <input type="text" name="localidad" id="">
        </div>
        <div class="form-group">
            <label for="">dni delegade 1</label>
            <input type="text" name="delegado1" id="">
        </div>
    </div>
</section>
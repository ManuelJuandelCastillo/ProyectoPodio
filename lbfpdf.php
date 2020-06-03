<?php
session_start();
if (!isset($_SESSION['equipo'])) {
    header('location:areaResponsables.php');
} else {
    $equipo = $_SESSION['equipo'][0];
    $torneo = $_SESSION['equipo'][1];
    require_once 'Conexion.php';
    $dbh = new Conexion;

    // codigo que trae los datos de equipo y jugadoras
    $sth_lbf = $dbh->prepare('SELECT p.apellidos, p.nombres, p.documento, p.carnet , p.ficha_ok, p.foto_4x4_ok, p.dni_frente_ok, p.dni_dorso_ok FROM lista_buena_fe as t join personas as p on t.documento=p.documento WHERE t.nombre_equipo = :equipo and torneo = :torneo and t.marcado_baja is null');
    $sth_lbf->execute([':equipo' => $equipo, ':torneo' => $torneo]);

    // creacion de pdf
    require_once 'fpdf/fpdf.php';
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',10);

    // logo
    $pdf->Image('img/podioWhite.png', 6,20,60);
    $pdf->Ln(15);

    $pdf->Cell(42,10,'');
    // titulo
    $pdf->SetFont('','B', 20);
    $pdf->Cell(102, 20, 'LISTA DE BUENA FE', 0, 0, 'C');
    // equipo A o B
    $pdf->SetFillColor(42,24,75);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(42,24,75);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('','B', 10);
    $pdf->Cell(42, 8, 'EQUIPO', 'LR', 0, 'C', true);
    $pdf->Ln();
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(64,64,64);
    $pdf->SetDrawColor(200,200,200);
    $pdf->SetFont('');
    $pdf->Cell(144,10,'');
    $pdf->Cell(21,8,'A','LRB',0,'C');
    $pdf->Cell(21,8,'B','LRB',0,'C');
    $pdf->Ln(20);

    // lbf datos equipo y torneo
    $pdf->SetFillColor(42,24,75);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(200,200,200);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('','B',10);

    $cabeceras = ['EQUIPO','TORNEO', 'CATEGORIA'];
    $columnas = [62,62,62];
    for ($i=0; $i < count($cabeceras); $i++) { 
        $pdf->Cell($columnas[$i], 8, $cabeceras[$i] , 1, 0, 'C', true);
    }
    $pdf->Ln();
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(64,64,64);
    $pdf->SetDrawColor(200,200,200);
    $pdf->SetFont('');
    $pdf->Cell($columnas[0],8,$equipo,'LRB',0,'C');
    $pdf->Cell($columnas[1],8,$torneo,'LRB',0,'C');
    $pdf->Cell($columnas[0],8,'MAXIVOLEY','LRB',0,'C');
    $pdf->Ln(20);

    // lbf cabeceras de tabla y ancho de colunas
    $cabeceras = ['dni','apellido, nombre (carnet)', 'ficha', 'foto', 'dni f', 'dni d'];
    $columnas = [28,90,17,17,17,17];

    $pdf->SetFillColor(42,24,75);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(200,200,200);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('','B', 10);
    
    for ($i=0; $i < count($cabeceras); $i++) { 
        $pdf->Cell($columnas[$i], 8, $cabeceras[$i] , 1, 0, 'C', true);
    }
    $pdf->Ln();

    $fill = false;
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(64,64,64);
    $pdf->SetDrawColor(200,200,200);
    $pdf->SetFont('');

    while($jugadora = $sth_lbf->fetch(PDO::FETCH_ASSOC)){
        $pdf->Cell($columnas[0],8,$jugadora['documento'],'LR',0,'C',$fill);
        $pdf->Cell($columnas[1],8,$jugadora['apellidos'] . ', ' . $jugadora['nombres'] . ' (' . $jugadora['carnet'] . ')','LR',0,'L',$fill);
        $pdf->Cell($columnas[2],8,$jugadora['ficha_ok'],'LR',0,'C',$fill);
        $pdf->Cell($columnas[3],8,$jugadora['foto_4x4_ok'],'LR',0,'C',$fill);
        $pdf->Cell($columnas[4],8,$jugadora['dni_frente_ok'],'LR',0,'C',$fill);
        $pdf->Cell($columnas[5],8,$jugadora['dni_dorso_ok'],'LR',0,'C',$fill);
        $pdf->Ln();
        $fill = !$fill;
    }  
    $pdf->Output();
}
?>
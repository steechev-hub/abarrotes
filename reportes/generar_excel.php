<?php

session_start();

include("../config/db.php");

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$tipo = $_GET['tipo'] ?? 'dia';

$fecha = $_GET['fecha'] ?? date('Y-m-d');

$where = "";

if($tipo == 'dia'){

    $where = "DATE(v.fecha) = '$fecha'";
}

elseif($tipo == 'semana'){

    $where = "YEARWEEK(v.fecha,1)=YEARWEEK('$fecha',1)";
}

elseif($tipo == 'mes'){

    $where = "MONTH(v.fecha)=MONTH('$fecha')
              AND YEAR(v.fecha)=YEAR('$fecha')";
}

elseif($tipo == 'anio'){

    $where = "YEAR(v.fecha)=YEAR('$fecha')";
}

/*
⚠️ CAMBIA detalle_venta
SI TU TABLA TIENE OTRO NOMBRE
*/

$sql = "

SELECT

p.codigo_barras,
p.nombre,

dv.cantidad,

p.precio_compra,
p.precio_venta,

(dv.cantidad * p.precio_venta) AS total_venta,

(dv.cantidad * p.precio_compra) AS total_costo,

(
(dv.cantidad * p.precio_venta)
-
(dv.cantidad * p.precio_compra)
) AS utilidad,

v.fecha

FROM detalle_venta dv

INNER JOIN productos p
ON dv.producto_id = p.id

INNER JOIN ventas v
ON dv.venta_id = v.id

WHERE $where

ORDER BY v.fecha DESC

";

$ventas = $conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   CREAR EXCEL
========================= */

$excel = new Spreadsheet();

$sheet = $excel->getActiveSheet();

$sheet->setTitle("Reporte Ventas");

/* =========================
   TITULOS
========================= */

$sheet->setCellValue('A1', 'REPORTE DE VENTAS');

$sheet->mergeCells('A1:I1');

$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);

$sheet->getStyle('A1')->getAlignment()
->setHorizontal(Alignment::HORIZONTAL_CENTER);

/* =========================
   ENCABEZADOS
========================= */

$fila = 3;

$headers = [

    'Código',
    'Producto',
    'Cantidad',

    'Costo Unitario',
    'Precio Venta',

    'Total Venta',
    'Total Costo',

    'Utilidad',
    'Fecha'

];

$sheet->fromArray($headers, NULL, "A$fila");

/* =========================
   ESTILO ENCABEZADOS
========================= */

$estiloHeader = [

    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF']
    ],

    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '0D6EFD']
    ],

    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER
    ],

    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN
        ]
    ]

];

$sheet->getStyle("A$fila:I$fila")
->applyFromArray($estiloHeader);

/* =========================
   DATOS
========================= */

$fila++;

$totalVentas = 0;
$totalCostos = 0;
$totalUtilidad = 0;

foreach($ventas as $v){

    $sheet->setCellValue("A$fila", $v['codigo_barras']);

    $sheet->setCellValue("B$fila", $v['nombre']);

    $sheet->setCellValue("C$fila", $v['cantidad']);

    $sheet->setCellValue("D$fila", $v['precio_compra']);

    $sheet->setCellValue("E$fila", $v['precio_venta']);

    $sheet->setCellValue("F$fila", $v['total_venta']);

    $sheet->setCellValue("G$fila", $v['total_costo']);

    $sheet->setCellValue("H$fila", $v['utilidad']);

    $sheet->setCellValue("I$fila", $v['fecha']);

    $totalVentas += $v['total_venta'];

    $totalCostos += $v['total_costo'];

    $totalUtilidad += $v['utilidad'];

    $fila++;
}

/* =========================
   RESUMEN
========================= */

$fila += 2;

$sheet->setCellValue("F$fila", "TOTAL VENTAS");
$sheet->setCellValue("G$fila", $totalVentas);

$fila++;

$sheet->setCellValue("F$fila", "TOTAL COSTOS");
$sheet->setCellValue("G$fila", $totalCostos);

$fila++;

$sheet->setCellValue("F$fila", "UTILIDAD");
$sheet->setCellValue("G$fila", $totalUtilidad);

$sheet->getStyle("F".($fila-2).":G$fila")
->getFont()->setBold(true);

/* =========================
   AUTO SIZE
========================= */

foreach(range('A','I') as $col){

    $sheet->getColumnDimension($col)
    ->setAutoSize(true);
}

/* =========================
   BORDES
========================= */

$sheet->getStyle("A3:I".($fila-3))
->getBorders()
->getAllBorders()
->setBorderStyle(Border::BORDER_THIN);

/* =========================
   DESCARGAR
========================= */

$nombreArchivo = "Reporte_Ventas.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");

header('Cache-Control: max-age=0');

$writer = new Xlsx($excel);

$writer->save('php://output');

exit();
?>
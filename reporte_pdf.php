<?php  
require('fpdf/fpdf.php');
require('db.php');

// Obtener los datos del formulario
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';

// Consultar los folios
$query = "SELECT folio, nombre, status FROM folios ORDER BY folio ASC";
$folios = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Verificar que la consulta ha retornado datos correctos
// Puedes descomentar la siguiente línea para depurar y ver la estructura del arreglo $folios
// var_dump($folios);

// Clasificar folios por estado
$hechos = [];
$no_hechos = [];

foreach ($folios as $f) {
    $line = str_pad($f['folio'], 2, '0', STR_PAD_LEFT) . "_" . $f['nombre'];
    if ($f['status'] === 'hecho') {
        $hechos[] = $f; // Almacenamos el arreglo completo
    } else {
        $no_hechos[] = $f; // Almacenamos el arreglo completo
    }
}

// Crear PDF personalizado
class PDF extends FPDF {
    function Header() {
        // Logo (ajustado a tamaño más pequeño)
        $this->Image('logo.png', 10, 8, 15);

        // Título
        $this->SetFont('Arial', 'B', 14);
        
        // Establecer color azul marino (RGB: 0, 0, 128)
        $this->SetTextColor(0, 0, 128); // Azul marino
        $this->Cell(0, 8, utf8_decode('Cámara de Comercio de Huancayo'), 0, 1, 'C');

        // Subtítulo
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 6, utf8_decode('Gestión de Folios - Reporte de Carpetas'), 0, 1, 'C');

        // Línea decorativa (más gruesa y azul marino)
        $this->Ln(5);
        $this->SetDrawColor(0, 0, 128); // Azul marino
        $this->SetLineWidth(1.5);  // Aumenté el grosor de la línea a 1.5
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }

    function sectionTitle($title) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 0, 128); // Azul marino
        $this->Cell(0, 10, utf8_decode($title), 0, 1, 'L');
        $this->SetTextColor(0); // Restablecer color de texto por defecto
    }

    function folioTable($folios) {
        $this->SetFont('Arial', 'B', 10);
        // Cabecera de la tabla
        $this->Cell(40, 10, utf8_decode('Folio'), 1, 0, 'C');
        $this->Cell(100, 10, utf8_decode('Nombre'), 1, 0, 'C');
        $this->Cell(40, 10, utf8_decode('Estado'), 1, 1, 'C');
        
        $this->SetFont('Arial', '', 10);
        // Cuerpo de la tabla
        foreach ($folios as $folio) {
            $this->Cell(40, 10, utf8_decode($folio['folio']), 1, 0, 'C');
            
            // Limitar el nombre a 30 caracteres (puedes cambiar este valor)
            $nombre = utf8_decode(substr($folio['nombre'], 0, 30));
            $this->Cell(100, 10, $nombre . (strlen($folio['nombre']) > 30 ? '...' : ''), 1, 0, 'C');
            
            $this->Cell(40, 10, utf8_decode($folio['status']), 1, 1, 'C');
        }
        $this->Ln(5);
    }

    function summary($total, $hechosCount, $noHechosCount) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 0, 128); // Azul marino
        $this->Cell(0, 10, utf8_decode('Resumen de Folios'), 0, 1, 'L');
        
        $this->SetFont('Arial', '', 11);
        $hechosPercentage = ($hechosCount / $total) * 100;
        $noHechosPercentage = ($noHechosCount / $total) * 100;

        $this->MultiCell(0, 8, utf8_decode("Total de folios: $total\nFolios hechos: $hechosCount ($hechosPercentage%)\nFolios faltantes: $noHechosCount ($noHechosPercentage%)\n\nEste reporte refleja el avance en la gestión de los folios de la Cámara de Comercio de Huancayo."), 0, 1);
        $this->Ln(10);
    }
}

// Crear objeto PDF
$pdf = new PDF();
$pdf->AddPage();

// Agregar el asunto con los datos del formulario
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode("ASUNTO: INFORME DE FOLIOS"), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Fecha: " . date('Y-m-d')), 0, 1);
$pdf->Cell(0, 10, utf8_decode("DE: $nombre $apellido"), 0, 1);
$pdf->Ln(10);

// Hechos
$pdf->sectionTitle("Carpetas Hechas: " . count($hechos));
$pdf->folioTable($hechos);

// No Hechos
$pdf->sectionTitle("Carpetas Faltantes: " . count($no_hechos));
$pdf->folioTable($no_hechos);

// Resumen
$pdf->summary(count($folios), count($hechos), count($no_hechos));

// Firma
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, utf8_decode("Atentamente, "), 0, 1);
$pdf->Cell(0, 10, utf8_decode("$nombre $apellido"), 0, 1);

// Mostrar PDF en el navegador
$pdf->Output('I', 'Reporte_Carpetas.pdf');
?>

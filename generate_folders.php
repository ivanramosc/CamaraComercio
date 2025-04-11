<?php
include('db.php');

// Verificar si se envió la solicitud para generar carpetas
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperamos los folios no hechos (suponiendo que la columna es 'status' o algún campo similar)
    $query = "SELECT folio, year, nombre FROM folios WHERE status != 'hecho' ORDER BY folio ASC";
    $stmt = $pdo->query($query);
    $folios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Directorio donde se guardarán las carpetas (en el servidor)
    $baseDir = 'uploads/';  // Puedes cambiar esta ruta a cualquier ubicación en el servidor

    // Crear un archivo ZIP para la descarga
    $zip = new ZipArchive();
    $zipFileName = 'folios.zip';
    
    if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
        // Crear carpetas y agregarlas al archivo ZIP
        foreach ($folios as $folio) {
            // Formato de nombre: 01_2019_nombre_folio
            $folderName = str_pad($folio['folio'], 2, '0', STR_PAD_LEFT) . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $folio['nombre']);
            $folderPath = $baseDir . $folderName;

            // Verificar si la carpeta ya existe
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true); // Crear la carpeta
            }

            // Añadir la carpeta al archivo ZIP
            $zip->addEmptyDir($folderName); // Agregar la carpeta al ZIP
        }

        $zip->close();

        // Forzar la descarga del archivo ZIP
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        readfile($zipFileName);
        
        // Eliminar archivo ZIP temporal
        unlink($zipFileName);

        exit();
    } else {
        echo "Error al crear el archivo ZIP.";
    }
}
?>

<?php  
include('db.php');

// Configuración de paginación
$porPagina = 15;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($paginaActual - 1) * $porPagina;

// Contar total de registros
$totalQuery = "SELECT COUNT(*) FROM folios";
$totalFolios = $pdo->query($totalQuery)->fetchColumn();
$totalPaginas = ceil($totalFolios / $porPagina);

// Recuperar folios paginados
$query = "SELECT id, folio, year, status, nombre FROM folios ORDER BY folio ASC LIMIT $inicio, $porPagina";
$stmt = $pdo->query($query);
$folios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Conteo de estados
$queryCount = "SELECT status, COUNT(*) AS count FROM folios GROUP BY status";
$stmtCount = $pdo->query($queryCount);
$statusCount = $stmtCount->fetchAll(PDO::FETCH_ASSOC);

$statusReport = ['hecho' => 0, 'no-hecho' => 0];
foreach ($statusCount as $status) {
    $statusReport[$status['status']] = $status['count'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Selección de Folios</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
  </head>
<body>
<div class="container mt-5">
    <div class="encabezado d-flex align-items-center mb-4">
        <img src="logo.png" alt="Logo CCH" class="mr-3" style="height: 60px;">
        <div>
            <h1 class="mb-0" style="font-size: 1.5rem;">Cámara de Comercio de Huancayo</h1>
            <p class="mb-0">Gestión de Folios</p>
        </div>
    </div>

    <div class="reporte">
      <p><strong>Hechos:</strong> <span id="status-hecho-count"><?= $statusReport['hecho'] ?></span></p>
      <p><strong>Faltan:</strong> <span id="status-no-hecho-count"><?= $statusReport['no-hecho'] ?></span></p>
      <form action="generate_folders.php" method="POST">
        <button type="submit" class="btn btn-success">Generar Carpetas <i class="ri-folder-line"></i></button>
      </form>
    </div>
<!-- Botón para abrir la ventana modal -->
<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reportModal">
    Generar Reporte PDF <i class="ri-file-pdf-line"></i>
</button>

<!-- Ventana Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Generar Reporte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="reporte_pdf.php" method="post" target="_blank">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                  
                    <button type="submit" class="btn btn-primary">Generar Reporte</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Agregar los enlaces de Bootstrap CSS y JS para que funcione la ventana modal -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


<!-- Agregar los enlaces de Bootstrap CSS y JS para que funcione la ventana modal -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <form action="insert_folio.php" method="POST" class="reporte mb-4">
        <div class="form-group">
            <label>Año:</label>
            <select name="year" class="form-control" required>
                <?php for ($y = 2012; $y <= 2025; $y++): ?>
                    <option value="<?= $y ?>"><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Número de folios (1-100):</label>
            <input type="number" name="folio_number" class="form-control" min="1" max="100" required>
        </div>
        <button type="submit" class="btn btn-primary">Generar Folios <i class="ri-arrow-left-circle-line"></i></button>
    </form>
    
  

    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 60%;">Folio</th>
                <th style="width: 2%;">Estado</th>
                <th style="width: 2%;">Insertar Nombre</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($folios as $folio): ?>
                <tr id="folio-<?= $folio['id'] ?>">
                    <td title="<?= htmlspecialchars($folio['folio']) ?>">
                        <!-- Mostrar el folio completo con el formato deseado -->
                        <?= str_pad($folio['folio'], 2, '0', STR_PAD_LEFT) . "_" . htmlspecialchars($folio['nombre']) ?>
                    </td>
                    <td class="<?= $folio['status'] ?>" id="status-<?= $folio['id'] ?>">
                        <!-- Usar los iconos de Remix Icon con clases para color -->
                        <i class="ri-checkbox-<?= $folio['status'] == 'hecho' ? 'line' : 'blank-line' ?> <?= $folio['status'] == 'hecho' ? 'hecho' : 'no-hecho' ?>" 
                           data-folio-id="<?= $folio['id'] ?>"
                           onclick="toggleStatus(<?= $folio['id'] ?>)"></i>
                           
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="openEditModal(<?= $folio['id'] ?>, '<?= htmlspecialchars($folio['nombre'], ENT_QUOTES) ?>')"><i class="    ri-file-edit-line"></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php if ($paginaActual > 1): ?>
                <li class="page-item"><a class="page-link" href="?pagina=<?= $paginaActual - 1 ?>">Anterior</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($paginaActual < $totalPaginas): ?>
                <li class="page-item"><a class="page-link" href="?pagina=<?= $paginaActual + 1 ?>">Siguiente</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<!-- Modal para editar nombre -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="modalForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Insertar nombre al folio</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="folioId">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" class="form-control" id="folioName" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="assets/script.js"></script>
</body>
</html>

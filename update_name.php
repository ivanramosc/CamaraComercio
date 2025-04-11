<?php 
include('db.php');

if (isset($_POST['id'], $_POST['name'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);

    $stmt = $pdo->prepare("SELECT folio FROM folios WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $baseParts = explode('_', $result['folio']);
        $folioBase = $baseParts[0] . '_' . $baseParts[1];
        $nuevoFolio = $name !== '' ? $folioBase . '_' . $name : $folioBase;

        $update = $pdo->prepare("UPDATE folios SET nombre = :nombre, folio = :folio WHERE id = :id");
        $update->execute(['nombre' => $name, 'folio' => $nuevoFolio, 'id' => $id]);

        echo $nuevoFolio;
    }
}
?>

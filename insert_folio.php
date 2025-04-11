<?php
include('db.php');

if (isset($_POST['year']) && isset($_POST['folio_number'])) {
    $year = $_POST['year'];
    $max = intval($_POST['folio_number']);

    for ($i = 1; $i <= $max; $i++) {
        $folio_num = str_pad($i, 2, '0', STR_PAD_LEFT);
        $folio = "{$folio_num}_{$year}";

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM folios WHERE folio = :folio");
        $stmt->execute(['folio' => $folio]);

        if ($stmt->fetchColumn() == 0) {
            $insert = $pdo->prepare("INSERT INTO folios (folio, year, status, nombre) VALUES (:folio, :year, 'no-hecho', '')");

            $insert->execute(['folio' => $folio, 'year' => $year]);
        }
    }

    header("Location: index.php");
    exit;
}
?>

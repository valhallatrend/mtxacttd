<?php
header('Content-Type: application/json');

try {
    $cuenta = $_GET['cuenta'] ?? null;

    if (!$cuenta) {
        throw new Exception("Parámetro 'cuenta' no recibido.");
    }

    $db = new PDO('sqlite:/app/db/licencias.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare('SELECT * FROM licencias WHERE cuenta = :cuenta');
    $stmt->execute([':cuenta' => $cuenta]);
    $licencia = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$licencia) {
        echo json_encode(['valido' => false, 'mensaje' => 'Licencia no encontrada']);
        exit;
    }

    if ($licencia['estado'] !== 'activo') {
        echo json_encode(['valido' => false, 'mensaje' => 'Licencia inactiva']);
        exit;
    }

    echo json_encode([
        'valido' => true,
        'cuenta' => $licencia['cuenta'],
        'tipo' => $licencia['tipo'],
        'expira' => $licencia['expira'],
        'max_posiciones' => $licencia['max_posiciones'],
        'email' => $licencia['email'],
        'comentario' => $licencia['comentario'],
        'estado' => $licencia['estado']
    ]);

} catch (Exception $e) {
    echo json_encode(['valido' => false, 'error' => $e->getMessage()]);
}

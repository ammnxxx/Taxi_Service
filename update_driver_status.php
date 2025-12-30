<?php
require_once 'config/database.php';

header('Content-Type: application/json');

// Verificar que sea una petición POST y que el usuario esté logueado
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit;
}

$status = $_POST['status'] ?? '';
$user_id = $_SESSION['user_id'];

if (!in_array($status, ['activo', 'inactivo', 'descanso', 'ocupado'])) {
    echo json_encode(['success' => false, 'message' => 'Estado no válido']);
    exit;
}

$conn = conectarDB();

// Actualizar estado del conductor
$sql = "UPDATE conductores SET modo_trabajo = ? WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $user_id);

if ($stmt->execute()) {
    // Actualizar sesión
    $_SESSION['usuario']['modo_trabajo'] = $status;
    
    echo json_encode([
        'success' => true,
        'message' => 'Estado actualizado correctamente'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado']);
}

$stmt->close();
$conn->close();
?>
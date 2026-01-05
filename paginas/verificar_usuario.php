<?php
require_once '../config/database.php';
$conn = conectarDB();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['existe' => false]);
    exit;
}

$campo = $_POST['campo'] ?? '';
$valor = $_POST['valor'] ?? '';

if (empty($campo) || empty($valor)) {
    echo json_encode(['existe' => false]);
    exit;
}

// Solo permitir verificar estos campos
$camposPermitidos = ['nusuario', 'telefono'];
if (!in_array($campo, $camposPermitidos)) {
    echo json_encode(['existe' => false]);
    exit;
}

// Verificar si el valor existe
$query = "SELECT id FROM usuarios WHERE $campo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $valor);
$stmt->execute();
$result = $stmt->get_result();

$existe = $result->num_rows > 0;

echo json_encode(['existe' => $existe]);

$stmt->close();
$conn->close();
?>
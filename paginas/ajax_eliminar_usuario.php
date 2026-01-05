<?php
require_once '../config/database.php';
$conn = conectarDB();

header('Content-Type: application/json');

// Capturamos el ID desde el parámetro 'op' de la URL
$id = $_GET['op'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'No se proporcionó un ID válido en el parámetro op.']);
    exit;
}

// 1. Obtener la ruta de la foto antes de borrar (para limpieza de archivos)
$queryFoto = "SELECT foto_url FROM usuarios WHERE id = ?";
$stmtFoto = $conn->prepare($queryFoto);
$stmtFoto->bind_param("i", $id);
$stmtFoto->execute();
$resultado = $stmtFoto->get_result();
$usuario = $resultado->fetch_assoc();
$stmtFoto->close();

if (!$usuario) {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
    exit;
}

// 2. Ejecutar la eliminación física del registro
$queryDel = "DELETE FROM usuarios WHERE id = ?";
$stmtDel = $conn->prepare($queryDel);
$stmtDel->bind_param("i", $id);

if ($stmtDel->execute()) {
    // 3. Borrar la foto del servidor si existe
    if (!empty($usuario['foto_url'])) {
        // Si la ruta guardada es relativa, asegúrate de que el path sea correcto para unlink
        if (file_exists($usuario['foto_url'])) {
            unlink($usuario['foto_url']);
        }
    }
    echo json_encode(['success' => true, 'message' => 'Usuario eliminado con éxito.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $conn->error]);
}

$stmtDel->close();
$conn->close();
?>
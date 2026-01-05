<?php
require_once '../config/database.php';

$conn = conectarDB();

header('Content-Type: application/json');

// 1. Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// 2. Obtener datos del formulario
$nombre    = $_POST['u_reg_nombre'] ?? '';
$apellido  = $_POST['u_reg_apellido'] ?? '';
$nusuario  = $_POST['u_reg_nusuario'] ?? '';
$email     = $_POST['u_reg_email'] ?? '';
$telefono  = $_POST['u_reg_tel'] ?? '';
$tipo      = $_POST['u_reg_tipo'] ?? 'pasajero';
$direccion = $_POST['u_reg_dir'] ?? '';
$ubicacion = $_POST['u_reg_gps'] ?? '';
$password  = $_POST['u_reg_pass'] ?? '';

// 3. Validar campos obligatorios
if (empty($nombre) || empty($apellido) || empty($nusuario) || empty($telefono) || empty($password) || empty($direccion) || empty($ubicacion)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados']);
    exit;
}

// 4. Validar que las contraseñas coincidan
if ($_POST['u_reg_pass'] !== $_POST['u_reg_pass2']) {
    echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
    exit;
}

// 5. Verificar si el usuario ya existe
$query = "SELECT id FROM usuarios WHERE nusuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nusuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya está registrado']);
    exit;
}
$stmt->close();

// 6. Verificar si el teléfono ya existe
$query = "SELECT id FROM usuarios WHERE telefono = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $telefono);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'El número de teléfono ya está registrado']);
    exit;
}
$stmt->close();

// 7. Hashear la contraseña
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// 8. Manejar la subida de la foto
$foto_url = null;
if (isset($_FILES['u_reg_foto']) && $_FILES['u_reg_foto']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/usuarios/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    $fileType = mime_content_type($_FILES['u_reg_foto']['tmp_name']);
    
    if (in_array($fileType, $allowedTypes)) {
        $fileName = uniqid() . '_' . basename($_FILES['u_reg_foto']['name']);
        $uploadFilePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['u_reg_foto']['tmp_name'], $uploadFilePath)) {
            $foto_url = $uploadFilePath;
        }
    }
}

// 9. Insertar usuario en la base de datos
// Nota: Se han eliminado UUID sobrantes y se ajustaron los ? a 10 (NOW() no lleva ?)
$query = "INSERT INTO usuarios (nusuario, nombre, apellido, email, telefono, password_hash, tipo, foto_url, direccion, ubicacion, fecha_registro) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($query);

// "ssssssssss" representa 10 strings para los 10 signos de interrogación
$stmt->bind_param("ssssssssss", 
    $nusuario,      // 1
    $nombre,        // 2
    $apellido,      // 3
    $email,         // 4
    $telefono,      // 5
    $password_hash, // 6
    $tipo,          // 7
    $foto_url,      // 8
    $direccion,     // 9
    $ubicacion      // 10
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar usuario: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
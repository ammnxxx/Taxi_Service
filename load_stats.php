<?php
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode([]);
    exit;
}

$user_type = $_POST['user_type'] ?? 'pasajero';
$user_id = $_POST['user_id'] ?? 0;
$conn = conectarDB();

$stats = [];

switch ($user_type) {
    case 'pasajero':
        // Viajes totales
        $sql = "SELECT COUNT(*) as total FROM viajes WHERE pasajero_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stats['total_rides'] = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();
        
        // Total gastado
        $sql = "SELECT COALESCE(SUM(tarifa_usd), 0) as total FROM viajes 
                WHERE pasajero_id = ? AND estado = 'completado'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stats['total_spent'] = number_format($stmt->get_result()->fetch_assoc()['total'], 2);
        $stmt->close();
        
        // Calificación promedio
        $sql = "SELECT AVG(calificacion_pasajero) as avg FROM viajes 
                WHERE pasajero_id = ? AND calificacion_pasajero IS NOT NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stats['avg_rating'] = number_format($stmt->get_result()->fetch_assoc()['avg'] ?? 0, 1);
        $stmt->close();
        
        // Viajes activos
        $sql = "SELECT COUNT(*) as total FROM viajes 
                WHERE pasajero_id = ? AND estado IN ('aceptado', 'en_camino', 'en_proceso')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stats['active_rides'] = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();
        break;
        
    case 'conductor':
        // Obtener ID del conductor
        $sql = "SELECT id FROM conductores WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $conductor_id = $stmt->get_result()->fetch_assoc()['id'] ?? 0;
        $stmt->close();
        
        if ($conductor_id) {
            // Viajes completados
            $sql = "SELECT COUNT(*) as total FROM viajes 
                    WHERE conductor_id = ? AND estado = 'completado'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $conductor_id);
            $stmt->execute();
            $stats['completed_rides'] = $stmt->get_result()->fetch_assoc()['total'];
            $stmt->close();
            
            // Ganancias totales
            $sql = "SELECT COALESCE(SUM(tarifa_usd), 0) as total FROM viajes 
                    WHERE conductor_id = ? AND estado = 'completado'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $conductor_id);
            $stmt->execute();
            $stats['total_earnings'] = number_format($stmt->get_result()->fetch_assoc()['total'] * 0.8, 2); // 80% para el conductor
            $stmt->close();
            
            // Calificación promedio
            $sql = "SELECT AVG(calificacion_conductor) as avg FROM viajes 
                    WHERE conductor_id = ? AND calificacion_conductor IS NOT NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $conductor_id);
            $stmt->execute();
            $stats['avg_rating'] = number_format($stmt->get_result()->fetch_assoc()['avg'] ?? 0, 1);
            $stmt->close();
            
            // Viajes disponibles (cercanos)
            $stats['available_rides'] = rand(0, 5); // Simulado
        }
        break;
        
    case 'admin':
    case 'operador':
        // Usuarios totales
        $sql = "SELECT COUNT(*) as total FROM usuarios";
        $stats['total_users'] = $conn->query($sql)->fetch_assoc()['total'];
        
        // Viajes activos
        $sql = "SELECT COUNT(*) as total FROM viajes 
                WHERE estado IN ('aceptado', 'en_camino', 'en_proceso', 'solicitado')";
        $stats['active_rides'] = $conn->query($sql)->fetch_assoc()['total'];
        
        // Ingresos del día
        $sql = "SELECT COALESCE(SUM(tarifa_usd), 0) as total FROM viajes 
                WHERE DATE(fecha_solicitud) = CURDATE() AND estado = 'completado'";
        $stats['daily_revenue'] = number_format($conn->query($sql)->fetch_assoc()['total'], 2);
        
        // Conductores activos
        $sql = "SELECT COUNT(*) as total FROM conductores WHERE modo_trabajo = 'activo'";
        $stats['active_drivers'] = $conn->query($sql)->fetch_assoc()['total'];
        break;
}

$conn->close();
echo json_encode($stats);
?>
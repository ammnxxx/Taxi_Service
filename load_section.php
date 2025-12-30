<?php
session_start();
require_once 'config/database.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    echo '<div class="error">Acceso no autorizado</div>';
    exit;
}

$section = $_POST['section'] ?? 'overview';
$user_type = $_POST['user_type'] ?? $_SESSION['user_type'];
$user_id = $_SESSION['user_id'];
$usuario = $_SESSION['usuario'];

$conn = conectarDB();

switch ($section) {
    case 'overview':
        echo '<div class="dashboard-section">
                <div class="section-header">
                    <h2>Resumen General</h2>
                </div>
                <div class="welcome-message">
                    <h3>¡Hola, ' . htmlspecialchars($usuario['nombre']) . '!</h3>
                    <p>Bienvenido al panel de control de Taxi Services.</p>
                </div>';
        
        if ($user_type === 'conductor') {
            // Verificar si tiene licencia válida
            $fecha_licencia = new DateTime($usuario['licencia_valida_hasta'] ?? date('Y-m-d'));
            $hoy = new DateTime();
            $diferencia = $hoy->diff($fecha_licencia);
            
            if ($diferencia->invert) {
                echo '<div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>¡Atención!</strong> Tu licencia ha expirado. Por favor renueva tu licencia para continuar trabajando.
                    </div>';
            } elseif ($diferencia->days < 30) {
                echo '<div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Tu licencia expira en ' . $diferencia->days . ' días. Te recomendamos renovarla pronto.
                    </div>';
            }
        }
        
        echo '</div>';
        break;
        
    case 'request-ride':
        if ($user_type === 'pasajero') {
            echo '<div class="dashboard-section">
                    <div class="section-header">
                        <h2>Solicitar Viaje</h2>
                    </div>
                    <div class="ride-request-form">
                        <div class="input-group">
                            <label for="origen"><i class="fas fa-map-marker-alt"></i> Origen</label>
                            <input type="text" id="origen" placeholder="¿Dónde estás?" class="address-input">
                            <button class="btn-location" id="btnCurrentLocation">
                                <i class="fas fa-crosshairs"></i> Usar mi ubicación
                            </button>
                        </div>
                        
                        <div class="input-group">
                            <label for="destino"><i class="fas fa-flag-checkered"></i> Destino</label>
                            <input type="text" id="destino" placeholder="¿A dónde vas?" class="address-input">
                        </div>
                        
                        <div class="input-group">
                            <label><i class="fas fa-car"></i> Tipo de Servicio</label>
                            <div class="service-types">
                                <label class="service-type">
                                    <input type="radio" name="service_type" value="economico" checked>
                                    <div class="service-icon">
                                        <i class="fas fa-taxi"></i>
                                    </div>
                                    <span>Económico</span>
                                    <small>$5 base</small>
                                </label>
                                <label class="service-type">
                                    <input type="radio" name="service_type" value="premium">
                                    <div class="service-icon premium">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <span>Premium</span>
                                    <small>$8 base</small>
                                </label>
                                <label class="service-type">
                                    <input type="radio" name="service_type" value="grupo">
                                    <div class="service-icon group">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <span>Grupo</span>
                                    <small>$12 base</small>
                                </label>
                            </div>
                        </div>
                        
                        <div class="estimated-fare">
                            <h4>Tarifa Estimada: <span id="estimatedFare">$0.00</span></h4>
                            <small>La tarifa final puede variar según la ruta y el tráfico</small>
                        </div>
                        
                        <button class="btn btn-primary btn-block" id="btnRequestRide">
                            <i class="fas fa-taxi"></i> Solicitar Viaje
                        </button>
                    </div>
                </div>';
        } else {
            echo '<div class="error">Acceso no permitido para este tipo de usuario</div>';
        }
        break;
        
    case 'my-rides':
        if ($user_type === 'pasajero') {
            // Obtener viajes del pasajero
            $sql = "SELECT v.*, c.nombre as conductor_nombre, c.apellido as conductor_apellido,
                           ve.marca, ve.modelo, ve.color, ve.placa
                    FROM viajes v
                    LEFT JOIN conductores c ON v.conductor_id = c.id
                    LEFT JOIN vehiculos ve ON v.vehiculo_id = ve.id
                    WHERE v.pasajero_id = ?
                    ORDER BY v.fecha_solicitud DESC
                    LIMIT 10";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            echo '<div class="dashboard-section">
                    <div class="section-header">
                        <h2>Mis Viajes</h2>
                        <div class="ride-filters">
                            <select id="rideFilter">
                                <option value="all">Todos los viajes</option>
                                <option value="completado">Completados</option>
                                <option value="en_proceso">En proceso</option>
                                <option value="cancelado">Cancelados</option>
                            </select>
                        </div>
                    </div>';
            
            if ($result->num_rows > 0) {
                echo '<div class="rides-list">';
                while ($ride = $result->fetch_assoc()) {
                    $estado_class = '';
                    switch ($ride['estado']) {
                        case 'completado': $estado_class = 'completed'; break;
                        case 'en_proceso': 
                        case 'en_camino': 
                        case 'aceptado': $estado_class = 'active'; break;
                        case 'cancelado': $estado_class = 'cancelled'; break;
                        default: $estado_class = 'pending';
                    }
                    
                    echo '<div class="ride-item ' . $estado_class . '">
                            <div class="ride-header">
                                <div class="ride-date">
                                    <strong>' . date('d/m/Y', strtotime($ride['fecha_solicitud'])) . '</strong>
                                    <span>' . date('H:i', strtotime($ride['fecha_solicitud'])) . '</span>
                                </div>
                                <div class="ride-status">
                                    <span class="status-badge">' . ucfirst($ride['estado']) . '</span>
                                </div>
                            </div>
                            <div class="ride-info">
                                <div class="ride-locations">
                                    <div class="location">
                                        <i class="fas fa-circle"></i>
                                        <span>' . htmlspecialchars($ride['origen_direccion']) . '</span>
                                    </div>
                                    <div class="location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>' . htmlspecialchars($ride['destino_direccion']) . '</span>
                                    </div>
                                </div>
                                <div class="ride-details">
                                    <div class="detail">
                                        <i class="fas fa-road"></i>
                                        <span>' . ($ride['distancia_real_km'] ?? $ride['distancia_estimada_km'] ?? '0') . ' km</span>
                                    </div>
                                    <div class="detail">
                                        <i class="fas fa-clock"></i>
                                        <span>' . ($ride['duracion_real_minutos'] ?? $ride['duracion_estimada_minutos'] ?? '0') . ' min</span>
                                    </div>
                                    <div class="detail">
                                        <i class="fas fa-dollar-sign"></i>
                                        <span>$' . $ride['tarifa_usd'] . '</span>
                                    </div>
                                </div>';
                    
                    if ($ride['conductor_nombre']) {
                        echo '<div class="ride-driver">
                                <div class="driver-info">
                                    <i class="fas fa-user"></i>
                                    <span>' . htmlspecialchars($ride['conductor_nombre'] . ' ' . $ride['conductor_apellido']) . '</span>
                                </div>
                                <div class="vehicle-info">
                                    <i class="fas fa-car"></i>
                                    <span>' . htmlspecialchars($ride['marca'] . ' ' . $ride['modelo'] . ' (' . $ride['placa'] . ')') . '</span>
                                </div>
                            </div>';
                    }
                    
                    echo '</div></div>';
                }
                echo '</div>';
            } else {
                echo '<div class="empty-state">
                        <i class="fas fa-taxi"></i>
                        <h4>No has realizado viajes aún</h4>
                        <p>Solicita tu primer viaje desde la opción "Solicitar Viaje"</p>
                    </div>';
            }
            
            echo '</div>';
            $stmt->close();
        }
        break;
        
    case 'available-rides':
        if ($user_type === 'conductor') {
            // Obtener ID del conductor
            $sql_conductor = "SELECT id FROM conductores WHERE usuario_id = ?";
            $stmt_conductor = $conn->prepare($sql_conductor);
            $stmt_conductor->bind_param("i", $user_id);
            $stmt_conductor->execute();
            $conductor = $stmt_conductor->get_result()->fetch_assoc();
            $conductor_id = $conductor['id'] ?? 0;
            $stmt_conductor->close();
            
            if ($conductor_id) {
                // Verificar si el conductor está activo
                $sql_status = "SELECT modo_trabajo FROM conductores WHERE id = ?";
                $stmt_status = $conn->prepare($sql_status);
                $stmt_status->bind_param("i", $conductor_id);
                $stmt_status->execute();
                $status_result = $stmt_status->get_result()->fetch_assoc();
                $modo_trabajo = $status_result['modo_trabajo'] ?? 'inactivo';
                $stmt_status->close();
                
                if ($modo_trabajo !== 'activo') {
                    echo '<div class="dashboard-section">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-circle"></i>
                                <h4>No estás disponible para recibir viajes</h4>
                                <p>Cambia tu estado a "Activo" en el panel lateral para comenzar a recibir solicitudes de viaje.</p>
                                <button class="btn btn-primary" id="btnGoActive">
                                    <i class="fas fa-power-off"></i> Activar modo trabajo
                                </button>
                            </div>
                        </div>';
                } else {
                    // Obtener viajes disponibles
                    $sql = "SELECT v.*, u.nombre as pasajero_nombre, u.apellido as pasajero_apellido,
                                   u.foto_url as pasajero_foto
                            FROM viajes v
                            INNER JOIN usuarios u ON v.pasajero_id = u.id
                            WHERE v.estado IN ('solicitado', 'buscando_conductor')
                            AND v.tipo_servicio IN (
                                SELECT tipo_servicio FROM vehiculos 
                                WHERE conductor_id = ? AND activo = 1
                            )
                            ORDER BY v.fecha_solicitud DESC
                            LIMIT 10";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    echo '<div class="dashboard-section">
                            <div class="section-header">
                                <h2>Viajes Disponibles</h2>
                                <div class="refresh-controls">
                                    <button class="btn-refresh" id="btnRefreshRides">
                                        <i class="fas fa-sync-alt"></i> Actualizar
                                    </button>
                                </div>
                            </div>';
                    
                    if ($result->num_rows > 0) {
                        echo '<div class="available-rides-list">';
                        while ($ride = $result->fetch_assoc()) {
                            // Calcular distancia aproximada (en producción usarías coordenadas reales)
                            $distancia_km = rand(1, 20);
                            $tarifa_aproximada = $distancia_km * 1.5 + 3;
                            
                            echo '<div class="available-ride-item" data-ride-id="' . $ride['id'] . '">
                                    <div class="ride-passenger">
                                        <div class="passenger-avatar">';
                            
                            if ($ride['pasajero_foto']) {
                                echo '<img src="' . htmlspecialchars($ride['pasajero_foto']) . '" alt="' . htmlspecialchars($ride['pasajero_nombre']) . '">';
                            } else {
                                echo '<i class="fas fa-user"></i>';
                            }
                            
                            echo '</div>
                                        <div class="passenger-info">
                                            <h4>' . htmlspecialchars($ride['pasajero_nombre'] . ' ' . $ride['pasajero_apellido']) . '</h4>
                                            <div class="passenger-rating">
                                                <i class="fas fa-star"></i>
                                                <span>4.8</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ride-details">
                                        <div class="detail">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <div>
                                                <small>Desde</small>
                                                <p>' . htmlspecialchars(substr($ride['origen_direccion'], 0, 40)) . '...</p>
                                            </div>
                                        </div>
                                        <div class="detail">
                                            <i class="fas fa-flag-checkered"></i>
                                            <div>
                                                <small>Hasta</small>
                                                <p>' . htmlspecialchars(substr($ride['destino_direccion'], 0, 40)) . '...</p>
                                            </div>
                                        </div>
                                        <div class="detail">
                                            <i class="fas fa-road"></i>
                                            <div>
                                                <small>Distancia</small>
                                                <p>' . $distancia_km . ' km</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ride-footer">
                                        <div class="ride-fare">
                                            <h3>$' . number_format($tarifa_aproximada, 2) . '</h3>
                                            <small>Tarifa aproximada</small>
                                        </div>
                                        <button class="btn-accept-ride" data-ride-id="' . $ride['id'] . '">
                                            <i class="fas fa-check"></i> Aceptar
                                        </button>
                                    </div>
                                </div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<div class="empty-state">
                                <i class="fas fa-search"></i>
                                <h4>No hay viajes disponibles en este momento</h4>
                                <p>Los viajes aparecerán aquí cuando los pasajeros los soliciten</p>
                            </div>';
                    }
                    
                    echo '</div>';
                    $stmt->close();
                }
            }
        }
        break;
        
    case 'active-rides':
        if ($user_type === 'conductor') {
            // Obtener ID del conductor
            $sql_conductor = "SELECT id FROM conductores WHERE usuario_id = ?";
            $stmt_conductor = $conn->prepare($sql_conductor);
            $stmt_conductor->bind_param("i", $user_id);
            $stmt_conductor->execute();
            $conductor = $stmt_conductor->get_result()->fetch_assoc();
            $conductor_id = $conductor['id'] ?? 0;
            $stmt_conductor->close();
            
            if ($conductor_id) {
                // Obtener viaje activo
                $sql = "SELECT v.*, u.nombre as pasajero_nombre, u.apellido as pasajero_apellido,
                               u.telefono as pasajero_telefono, u.foto_url as pasajero_foto
                        FROM viajes v
                        INNER JOIN usuarios u ON v.pasajero_id = u.id
                        WHERE v.conductor_id = ? 
                        AND v.estado IN ('aceptado', 'en_camino', 'en_proceso')
                        LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $conductor_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                echo '<div class="dashboard-section">
                        <div class="section-header">
                            <h2>Viaje Activo</h2>
                        </div>';
                
                if ($ride = $result->fetch_assoc()) {
                    // Calcular tiempo estimado (en producción usarías Google Maps API)
                    $tiempo_estimado = $ride['duracion_estimada_minutos'] ?? 15;
                    $distancia_restante = $ride['distancia_estimada_km'] ?? 5;
                    
                    echo '<div class="active-ride-container">
                            <div class="ride-header">
                                <div class="passenger-info">
                                    <div class="passenger-avatar large">';
                    
                    if ($ride['pasajero_foto']) {
                        echo '<img src="' . htmlspecialchars($ride['pasajero_foto']) . '" alt="' . htmlspecialchars($ride['pasajero_nombre']) . '">';
                    } else {
                        echo '<i class="fas fa-user"></i>';
                    }
                    
                    echo '</div>
                                    <div>
                                        <h3>' . htmlspecialchars($ride['pasajero_nombre'] . ' ' . $ride['pasajero_apellido']) . '</h3>
                                        <div class="contact-info">
                                            <i class="fas fa-phone"></i>
                                            <span>' . htmlspecialchars($ride['pasajero_telefono']) . '</span>
                                            <button class="btn-call">
                                                <i class="fas fa-phone-alt"></i> Llamar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ride-status-indicator">
                                    <span class="status-badge active">' . ucfirst($ride['estado']) . '</span>
                                </div>
                            </div>
                            
                            <div class="ride-map">
                                <div class="map-placeholder">
                                    <i class="fas fa-map"></i>
                                    <p>Mapa del viaje</p>
                                    <small>(Integración con Google Maps)</small>
                                </div>
                            </div>
                            
                            <div class="ride-progress">
                                <div class="progress-steps">
                                    <div class="step active">
                                        <div class="step-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="step-info">
                                            <h5>Aceptado</h5>
                                            <small>' . date('H:i', strtotime($ride['fecha_aceptacion'])) . '</small>
                                        </div>
                                    </div>
                                    <div class="step ' . ($ride['estado'] === 'en_camino' || $ride['estado'] === 'en_proceso' ? 'active' : '') . '">
                                        <div class="step-icon">
                                            <i class="fas fa-car"></i>
                                        </div>
                                        <div class="step-info">
                                            <h5>En camino</h5>
                                            <small>' . ($ride['fecha_inicio'] ? date('H:i', strtotime($ride['fecha_inicio'])) : 'Pendiente') . '</small>
                                        </div>
                                    </div>
                                    <div class="step">
                                        <div class="step-icon">
                                            <i class="fas fa-flag-checkered"></i>
                                        </div>
                                        <div class="step-info">
                                            <h5>Completado</h5>
                                            <small>' . ($ride['fecha_finalizacion'] ? date('H:i', strtotime($ride['fecha_finalizacion'])) : 'Pendiente') . '</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ride-stats">
                                <div class="stat">
                                    <i class="fas fa-road"></i>
                                    <div>
                                        <h4>' . $distancia_restante . ' km</h4>
                                        <small>Distancia restante</small>
                                    </div>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <h4>' . $tiempo_estimado . ' min</h4>
                                        <small>Tiempo estimado</small>
                                    </div>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-dollar-sign"></i>
                                    <div>
                                        <h4>$' . $ride['tarifa_usd'] . '</h4>
                                        <small>Tarifa estimada</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ride-actions">';
                    
                    if ($ride['estado'] === 'aceptado') {
                        echo '<button class="btn btn-success" id="btnStartRide">
                                <i class="fas fa-play"></i> Iniciar Viaje
                              </button>';
                    } elseif ($ride['estado'] === 'en_camino') {
                        echo '<button class="btn btn-success" id="btnCompleteRide">
                                <i class="fas fa-flag-checkered"></i> Finalizar Viaje
                              </button>';
                    }
                    
                    echo '<button class="btn btn-warning" id="btnCancelRide">
                            <i class="fas fa-times"></i> Cancelar Viaje
                          </button>
                            </div>
                        </div>';
                } else {
                    echo '<div class="empty-state">
                            <i class="fas fa-car"></i>
                            <h4>No tienes viajes activos</h4>
                            <p>Busca viajes disponibles en la sección "Viajes Disponibles"</p>
                            <button class="btn btn-primary" id="btnFindRides">
                                <i class="fas fa-search"></i> Buscar Viajes
                            </button>
                        </div>';
                }
                
                echo '</div>';
                $stmt->close();
            }
        }
        break;
        
    case 'earnings':
        if ($user_type === 'conductor') {
            // Obtener ID del conductor
            $sql_conductor = "SELECT id FROM conductores WHERE usuario_id = ?";
            $stmt_conductor = $conn->prepare($sql_conductor);
            $stmt_conductor->bind_param("i", $user_id);
            $stmt_conductor->execute();
            $conductor = $stmt_conductor->get_result()->fetch_assoc();
            $conductor_id = $conductor['id'] ?? 0;
            $stmt_conductor->close();
            
            if ($conductor_id) {
                // Obtener estadísticas de ganancias
                $sql_today = "SELECT COALESCE(SUM(tarifa_usd * 0.8), 0) as total 
                             FROM viajes 
                             WHERE conductor_id = ? 
                             AND DATE(fecha_finalizacion) = CURDATE() 
                             AND estado = 'completado'";
                
                $sql_week = "SELECT COALESCE(SUM(tarifa_usd * 0.8), 0) as total 
                            FROM viajes 
                            WHERE conductor_id = ? 
                            AND YEARWEEK(fecha_finalizacion, 1) = YEARWEEK(CURDATE(), 1)
                            AND estado = 'completado'";
                
                $sql_month = "SELECT COALESCE(SUM(tarifa_usd * 0.8), 0) as total 
                             FROM viajes 
                             WHERE conductor_id = ? 
                             AND MONTH(fecha_finalizacion) = MONTH(CURDATE())
                             AND YEAR(fecha_finalizacion) = YEAR(CURDATE())
                             AND estado = 'completado'";
                
                $stmt_today = $conn->prepare($sql_today);
                $stmt_today->bind_param("i", $conductor_id);
                $stmt_today->execute();
                $ganancias_hoy = $stmt_today->get_result()->fetch_assoc()['total'];
                $stmt_today->close();
                
                $stmt_week = $conn->prepare($sql_week);
                $stmt_week->bind_param("i", $conductor_id);
                $stmt_week->execute();
                $ganancias_semana = $stmt_week->get_result()->fetch_assoc()['total'];
                $stmt_week->close();
                
                $stmt_month = $conn->prepare($sql_month);
                $stmt_month->bind_param("i", $conductor_id);
                $stmt_month->execute();
                $ganancias_mes = $stmt_month->get_result()->fetch_assoc()['total'];
                $stmt_month->close();
                
                // Obtener últimos viajes pagados
                $sql_viajes = "SELECT v.*, p.fecha_pago, p.monto_conductor_usd
                              FROM viajes v
                              INNER JOIN pagos p ON v.id = p.viaje_id
                              WHERE v.conductor_id = ? 
                              AND p.estado = 'completado'
                              ORDER BY p.fecha_pago DESC
                              LIMIT 5";
                $stmt_viajes = $conn->prepare($sql_viajes);
                $stmt_viajes->bind_param("i", $conductor_id);
                $stmt_viajes->execute();
                $viajes = $stmt_viajes->get_result();
                
                echo '<div class="dashboard-section">
                        <div class="section-header">
                            <h2>Mis Ganancias</h2>
                            <div class="period-selector">
                                <select id="earningPeriod">
                                    <option value="today">Hoy</option>
                                    <option value="week" selected>Esta semana</option>
                                    <option value="month">Este mes</option>
                                    <option value="all">Todos</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="earnings-summary">
                            <div class="earning-card">
                                <div class="earning-icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="earning-info">
                                    <h3>$' . number_format($ganancias_hoy, 2) . '</h3>
                                    <p>Ganancias hoy</p>
                                </div>
                            </div>
                            <div class="earning-card">
                                <div class="earning-icon">
                                    <i class="fas fa-calendar-week"></i>
                                </div>
                                <div class="earning-info">
                                    <h3>$' . number_format($ganancias_semana, 2) . '</h3>
                                    <p>Esta semana</p>
                                </div>
                            </div>
                            <div class="earning-card">
                                <div class="earning-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="earning-info">
                                    <h3>$' . number_format($ganancias_mes, 2) . '</h3>
                                    <p>Este mes</p>
                                </div>
                            </div>
                            <div class="earning-card">
                                <div class="earning-icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div class="earning-info">
                                    <h3>$' . number_format($ganancias_mes * 0.7, 2) . '</h3>
                                    <p>Disponible para retiro</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="earnings-chart">
                            <div class="chart-placeholder">
                                <i class="fas fa-chart-line"></i>
                                <p>Gráfico de ganancias</p>
                                <small>(Se integrará con una librería de gráficos)</small>
                            </div>
                        </div>
                        
                        <div class="recent-earnings">
                            <h3>Últimos Pagos</h3>';
                
                if ($viajes->num_rows > 0) {
                    echo '<table class="earnings-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Viaje</th>
                                    <th>Tarifa</th>
                                    <th>Comisión</th>
                                    <th>Ganancia</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>';
                    
                    while ($viaje = $viajes->fetch_assoc()) {
                        $comision = $viaje['tarifa_usd'] * 0.2;
                        $ganancia = $viaje['tarifa_usd'] - $comision;
                        
                        echo '<tr>
                                <td>' . date('d/m/Y', strtotime($viaje['fecha_pago'])) . '</td>
                                <td>#' . $viaje['id'] . '</td>
                                <td>$' . number_format($viaje['tarifa_usd'], 2) . '</td>
                                <td>$' . number_format($comision, 2) . '</td>
                                <td><strong>$' . number_format($ganancia, 2) . '</strong></td>
                                <td><span class="status-badge completed">Pagado</span></td>
                              </tr>';
                    }
                    
                    echo '</tbody></table>';
                } else {
                    echo '<div class="empty-state small">
                            <i class="fas fa-receipt"></i>
                            <h4>No hay pagos registrados</h4>
                            <p>Los pagos aparecerán aquí cuando completes viajes</p>
                        </div>';
                }
                
                echo '</div></div>';
                $stmt_viajes->close();
            }
        }
        break;
        
    case 'profile':
        echo '<div class="dashboard-section">
                <div class="section-header">
                    <h2>Mi Perfil</h2>
                </div>
                <div class="profile-form">
                    <form id="profileForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="' . htmlspecialchars($usuario['nombre']) . '" required>
                            </div>
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <input type="text" id="apellido" name="apellido" value="' . htmlspecialchars($usuario['apellido']) . '">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="' . htmlspecialchars($usuario['email']) . '" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" value="' . htmlspecialchars($usuario['telefono']) . '" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="' . ($usuario['fecha_nacimiento'] ?? '') . '">
                            </div>
                            <div class="form-group">
                                <label for="genero">Género</label>
                                <select id="genero" name="genero">
                                    <option value="">Seleccionar</option>
                                    <option value="masculino" ' . ($usuario['genero'] === 'masculino' ? 'selected' : '') . '>Masculino</option>
                                    <option value="femenino" ' . ($usuario['genero'] === 'femenino' ? 'selected' : '') . '>Femenino</option>
                                    <option value="otro" ' . ($usuario['genero'] === 'otro' ? 'selected' : '') . '>Otro</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="foto_url">Foto de Perfil (URL)</label>
                            <input type="text" id="foto_url" name="foto_url" value="' . htmlspecialchars($usuario['foto_url'] ?? '') . '">
                            <small>Ingresa la URL de tu foto de perfil</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" id="btnSaveProfile">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>';
        break;
        
    default:
        echo '<div class="dashboard-section">
                <div class="section-header">
                    <h2>Sección no encontrada</h2>
                </div>
                <p>La sección solicitada no está disponible o no existe.</p>
            </div>';
        break;
}

$conn->close();

// Agregar estilos CSS para las secciones
echo '<style>
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
    }
    
    .alert-warning {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
    }
    
    .alert-info {
        background-color: #d1ecf1;
        border: 1px solid #bee5eb;
        color: #0c5460;
    }
    
    .alert i {
        margin-right: 10px;
        margin-top: 2px;
    }
    
    .welcome-message {
        text-align: center;
        padding: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    
    .welcome-message h3 {
        font-size: 24px;
        margin-bottom: 10px;
    }
    
    /* Estilos para solicitar viaje */
    .ride-request-form {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .address-input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }
    
    .btn-location {
        background: #f8f9fa;
        border: 1px solid #ddd;
        padding: 8px 15px;
        border-radius: 5px;
        margin-top: 5px;
        cursor: pointer;
        font-size: 14px;
    }
    
    .service-types {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }
    
    .service-type {
        flex: 1;
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .service-type input {
        display: none;
    }
    
    .service-type input:checked + .service-icon {
        border-color: #667eea;
        background: #667eea;
        color: white;
    }
    
    .service-type input:checked ~ span {
        color: #667eea;
        font-weight: bold;
    }
    
    .service-type input:checked {
        border-color: #667eea;
    }
    
    .service-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 10px;
        border: 3px solid transparent;
    }
    
    .service-icon.premium {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .service-icon.group {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    
    .estimated-fare {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        margin: 20px 0;
    }
    
    #estimatedFare {
        color: #667eea;
        font-size: 24px;
        font-weight: bold;
    }
    
    .btn-block {
        display: block;
        width: 100%;
    }
    
    /* Estilos para lista de viajes */
    .ride-filters {
        display: flex;
        gap: 10px;
    }
    
    .rides-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .ride-item {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        background: white;
    }
    
    .ride-item.completed {
        border-left: 4px solid #48bb78;
    }
    
    .ride-item.active {
        border-left: 4px solid #4299e1;
    }
    
    .ride-item.cancelled {
        border-left: 4px solid #f56565;
    }
    
    .ride-item.pending {
        border-left: 4px solid #ed8936;
    }
    
    .ride-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .ride-date strong {
        display: block;
        font-size: 16px;
    }
    
    .ride-date span {
        color: #666;
        font-size: 14px;
    }
    
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .status-badge.completed {
        background: #c6f6d5;
        color: #22543d;
    }
    
    .status-badge.active {
        background: #bee3f8;
        color: #2a4365;
    }
    
    .status-badge.cancelled {
        background: #fed7d7;
        color: #742a2a;
    }
    
    .ride-locations {
        margin-bottom: 15px;
    }
    
    .location {
        display: flex;
        align-items: flex-start;
        margin-bottom: 10px;
    }
    
    .location i {
        margin-right: 10px;
        margin-top: 5px;
        color: #667eea;
    }
    
    .ride-details {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }
    
    .detail {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #666;
    }
    
    .ride-driver {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        display: flex;
        justify-content: space-between;
    }
    
    .driver-info, .vehicle-info {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }
    
    .empty-state i {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 20px;
    }
    
    .empty-state h4 {
        margin-bottom: 10px;
        color: #2d3748;
    }
    
    .empty-state p {
        color: #718096;
        margin-bottom: 20px;
    }
    
    /* Estilos para viajes disponibles */
    .available-rides-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .available-ride-item {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .ride-passenger {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .passenger-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #667eea;
        overflow: hidden;
    }
    
    .passenger-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .passenger-info h4 {
        margin-bottom: 5px;
    }
    
    .passenger-rating {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #f6ad55;
    }
    
    .ride-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .ride-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .ride-fare h3 {
        color: #48bb78;
        margin: 0;
    }
    
    .btn-accept-ride {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }
    
    /* Estilos para viaje activo */
    .active-ride-container {
        background: white;
        border-radius: 10px;
        padding: 20px;
    }
    
    .passenger-avatar.large {
        width: 80px;
        height: 80px;
        font-size: 32px;
    }
    
    .contact-info {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }
    
    .btn-call {
        background: #48bb78;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .map-placeholder {
        background: #f8f9fa;
        border: 2px dashed #ddd;
        border-radius: 10px;
        height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 20px 0;
    }
    
    .map-placeholder i {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 10px;
    }
    
    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
        position: relative;
    }
    
    .progress-steps:before {
        content: "";
        position: absolute;
        top: 20px;
        left: 10%;
        right: 10%;
        height: 2px;
        background: #e2e8f0;
        z-index: 1;
    }
    
    .step {
        text-align: center;
        z-index: 2;
        flex: 1;
    }
    
    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: #718096;
    }
    
    .step.active .step-icon {
        background: #667eea;
        color: white;
    }
    
    .ride-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }
    
    .stat {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .stat i {
        font-size: 24px;
        color: #667eea;
    }
    
    .ride-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    
    /* Estilos para ganancias */
    .earnings-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }
    
    .earning-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .earning-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }
    
    .earnings-chart {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 30px;
        margin: 20px 0;
        text-align: center;
    }
    
    .chart-placeholder i {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 10px;
    }
    
    .earnings-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .earnings-table th {
        background: #f8f9fa;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #2d3748;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .earnings-table td {
        padding: 12px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    /* Estilos para perfil */
    .profile-form {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #2d3748;
    }
    
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        font-size: 16px;
    }
    
    .empty-state.small {
        padding: 20px;
    }
    
    .empty-state.small i {
        font-size: 32px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .service-types {
            flex-direction: column;
        }
        
        .ride-details {
            grid-template-columns: 1fr;
        }
        
        .ride-stats {
            grid-template-columns: 1fr;
        }
        
        .earnings-summary {
            grid-template-columns: 1fr;
        }
    }
</style>';
?>
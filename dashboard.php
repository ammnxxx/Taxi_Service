<?php
require_once 'config/database.php';
// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$userType = $_SESSION['user_type'] ?? '4';
if (!empty($usuario['foto_url'])) {
    $foto_usuario = htmlspecialchars($usuario['foto_url']);
} else {
    $foto_usuario = 'assets/img/user.svg';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/slimselect.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <!-- Icon font -->
    <link rel="stylesheet" href="assets/webfont/tabler-icons.min.css">
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="assets/icon/favicon-32x32.png" sizes="32x32">
    <link rel="apple-touch-icon" href="assets/icon/favicon-32x32.png">

    <meta name="description" content="Servicio de Taxis y Trasnporte Ejecutivo">
    <meta name="keywords" content="taxi, servicio de taxi, transporte privado, traslados ejecutivos, pedir taxi, taxi 24 horas, transporte seguro, taxi aeropuerto, tarifas de taxi, conductores verificados, movilidad urbana">
    <meta name="author" content="Anderson Marquez">
    <title>TAXI LAS VEGAS</title>
</head>
<div id="global-loader" style="display: none;">
    <div class="loader-content">
        <div class="spinner"></div>
        <span>Cargando...</span>
    </div>
</div>
<body>
    <!-- header -->
    <header class="header">
        <div class="header__content">
            <!-- header logo -->
            <a href="assets/index.html" class="header__logo">
                <img src="assets/img/logo.png" alt="" style="height:60px">
            </a>
            <!-- end header logo -->

            <!-- header menu btn -->
            <button class="header__btn" type="button">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <!-- end header menu btn -->
        </div>
    </header>
    <!-- end header -->

    <?php include("left-sidebar.php");?>
    <!-- main content -->
    <main class="main">
        <div class="container-fluid">
            <div id="contenedor-principal">
                
            </div>
    </main>
    </body>
</html>    
    <!-- end main content -->

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/slimselect.min.js"></script>
    <script src="assets/js/smooth-scrollbar.js"></script>
    <script src="assets/js/admin.js"></script>
    <script>
    // Registros para evitar duplicidad
    const cacheJS = new Set();
    const cacheCSS = new Set();

    function cargarPaginaFullCache(url, op) {
    console.log('Iniciando carga de página:', url, ' con op:', op);
    
    // Preparamos los datos a enviar
    // Si op es undefined (carga inicial), no enviamos nada para limpiar la URL
    var datosEnviar = {};
    if (op !== undefined && op !== null) {
        datosEnviar.op = op;
    }

    $.ajax({
        url: url,
        method: 'POST',
        data:   { 
                    userType: "<?php echo $userType; ?>",
                    op: op // Aprovechas de enviar el parámetro op si lo necesitas
                },
        dataType: 'html',
        success: function(htmlRaw) {
            const $data = $(htmlRaw);

            // 1. Procesar CSS (Mantiene tu lógica de caché)
            $data.filter('link[rel="stylesheet"]').add($data.find('link[rel="stylesheet"]')).each(
                function() {
                    const href = $(this).attr('href');
                    if (href && !cacheCSS.has(href)) {
                        $('head').append(`<link rel="stylesheet" href="${href}" type="text/css" />`);
                        cacheCSS.add(href);
                        console.log('CSS cacheado:', href);
                    }
                }
            );

            // 2. Extraer Scripts y el HTML limpio
            const $scripts = $data.filter('script').add($data.find('script'));
            const $contenido = $data.not('script').not('link[rel="stylesheet"]');

            // 3. Inyectar Contenido
            $('#contenedor-principal').html($contenido);

            // 4. Procesar Scripts (Mantiene tu lógica de caché de JS)
            $scripts.each(function() {
                const src = $(this).attr('src');
                if (src) {
                    if (!cacheJS.has(src)) {
                        $.ajax({
                            url: src,
                            dataType: "script",
                            cache: true, 
                            success: function() {
                                cacheJS.add(src);
                                console.log('JS cacheado:', src);
                            }
                        });
                    }
                } else {
                    $.globalEval(this.text || this.textContent || $(this).html());
                }
            });

            // 5. Reinicializar SlimSelect con Trigger de Eventos
            // Esto es vital para que el .on('change') de jQuery funcione
            $('#contenedor-principal select').each(function() {
                new SlimSelect({
                    select: this,
                    events: {
                        afterChange: () => {
                            // Forzamos al select oculto a disparar el evento change
                            $(this).trigger('change');
                        }
                    }
                });
            });

            // 6. Ejecutar inicialización de otros componentes (como el modal o forms)
            if (typeof inicializar_ajax === "function") {
                inicializar_ajax();
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar la página:", error);
        }
    });
}
    function ver_ubicacion(coordenadas) {
    // Validar que la cadena no esté vacía
    if (!coordenadas || coordenadas.trim() === "") {
        console.error("Coordenadas no válidas");
        return;
    }

    // Limpiar espacios en blanco por si acaso
    const coordsLimpias = coordenadas.trim();

    // Estructura de URL para Google Maps Search con marcador (pin)
    // api=1: Versión de la API
    // query: latitud,longitud donde se colocará el marcador
    const url = `https://www.google.com/maps/search/?api=1&query=${coordsLimpias}`;

    // Abrir en una nueva pestaña
    window.open(url, '_blank');
}
$(document).on({
    ajaxStart: function() { 
        $('#global-loader').fadeIn(200); 
    },
    ajaxStop: function() { 
        $('#global-loader').fadeOut(200); 
    }    
});
$(document).on('change', '#filter__sort_type_user_list', function() {
    var op = $(this).val();
    console.log("Filtro cambiado a:", op);
    
    // Llamamos a tu función pasando la URL y el valor 'op'
    cargarPaginaFullCache('paginas/user-list.php', op);
});
    </script>

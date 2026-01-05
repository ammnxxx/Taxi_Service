<?php 
require_once '../config/database.php'; 
$conn=conectarDB();
if(isset($_POST['op']))
    $op = $_POST['op'];

?>
<div class="col-12">
    <div class="main__title">
        <h2>Nuevo Usuario</h2>

        <!--<span class="main__title-stat">14,452 Total</span>-->

        <div class="main__title-wrap">
            <!-- search -->
            <form action="#" class="main__title-form">
                <input type="text" placeholder="Buscar">
                <button type="button">
                    <i class="ti ti-search"></i>
                </button>
            </form>
            <!-- end search -->
        </div>
    </div>
</div>
<!-- end main title -->
<div class="col-12">
    <form id="formNuevoUsuario" method="POST" class="sign__form sign__form--add" enctype="multipart/form-data"
        autocomplete="off">
        <div class="row">

            <div class="col-12 col-xl-7">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="sign__group">
                            <input type="text" name="u_reg_nombre" id="u_reg_nombre" class="sign__input"
                                placeholder="Nombre *" required autocomplete="off">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="sign__group">
                            <input type="text" name="u_reg_apellido" id="u_reg_apellido" class="sign__input"
                                placeholder="Apellido *" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="sign__group">
                            <input type="text" name="u_reg_nusuario" id="u_reg_nusuario" class="sign__input"
                                placeholder="Nombre de Usuario (Username) *" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="sign__group">
                            <input type="email" name="u_reg_email" id="u_reg_email" class="sign__input"
                                placeholder="Correo Electrónico" autocomplete="off">
                        </div>
                    </div>



                    <div class="col-12">
                        <div class="sign__group">
                            <input type="text" name="u_reg_dir" id="u_reg_dir" class="sign__input"
                                placeholder="Dirección" required autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sign__group">
                            <input type="text" name="u_reg_gps" id="u_reg_gps" class="sign__input"
                                placeholder="Obteniendo ubicación GPS..." readonly required>
                            <small style="color: #29b474; font-size: 11px; margin-left: 20px;">* Coordenadas de tu
                                Ubicacion Actual</small><br>
                            <small style="color: #ff55a5; font-size: 11px; margin-left: 20px;">* Se requiere permiso de
                                ubicación</small>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sign__group">
                            <div class="sign__gallery">
                                <label id="label_foto" for="u_reg_foto">Subir Foto de Perfil</label>
                                <input data-name="#label_foto" id="u_reg_foto" name="u_reg_foto"
                                    class="sign__gallery-upload" type="file" accept=".png, .jpg, .jpeg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="row">
                    <div class="col-12">
                        <div class="sign__group">
                            <input type="tel" name="u_reg_tel" id="u_reg_tel" class="sign__input"
                                placeholder="Teléfono *" required autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sign__group">
                            <select class="sign__selectjs" name="u_reg_tipo" id="u_reg_tipo">
                                    <option value="4" <?php if($op=='4'){echo 'selected';}?>>Pasajero</option>
                                    <option value="3"<?php if($op=='3'){echo 'selected';}?>>Conductor</option>
                                    <option value="2" <?php if($op=='2'){echo 'selected';}?>>Operador</option>
                                    <option value="1" <?php if($op=='1'){echo 'selected';}?>>Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sign__group">
                            <input type="password" name="u_reg_pass" id="u_reg_pass" class="sign__input"
                                placeholder="Contraseña *" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sign__group">
                            <input type="password" name="u_reg_pass2" id="u_reg_pass2" class="sign__input"
                                placeholder="Confirmación Contraseña *" required autocomplete="new-password">
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-12 mt-4">
                <button type="buttom" class="sign__btn sign__btn--small"><span>Registrar Ahora</span></button>
            </div>
        </div>
    </form>
</div>
<!-- Agrega este div para mostrar mensajes -->
<div id="mensajeResultado" style="display: none;" class="col-12 mt-3"></div>

<script>

$(document).ready(function() {
    
    $(document).off('click', '.sign__btn').on('click', '.sign__btn', function(e) {
    e.preventDefault();

    // 1. VALIDACIÓN PRIMERO (Antes de cambiar cualquier estado visual)
    if (typeof validarFormulario === "function") {
        if (!validarFormulario()) {
            // Si falla, el código se detiene aquí. 
            // El botón sigue habilitado y el usuario puede corregir.
            return; 
        }
    }

    // 2. BUSCAR ELEMENTOS
    var formulario = document.getElementById('formNuevoUsuario');
    var $btn = $(this);

    // 3. CAMBIAR ESTADO VISUAL (Solo llegamos aquí si la validación pasó)
    $btn.prop('disabled', true).html('<span>Procesando...</span>');
    $('#mensajeResultado').hide().empty();

    // 4. PREPARAR DATOS Y ENVIAR
    var formData = new FormData(formulario);

    $.ajax({
        url: 'paginas/ajax_procesar_usuario.php', 
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                mostrarMensaje('success', response.message);
            } else {
                mostrarMensaje('error', response.message);
                // Si hubo error en el servidor, rehabilitamos el botón
                $btn.prop('disabled', false).html('<span>Registrar Ahora</span>');
            }
        },
        error: function(xhr, status, error) {
            mostrarMensaje('error', 'Error en la conexión: ' + error);
            // Si hubo error de red, rehabilitamos el botón
            $btn.prop('disabled', false).html('<span>Registrar Ahora</span>');
        }
    });
});

    // --- Validaciones en tiempo real (Delegadas) ---

    $(document).off('keyup', '#u_reg_pass2').on('keyup', '#u_reg_pass2', function() {
        if (typeof validarConfirmacionPassword === "function") {
            validarConfirmacionPassword();
        }
    });

    $(document).off('blur', '#u_reg_nusuario').on('blur', '#u_reg_nusuario', function() {
        var valor = $(this).val().trim();
        if (valor !== '' && typeof verificarUsuarioExistente === "function") {
            verificarUsuarioExistente(valor);
        }
    });

    $(document).off('blur', '#u_reg_tel').on('blur', '#u_reg_tel', function() {
        var valor = $(this).val().trim();
        if (valor !== '' && typeof verificarTelefonoExistente === "function") {
            verificarTelefonoExistente(valor);
        }
    });

});
function inicializar_ajax() {
    const gpsInput = document.getElementById('u_reg_gps');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                gpsInput.value = `${lat}, ${lon}`;
            },
            (error) => {
                gpsInput.placeholder = "Ubicación no disponible (activa el GPS)";
                console.error("Error obteniendo ubicación:", error);
            }
        );
    } else {
        gpsInput.placeholder = "Tu navegador no soporta geolocalización";
    }
}
function validarFormulario() {
    // Validar campos requeridos
    var camposRequeridos = ['u_reg_nombre', 'u_reg_apellido', 'u_reg_nusuario', 'u_reg_tel', 'u_reg_pass',
        'u_reg_pass2', 'u_reg_dir', 'u_reg_gps'
    ];
    var valido = true;

    camposRequeridos.forEach(function(campo) {
        var input = $('#' + campo);
        if (input.val().trim() === '') {
            input.addClass('error');
            valido = false;
        } else {
            input.removeClass('error');
        }
    });

    // Validar email si está presente
    var email = $('#u_reg_email').val();
    if (email.trim() !== '' && !validarEmail(email)) {
        mostrarMensaje('error', 'El formato del email no es válido');
        $('#u_reg_email').addClass('error');
        return false;
    } else {
        $('#u_reg_email').removeClass('error');
    }

    // Validar contraseñas
    if (!validarConfirmacionPassword()) {
        return false;
    }

    // Validar ubicación GPS
    if ($('#u_reg_gps').val().trim() === '' || $('#u_reg_gps').val() === 'Obteniendo ubicación GPS...') {
        mostrarMensaje('error', 'Es necesario obtener la ubicación GPS');
        return false;
    }

    return valido;
}

function validarEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validarConfirmacionPassword() {
    var pass1 = $('#u_reg_pass').val();
    var pass2 = $('#u_reg_pass2').val();

    if (pass1 !== pass2) {
        $('#u_reg_pass, #u_reg_pass2').addClass('error');
        mostrarMensaje('error', 'Las contraseñas no coinciden');
        return false;
    } else {
        $('#u_reg_pass, #u_reg_pass2').removeClass('error');
        return true;
    }
}

function verificarUsuarioExistente(usuario) {
    $.ajax({
        url: 'paginas/verificar_usuario.php', // Necesitarás crear este archivo
        type: 'POST',
        data: {
            campo: 'nusuario',
            valor: usuario
        },
        global: false,
        dataType: 'json',
        success: function(response) {
            if (response.existe) {
                $('#u_reg_nusuario').addClass('error');
                mostrarMensaje('error', 'El nombre de usuario ya está registrado');
            } else {
                $('#u_reg_nusuario').removeClass('error');
            }
        }
    });
}

function verificarTelefonoExistente(telefono) {
    $.ajax({
        url: 'paginas/verificar_usuario.php', // Necesitarás crear este archivo
        type: 'POST',
        data: {
            campo: 'telefono',
            valor: telefono
        },
        global: false,
        dataType: 'json',
        success: function(response) {
            if (response.existe) {
                $('#u_reg_tel').addClass('error');
                mostrarMensaje('error', 'El número de teléfono ya está registrado');
            } else {
                $('#u_reg_tel').removeClass('error');
            }
        }
    });
}

function mostrarMensaje(tipo, mensaje) {
    var div = $('#mensajeResultado');
    div.removeClass('alert-success alert-danger');

    if (tipo === 'success') {
        div.addClass('alert-success').html('<div class="alert alert-success">' + mensaje + '</div>');
    } else {
        div.addClass('alert-danger').html('<div class="alert alert-danger">' + mensaje + '</div>');
    }

    div.show();

    // Ocultar mensaje después de 5 segundos
    setTimeout(function() {
        div.fadeOut();
    }, 5000);
}
</script>

<style>
.error {
    border-color: #ff3860 !important;
    box-shadow: 0 0 0 0.125em rgba(255, 56, 96, 0.25) !important;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

.alert-danger {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
}
</style>
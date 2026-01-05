<?php 
require_once '../config/database.php'; 
$conn = conectarDB();

// Verificamos ID
if (!isset($_POST['op']))  {
    echo "<div class='alert alert-danger'>ID de usuario no válido.</div>";
    exit;
}
$op=explode(',',$_POST['op']);

$id_usuario = mysqli_real_escape_string($conn, $_POST['op'][0]);

// Consulta MySQLi
$query = "SELECT * FROM usuarios WHERE id = '$id_usuario'";
$resultado = mysqli_query($conn, $query);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $u = mysqli_fetch_assoc($resultado);
} else {
    echo "<div class='alert alert-danger'>Usuario no encontrado.</div>";
    exit;
}
?>

<div class="col-12">
    <div class="main__title">
        <h2>Modificar Usuario: <span style="color: #ff55a5;"><?php echo $u['nusuario']; ?></span></h2>
        <div class="main__title-wrap">
            <form action="#" class="main__title-form">
                <input type="text" placeholder="Buscar">
                <button type="button"><i class="ti ti-search"></i></button>
            </form>
        </div>
    </div>
</div>

<div class="col-12">
    <form id="formEditarUsuario" method="POST" class="sign__form sign__form--add" enctype="multipart/form-data" autocomplete="off">
        
        <input type="hidden" name="user_id" id="user_id" value="<?php echo $u['id']; ?>">
        <input type="hidden" name="accion" value="editar">

        <div class="row">
            <div class="col-12 col-xl-7">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="sign__group">
                            <input type="text" name="u_reg_nombre" id="u_reg_nombre" class="sign__input" placeholder="Nombre *" required value="<?php echo $u['nombre']; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="sign__group">
                            <input type="text" name="u_reg_apellido" id="u_reg_apellido" class="sign__input" placeholder="Apellido *" required value="<?php echo $u['apellido']; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="sign__group">
                            <input type="text" name="u_reg_nusuario" id="u_reg_nusuario" class="sign__input" placeholder="Username *" required value="<?php echo $u['nusuario']; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="sign__group">
                            <input type="email" name="u_reg_email" id="u_reg_email" class="sign__input" placeholder="Email" value="<?php echo $u['email']; ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sign__group">
                            <input type="text" name="u_reg_dir" id="u_reg_dir" class="sign__input" placeholder="Dirección" required value="<?php echo $u['direccion']; ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sign__group">
                            <input type="text" name="u_reg_gps" id="u_reg_gps" class="sign__input" placeholder="GPS" readonly value="<?php echo $u['ubicacion']; ?>">
                            <small style="color: #29b474; font-size: 11px; margin-left: 20px;">* Ubicación guardada</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sign__group">
                            <div class="sign__gallery">
                                <label id="label_foto" for="u_reg_foto">Cambiar Foto de Perfil</label>
                                <input data-name="#label_foto" id="u_reg_foto" name="u_reg_foto" class="sign__gallery-upload" type="file" accept="image/*">
                            </div>
                            <div class="sidebar__user-img" style="margin-top:15px;width:120px;height:120px">
                                <a href="#" tittle="Mi Perfil"><img src="<?php echo substr($u['foto_url'],3)?>" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="row">
                    <div class="col-12">
                        <div class="sign__group">
                            <input type="tel" name="u_reg_tel" id="u_reg_tel" class="sign__input" placeholder="Teléfono *" required value="<?php echo $u['telefono']; ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sign__group">
                            <select class="sign__selectjs" name="u_reg_tipo" id="u_reg_tipo">
                                <option value="pasajero" <?php if($u['tipo']=='pasajero'){echo 'selected';}?>>Pasajero</option>
                                <option value="conductor" <?php if($u['tipo']=='conductor'){echo 'selected';}?>>Conductor</option>
                                <option value="operador" <?php if($u['tipo']=='operador'){echo 'selected';}?>>Operador</option>
                                <option value="admin" <?php if($u['tipo']=='admin'){echo 'selected';}?>>Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sign__group">
                            <input type="password" name="u_reg_pass" id="u_reg_pass" class="sign__input" placeholder="Nueva Clave (opcional)">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sign__group">
                            <input type="password" name="u_reg_pass2" id="u_reg_pass2" class="sign__input" placeholder="Confirmar Clave">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <button type="button" class="sign__btn sign__btn--small"><span>Guardar Cambios</span></button>
            </div>
        </div>
    </form>
</div>

<div id="mensajeResultado" style="display: none;" class="col-12 mt-3"></div>

<style>
    .error { border-color: #ff3860 !important; box-shadow: 0 0 0 0.125em rgba(255, 56, 96, 0.25) !important; }
    .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
    .alert-success { color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; }
    .alert-danger { color: #a94442; background-color: #f2dede; border-color: #ebccd1; }
</style>

<script>
$(document).ready(function() {
    
    // 1. Evento Principal Guardar
    $(document).off('click', '.sign__btn').on('click', '.sign__btn', function(e) {
        e.preventDefault();
        if (!validarFormulario()) return;

        var $btn = $(this);
        var formData = new FormData(document.getElementById('formEditarUsuario'));

        $btn.prop('disabled', true).html('<span>Actualizando...</span>');

        $.ajax({
            url: 'paginas/ajax_procesar_usuario.php', 
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res.success) mostrarMensaje('success', res.message);
                else mostrarMensaje('error', res.message);
            },
            error: function() { mostrarMensaje('error', 'Error en el servidor.'); },
            complete: function() { 
                $btn.prop('disabled', false).html('<span>Guardar Cambios</span>'); 
            }
        });
    });

    // 2. Validaciones en tiempo real (Delegadas)
    $(document).off('keyup', '#u_reg_pass2').on('keyup', '#u_reg_pass2', function() {
        validarConfirmacionPassword();
    });

    $(document).off('blur', '#u_reg_nusuario').on('blur', '#u_reg_nusuario', function() {
        verificarExistencia('nusuario', $(this).val().trim(), '#u_reg_nusuario');
    });

    $(document).off('blur', '#u_reg_tel').on('blur', '#u_reg_tel', function() {
        verificarExistencia('telefono', $(this).val().trim(), '#u_reg_tel');
    });
});

// --- FUNCIONES ---

function inicializar_ajax() {
    // Re-inicializamos SlimSelect en el contenedor
   /* $('#formEditarUsuario select').each(function() { 
        new SlimSelect({ select: this }); 
    });*/
}

function validarFormulario() {
    var valido = true;
    var campos = ['u_reg_nombre', 'u_reg_apellido', 'u_reg_nusuario', 'u_reg_tel', 'u_reg_dir'];

    campos.forEach(function(id) {
        var input = $('#' + id);
        if (input.val().trim() === '') {
            input.addClass('error');
            valido = false;
        } else {
            input.removeClass('error');
        }
    });

    // Validar pass solo si escribió algo
    if ($('#u_reg_pass').val() !== '') {
        if (!validarConfirmacionPassword()) valido = false;
    }

    return valido;
}

function validarConfirmacionPassword() {
    var p1 = $('#u_reg_pass').val();
    var p2 = $('#u_reg_pass2').val();
    if (p1 !== p2) {
        $('#u_reg_pass, #u_reg_pass2').addClass('error');
        return false;
    } else {
        $('#u_reg_pass, #u_reg_pass2').removeClass('error');
        return true;
    }
}

function verificarExistencia(campo, valor, selector) {
    if (valor === '') return;
    $.ajax({
        url: 'paginas/verificar_usuario.php',
        type: 'POST',
        data: { 
            campo: campo, 
            valor: valor, 
            ignore_id: $('#user_id').val() // Importante para no validarse a sí mismo
        },
        dataType: 'json',
        success: function(res) {
            if (res.existe) {
                $(selector).addClass('error');
                mostrarMensaje('error', 'Ese ' + campo + ' ya está registrado.');
            } else {
                $(selector).removeClass('error');
            }
        }
    });
}

function mostrarMensaje(tipo, mensaje) {
    var div = $('#mensajeResultado');
    var clase = tipo === 'success' ? 'alert-success' : 'alert-danger';
    div.html('<div class="alert ' + clase + '">' + mensaje + '</div>').fadeIn();
    setTimeout(function() { div.fadeOut(); }, 5000);
}
</script>
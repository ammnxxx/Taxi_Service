<?php
session_start();
if(isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Taxi Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .recovery-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            color: #667eea;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .back-link i {
            margin-right: 8px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo i {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .logo h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .logo p {
            color: #666;
            font-size: 14px;
        }
        
        .steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        
        .steps:before {
            content: '';
            position: absolute;
            top: 15px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: #e1e1e1;
            z-index: 1;
        }
        
        .step {
            text-align: center;
            z-index: 2;
            position: relative;
            flex: 1;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            background: #e1e1e1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: white;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .step.active .step-number {
            background: #667eea;
        }
        
        .step-label {
            font-size: 12px;
            color: #777;
        }
        
        .step.active .step-label {
            color: #667eea;
            font-weight: 600;
        }
        
        .step-form {
            display: none;
        }
        
        .step-form.active {
            display: block;
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .input-group input:focus {
            border-color: #667eea;
            outline: none;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
            font-weight: 500;
            display: none;
        }
        
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }
        
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
        
        .btn-secondary {
            background: #6c757d;
            margin-top: 10px;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }
        
        @media (max-width: 480px) {
            .recovery-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="recovery-container">
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Volver al login
        </a>
        
        <div class="logo">
            <i class="fas fa-key"></i>
            <h1>Recuperar Contraseña</h1>
            <p>Ingresa tu email para restablecer tu contraseña</p>
        </div>
        
        <div class="steps">
            <div class="step active" id="step1">
                <div class="step-number">1</div>
                <div class="step-label">Verificar Email</div>
            </div>
            <div class="step" id="step2">
                <div class="step-number">2</div>
                <div class="step-label">Código de Verificación</div>
            </div>
            <div class="step" id="step3">
                <div class="step-number">3</div>
                <div class="step-label">Nueva Contraseña</div>
            </div>
        </div>
        
        <!-- Paso 1: Ingresar email -->
        <div class="step-form active" id="formStep1">
            <div class="input-group">
                <label for="email"><i class="fas fa-envelope"></i> Email registrado</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <button class="btn" id="btnSendCode">
                <span id="btnText1">Enviar Código</span>
                <div id="btnLoading1" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Enviando...
                </div>
            </button>
        </div>
        
        <!-- Paso 2: Verificar código -->
        <div class="step-form" id="formStep2">
            <div class="input-group">
                <label for="code"><i class="fas fa-shield-alt"></i> Código de verificación</label>
                <input type="text" id="code" name="code" maxlength="6" required>
                <small style="display: block; margin-top: 5px; color: #666;">
                    Revisa tu correo electrónico. El código expira en 15 minutos.
                </small>
            </div>
            
            <button class="btn" id="btnVerifyCode">
                <span id="btnText2">Verificar Código</span>
                <div id="btnLoading2" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Verificando...
                </div>
            </button>
            
            <button class="btn btn-secondary" id="btnResendCode">
                <span id="btnText3">Reenviar Código</span>
                <div id="btnLoading3" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Enviando...
                </div>
            </button>
        </div>
        
        <!-- Paso 3: Nueva contraseña -->
        <div class="step-form" id="formStep3">
            <div class="input-group">
                <label for="newPassword"><i class="fas fa-lock"></i> Nueva Contraseña</label>
                <input type="password" id="newPassword" name="newPassword" required>
            </div>
            
            <div class="input-group">
                <label for="confirmPassword"><i class="fas fa-lock"></i> Confirmar Contraseña</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>
            
            <button class="btn" id="btnResetPassword">
                <span id="btnText4">Restablecer Contraseña</span>
                <div id="btnLoading4" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Procesando...
                </div>
            </button>
        </div>
        
        <div id="message" class="message"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    $(document).ready(function() {
        let userEmail = '';
        let verificationToken = '';
        
        // Mostrar/ocultar pasos
        function showStep(stepNumber) {
            $('.step').removeClass('active');
            $('.step-form').removeClass('active');
            
            $('#step' + stepNumber).addClass('active');
            $('#formStep' + stepNumber).addClass('active');
        }
        
        // Paso 1: Enviar código
        $('#btnSendCode').click(function() {
            const email = $('#email').val().trim();
            
            if (!email) {
                showMessage('Por favor ingresa tu email', 'error');
                return;
            }
            
            if (!validateEmail(email)) {
                showMessage('Por favor ingresa un email válido', 'error');
                return;
            }
            
            // Mostrar loading
            $('#btnText1').hide();
            $('#btnLoading1').show();
            $('#btnSendCode').prop('disabled', true);
            
            $.ajax({
                url: 'process_recovery.php',
                type: 'POST',
                data: { action: 'send_code', email: email },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        userEmail = email;
                        showMessage(response.message, 'success');
                        setTimeout(() => {
                            showStep(2);
                        }, 1500);
                    } else {
                        showMessage(response.message, 'error');
                    }
                    $('#btnText1').show();
                    $('#btnLoading1').hide();
                    $('#btnSendCode').prop('disabled', false);
                },
                error: function() {
                    showMessage('Error de conexión', 'error');
                    $('#btnText1').show();
                    $('#btnLoading1').hide();
                    $('#btnSendCode').prop('disabled', false);
                }
            });
        });
        
        // Paso 2: Verificar código
        $('#btnVerifyCode').click(function() {
            const code = $('#code').val().trim();
            
            if (!code || code.length !== 6) {
                showMessage('Por favor ingresa el código de 6 dígitos', 'error');
                return;
            }
            
            // Mostrar loading
            $('#btnText2').hide();
            $('#btnLoading2').show();
            $('#btnVerifyCode').prop('disabled', true);
            
            $.ajax({
                url: 'process_recovery.php',
                type: 'POST',
                data: { 
                    action: 'verify_code', 
                    email: userEmail, 
                    code: code 
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        verificationToken = response.token;
                        showMessage(response.message, 'success');
                        setTimeout(() => {
                            showStep(3);
                        }, 1500);
                    } else {
                        showMessage(response.message, 'error');
                    }
                    $('#btnText2').show();
                    $('#btnLoading2').hide();
                    $('#btnVerifyCode').prop('disabled', false);
                },
                error: function() {
                    showMessage('Error de conexión', 'error');
                    $('#btnText2').show();
                    $('#btnLoading2').hide();
                    $('#btnVerifyCode').prop('disabled', false);
                }
            });
        });
        
        // Reenviar código
        $('#btnResendCode').click(function() {
            if (!userEmail) {
                showMessage('Por favor completa el paso 1 primero', 'error');
                return;
            }
            
            // Mostrar loading
            $('#btnText3').hide();
            $('#btnLoading3').show();
            $('#btnResendCode').prop('disabled', true);
            
            $.ajax({
                url: 'process_recovery.php',
                type: 'POST',
                data: { action: 'resend_code', email: userEmail },
                dataType: 'json',
                success: function(response) {
                    showMessage(response.message, response.success ? 'success' : 'error');
                    $('#btnText3').show();
                    $('#btnLoading3').hide();
                    $('#btnResendCode').prop('disabled', false);
                },
                error: function() {
                    showMessage('Error de conexión', 'error');
                    $('#btnText3').show();
                    $('#btnLoading3').hide();
                    $('#btnResendCode').prop('disabled', false);
                }
            });
        });
        
        // Paso 3: Restablecer contraseña
        $('#btnResetPassword').click(function() {
            const newPassword = $('#newPassword').val();
            const confirmPassword = $('#confirmPassword').val();
            
            if (!newPassword || !confirmPassword) {
                showMessage('Por favor completa ambos campos', 'error');
                return;
            }
            
            if (newPassword.length < 6) {
                showMessage('La contraseña debe tener al menos 6 caracteres', 'error');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                showMessage('Las contraseñas no coinciden', 'error');
                return;
            }
            
            if (!verificationToken) {
                showMessage('Error de verificación. Por favor inicia el proceso nuevamente.', 'error');
                return;
            }
            
            // Mostrar loading
            $('#btnText4').hide();
            $('#btnLoading4').show();
            $('#btnResetPassword').prop('disabled', true);
            
            $.ajax({
                url: 'process_recovery.php',
                type: 'POST',
                data: { 
                    action: 'reset_password', 
                    email: userEmail,
                    token: verificationToken,
                    new_password: newPassword
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showMessage(response.message, 'success');
                        setTimeout(() => {
                            window.location.href = 'index.php';
                        }, 2000);
                    } else {
                        showMessage(response.message, 'error');
                        $('#btnText4').show();
                        $('#btnLoading4').hide();
                        $('#btnResetPassword').prop('disabled', false);
                    }
                },
                error: function() {
                    showMessage('Error de conexión', 'error');
                    $('#btnText4').show();
                    $('#btnLoading4').hide();
                    $('#btnResetPassword').prop('disabled', false);
                }
            });
        });
        
        // Funciones auxiliares
        function showMessage(text, type) {
            const messageDiv = $('#message');
            messageDiv.removeClass('success error').addClass(type).text(text).show();
            setTimeout(() => messageDiv.fadeOut(), 5000);
        }
        
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        // Permitir submit con Enter
        $('input').keypress(function(e) {
            if (e.which === 13) {
                e.preventDefault();
                if ($('#formStep1').hasClass('active')) {
                    $('#btnSendCode').click();
                } else if ($('#formStep2').hasClass('active')) {
                    $('#btnVerifyCode').click();
                } else if ($('#formStep3').hasClass('active')) {
                    $('#btnResetPassword').click();
                }
            }
        });
    });
    </script>
</body>
</html>
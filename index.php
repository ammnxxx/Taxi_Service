<?php
session_start();
if(isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
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
	<style>
	.message {
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
            font-weight: 500;
            display: none;
			font-size: 12px;
			color: #fff;
			text-transform: uppercase;
        }

		.message.success{background-color:#00ff3a14;color:#FFF;border:2px solid #c3e6cb;display:block}
		.message.error{background-color:#dc35451f;color:#FFF;border:2px solid #db0c11;display:block}
	</style>

	<title>TAXI LAS VEGAS</title>
</head>

<body>
	<div class="sign section--bg" data-bg="assets/img/section/section.jpg">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="sign__content">
						<!-- authorization form -->
						<form id="loginForm" class="sign__form">
							<a href="index.php" class="sign__logo">
								<img src="assets/img/logo.png" alt="" style="height:100px">
							</a>

							<div class="sign__group">
								<input type="text" class="sign__input" placeholder="Telefono o Nombre de Usuario"  id="username" name="username" required  autocomplete="off">
							</div>

							<div class="sign__group">
								<input type="password" class="sign__input" placeholder="Contraseña" id="password" name="password" required>
								<i class="fas fa-eye" id="togglePassword"></i>
							</div>

							<button class="sign__btn" type="submit" id="btnLogin">
								<span id="btnText">Iniciar Sesión</span>
								<div id="btnLoading" style="display: none;">
									<i class="fas fa-spinner fa-spin"></i> Verificando...
								</div>
							</button>
							<div id="message" class="message">xxxxx</div>
							<span class="sign__text">¿No tienes una cuenta? <a href="signup.html">¡Regístrate!</a></span>

							<span class="sign__text"><a id="forgotPasswordLink">¿Olvidaste tu contraseña?</a></span>
						</form>
						<!-- end authorization form -->
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/slimselect.min.js"></script>
	<script src="assets/js/smooth-scrollbar.js"></script>
	<script src="assets/js/admin.js"></script>
	
</body>
</html>
<script>
    $(document).ready(function() {
        // Mostrar/ocultar contraseña
        $('#togglePassword').click(function() {
            const passwordInput = $('#password');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            $(this).toggleClass('fa-eye fa-eye-slash');
        });
        
        // Envío del formulario de login
        $('#loginForm').submit(function(e) {
            e.preventDefault();
            
            const btnLogin = $('#btnLogin');
            const btnText = $('#btnText');
            const btnLoading = $('#btnLoading');
            
            // Mostrar loading
            btnText.hide();
            btnLoading.show();
            btnLogin.prop('disabled', true);
            
            const formData = $(this).serialize();
            
            $.ajax({
                url: 'process_login.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#message').removeClass('error').addClass('success').text(response.message).show();
                        setTimeout(function() {
                            window.location.href = 'dashboard.php';
                        }, 11500);
                    } else {
                        $('#message').removeClass('success').addClass('error').text(response.message).show();
                        btnText.show();
                        btnLoading.hide();
                        btnLogin.prop('disabled', false);
                    }
                },
                error: function() {
                    $('#message').removeClass('success').addClass('error').text('Error de conexión').show();
                    btnText.show();
                    btnLoading.hide();
                    btnLogin.prop('disabled', false);
                }
            });
        });
        
        // Enlace para recuperar contraseña
        $('#forgotPasswordLink').click(function(e) {
            e.preventDefault();
            window.location.href = 'recuperar_password.php';
        });
    });
    </script>

<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Вход | Turan</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link href="<?php echo auto_version('/css/login.min.css'); ?>" rel="stylesheet">
</head>
<body class="text-center">
	<form class="form-signin card p-4 shadow-lg" name="loginForm" id="loginForm" method="POST" action="">
		<h1 class="mb-3 lead">Вход в личный кабинет</h1>
		<div class="form-group">
      <input type="login" class="form-control" id="loginInput" placeholder="Логин">
      <div id="validLogin" class="valid-feedback">
        Выглядит хорошо!
      </div>
      <div id="invalidLogin" class="invalid-feedback">
        Кажется что-то не так!
      </div>
    </div>
		<div class="form-group">
      <input type="password" class="form-control" id="passwordInput" placeholder="Пароль">
      <div id="validPassword" class="valid-feedback">
        Ну, вроде с паролем всё верно.
      </div>
      <div id="invalidPassword" class="invalid-feedback">
        Кажется что-то не так!
      </div>
    </div>
		<button id="btnAuth" class="btn btn-lg btn-info btn-block" type="submit">Вход</button>
		<small class="mt-4 mb-3 text-muted">&copy; <?=date("Y")?> <a href="https://evseenko.kz/" class="text-muted text-decoration-none" target="_blank">Eugene Evseenko</a></small>
	</form>
	<script crossorigin="anonymous" src="<?php echo auto_version('/js/login.min.js'); ?>"></script>
</body>
</html>

<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?=$settings['stopped-title']?></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<link href="/css/login.min.css" rel="stylesheet">
</head>

<body class="text-center">
	<div class="form-signin">
    <div class="text-center mb-4">
      <img class="mb-4" src="/images/logo.png" alt="" height="60" alt="bootstrap">
      <h1 class="h3 mb-3 font-weight-normal"><?=$settings['stopped-title']?></h1>
      <p class="lead"><?=$settings['stopped-text']?></p>
    </div>

		<h1 class="h3 mb-3 font-weight-normal sr-only">Turan</h1>
    <div class="btn-group" role="group" aria-label="Basic example">
  		<a href="/" class="btn btn-lg btn-primary">Главная</a>
  		<button onclick="javascript:history.back();" class="btn btn-lg btn-warning">Назад</button>
    </div>
		<p class="mt-5 mb-3 text-muted">&copy; 2021 <a href="https://evseenko.kz/" class="text-muted text-decoration-none" target="_blank">Eugene Evseenko</a>
		</p>
	</div>
</body>
</html>

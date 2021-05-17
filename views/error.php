<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Ошибочка</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<link href="/css/login.min.css" rel="stylesheet">
</head>
<body class="text-center">
	<div class="card shadow-lg form-signin">
    <div class="container text-center" id="main-container">
      <h1 class="text-danger">Ошибочка</h1>
      <p class="lead mt-3"><?=$errors[$error_text]?></p>
      <p class="mt-3">Если вы считаете что этой ошибки быть не должно, то напишите разработчику.</p>
      <?if(!empty($_SESSION['login']) && !empty($_SESSION['id']) && !empty($_SESSION['gid'])):?>
      <a href="/profile?go=sign-out" class="btn btn-danger">Выйти</a>
      <?endif?>
      <button onclick="javascript:history.back();" class="btn btn-primary">Назад</button>
      <a href="mailto:jonikevseenko@gmail.com" class="btn btn-success">Написать</a>
    </div>
</body>
</html>

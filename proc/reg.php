<?php
  session_start();
  if($_POST) {
    require_once("../functions.php");
    $link = db_connect();
    $errors = array();
    if(isset($_POST['name'])){
      if (mb_strlen($_POST['name']) == 0) {
        $errors[] = put_error(1, "Без имени и фамилии никак...");
      }else if (mb_strlen($_POST['name']) > 100) {
        $errors[] = put_error(1, "Имя и фамилия должны быть не более 100 символов");
      }
    }else {
      $errors[] = put_error(1, 'Неправильное обращение');
    }
    if(isset($_POST['email'])){
      if (mb_strlen($_POST['email']) == 0) {
        $errors[] = put_error(2, "Заполните почтовый адрес");
      }else if (mb_strlen($_POST['email']) > 50) {
        $errors[] = put_error(2, "Длина почтового адреса должна быть не более 50 символов");
      }else if (mb_strlen($_POST['email']) != mb_strlen(str_replace(" ", "", $_POST['email']))) {
        $errors[] = put_error(3, "Тебе не кажется что пробелы тут лишние? Я их уберу сам");
      }else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = put_error(2, "Некорректный почтовый адрес 🤷‍♂️");
      }else if(get_count($link, "Students", "email", htmlspecialchars($_POST['email'])) > 1){
        $errors[] = put_error(2,"Этот почтовый адрес уже закреплен другим студентом");
      }
    }else {
      $errors[] = put_error(2, 'Неправильное обращение');
    }
    if (isset($_POST['login'])) {
			if (mb_strlen(trim($_POST['login'])) == 0) {
        $errors[] = put_error(4, "Логин не может быть пустым");
			}else if(mb_strlen($_POST['login']) < 6 || mb_strlen($_POST['login']) > 25){
        $errors[] = put_error(4, "Пароль должен содержать не менее 6 символов и не более 25");
			}else if(!preg_match ("#^[aA-zZ0-9\-_]+$#",$_POST['login'])){
        $errors[] = put_error(4, "В логине присутствуют запрещенные символы");
			}else if(get_count($link, "Students", "login", htmlspecialchars($_POST['login'])) > 0){
        $errors[] = put_error(4, "Этот логин уже закреплен другим студентом");
      }
		}else {
      $errors[] = put_error(4, 'Неправильное обращение');
    }
    if(isset($_POST['phone'])){
      if (mb_strlen(trim($_POST['phone'])) == 0) {
        $errors[] = put_error(5, "Заполните номер телефона");
      }else if (mb_strlen($_POST['phone']) != 16) {
        $errors[] = put_error(5, "Неверный формат номера телефона 🤷‍♂️");
      }
    } else {
      $errors[] = put_error(5, 'Неправильное обращение');
    }
    if (isset($_POST['fpass'])){
      if (mb_strlen(trim($_POST['fpass'])) == 0){
  			$errors[] = put_error(6, 'Нельзя забывать про пароль');
  		}elseif(mb_strlen($_POST['fpass']) != mb_strlen(str_replace(" ", "", $_POST['fpass']))){
        $errors[] = put_error(7, 'Серьёзно? Пробелы в пароле? Ай-яй! 😁');
      }elseif (strpos($_POST['fpass']," ") !== false) {
        $errors[] = put_error(7, 'Думаешь сможешь меня обмануть поставив пробелы между букв?) 😂');
      }elseif (mb_strlen($_POST['fpass']) < 6 || mb_strlen($_POST['fpass']) > 20) {
        $errors[] = put_error(6, 'Пароль должен содержать не менее 6 символов и не более 20');
      }
    } else {
      $errors[] = put_error(6, 'Неправильное обращение');
    }
    if (isset($_POST['spass'])){
      if (mb_strlen(trim($_POST['spass'])) == 0){
  			$errors[] = put_error(8, 'Нельзя забывать про пароль');
  		}elseif(trim($_POST['fpass']) != trim($_POST['spass'])){
        $errors[] = put_error(8, 'Пароли не совпадают');
      }
    } else {
      $errors[] = put_error(8, 'Неправильное обращение');
    }
    if (isset($_POST['gender'])){
      if ($_POST['gender'] < -1 || $_POST['gender'] > 1) {
        $errors[] = put_error(9, 'Давай-ка не будешь баловаться с этим?');
      }
    } else {
      $errors[] = put_error(9, 'Неправильное обращение');
    }
    if (isset($_POST['birth'])) {
      if (strtotime($_POST['birth']) > strtotime("now") || strlen(substr($_POST['birth'], 0, strpos($_POST['birth'], '-'))) > 4) {
        $errors[] = put_error(10, 'Ты что из будущего?');
      }elseif (strtotime($_POST['birth']) < strtotime("1970-01-01")) {
        $errors[] = $errors[] = put_error(10, 'Год должен быть больше 1970');
      }elseif (empty($_POST['birth'])) {
        $errors[] = $errors[] = put_error(10, 'Что-то не так с датой');
      }
    }
    if (isset($_POST['check'])){
      if ($_POST['check'] != 'true') {
        $errors[] = put_error(11, 'Нужно прочитать и принять условия');
      }
    } else {
      $errors[] = put_error(11, 'Неправильное обращение');
    }
    if (isset($_POST['recaptcha'])){
			$captcha_response = $_POST['recaptcha'];
			$url = 'https://www.google.com/recaptcha/api/siteverify';
			$params = [
				'secret' => '6LdH-ncUAAAAAGp4kKWaG1wsS3igcYU1KZeVz4C4',
				'response' => $captcha_response,
				'remoteip' => $_SERVER['REMOTE_ADDR']
			];
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			if(!empty($response)) $decoded_response = json_decode($response);
			$success = false;
			if ($decoded_response && $decoded_response->success)
			{
				$success = $decoded_response->success;
			}
			if(!$success){
				$errors[] = put_error(12, 'Неверная каптча');
			}
		}else {
			$errors[] = put_error(12, 'Каптча не найдена');
		}
    if (isset($_POST['token'])){
      if (get_count($link, "Students", "token", htmlspecialchars($_POST['token'])) == 0) {
        $errors[] = put_error(13, 'Недействительный токен');
      }
    } else {
      $errors[] = put_error(13, 'Неправильное обращение');
    }
    if(count($errors) > 0){
      header("HTTP/1.0 400 Bad Request",false,400);
      die(json_encode(array(
        'errors'=> $errors
      )));
    }
    if(!send_in_base($link,"UPDATE `Students` SET
      `name`='".htmlspecialchars($_POST['name'])."', `email`='".htmlspecialchars($_POST['email'])."', `login`='".htmlspecialchars($_POST['login'])."'
      , `phone`='".htmlspecialchars($_POST['phone'])."', `password`='".htmlspecialchars($_POST['fpass'])."', `gender`=".htmlspecialchars(($_POST['gender'] == -1)?'NULL':$_POST['gender'])."
      , `token`=NULL, `date-of-birth`=".((isset($_POST['birth']))?"'".htmlspecialchars($_POST['birth'])."'":'NULL').", `state`='active', `last-session`=CURRENT_TIMESTAMP
        WHERE `token`='".htmlspecialchars($_POST['token'])."'")){
      header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
    }else {
      exit(json_encode(array('response'=>'ok')));
    }
  }else {
    header("HTTP/1.0 400 Bad Request",false,400);die();
  }
?>

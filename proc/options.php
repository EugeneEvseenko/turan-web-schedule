<?php
  session_start();
	if($_POST) {
    if(!empty($_SESSION['login']) && !empty($_SESSION['id']) && !empty($_SESSION['gid'])){
    require_once("../functions.php");
    if (isset($_POST['action'])) {
      $action = trim($_POST['action']);
    }
    if(empty($action)) {
      header("HTTP/1.0 400 Bad Request",false,400);die();
    }
    $link = db_connect();
    switch ($action) {
      case 'privacy':{
        $settings = json_encode(array(
    			'hide-phone' => ($_POST['phone'] == "true") ? true : false,
    			'hide-email' => ($_POST['email'] == "true") ? true : false
    		));
        if(!send_in_base($link,"UPDATE `Students` SET `settings`='".$settings."' WHERE `id`=".$_SESSION['id'])){
          header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
				}else {
          exit(json_encode(array(
            'response'=> true
          )));
        }
      }break;
      case 'self':{
        $errors = array();
        if (isset($_POST['dob'])) {
          if (strtotime($_POST['dob']) > strtotime("now") || strlen(substr($_POST['dob'], 0, strpos($_POST['dob'], '-'))) > 4) {
            $errors[] = array(
              'error_code'=>4,
              'text'=>"Ты что из будущего?"
            );
          }elseif (strtotime($_POST['dob']) < strtotime("1970-01-01")) {
            $errors[] = array(
              'error_code'=>4,
              'text'=>"Год должен быть больше 1970"
            );
          }elseif (empty($_POST['dob'])) {
            $errors[] = array(
              'error_code'=>4,
              'text'=>"Что-то не так с датой"
            );
          }
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
        if (isset($_POST['gender'])){
          if ($_POST['gender'] < -1 || $_POST['gender'] > 1) {
            $errors[] = put_error(6, 'Давай-ка не будешь баловаться с этим?');
          }
        } else {
          $errors[] = put_error(6, 'Неправильное обращение');
        }
        if (isset($_POST['email'])) {
          if (mb_strlen($_POST['email']) == 0) {
            $errors[] = put_error(7, "Заполните почтовый адрес");
          }else if (strlen($_POST['email']) != strlen(str_replace(" ", "", $_POST['email']))) {
            $errors[] = array(
              'error_code'=>7,
              'text'=>"Тебе не кажется что пробелы тут лишние? Я их уберу сам."
            );
    			}else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = array(
              'error_code'=>7,
              'text'=>"Некорректный почтовый адрес 🤷‍♂️"
            );
    			}else if(get_more_count($link, "Students", "`email`='".$_POST['email']."' AND `id`!=".$_SESSION['id']) > 0){
            $errors[] = array(
              'error_code'=>7,
              'text'=>"Такой адрес уже у кого-то есть 🤷‍♂️"
            );
    			}else if(get_count($link,"Teachers", "email", $_POST['email']) > 0){
            $errors[] = array(
              'error_code'=>7,
              'text'=>"Я тут посмотрел, и нашёл такой адрес у одного из учителей, давай введём другой?"
            );
    			}
    		}else {
          $errors[] = put_error(7, 'Неправильное обращение');
        }
        if(count($errors) > 0){
          header("HTTP/1.0 400 Bad Request",false,400);
          die(json_encode(array(
            'errors'=> $errors
          )));
        }
        $query = "UPDATE `Students` SET `date-of-birth`=".((isset($_POST['dob']))?"'".$_POST['dob']."'":'NULL').", `email`='".$_POST['email']."', `phone`='".$_POST['phone'].
        "', `gender`=".(($_POST['gender'] != -1)?$_POST['gender']:'NULL')." WHERE `id`=".$_SESSION['id']." AND `group-id`=".$_SESSION['gid'];
        if(!send_in_base($link,$query)){
          header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
				}else {
          exit(json_encode(array(
            'response'=> true
          )));
        }
      }break;
      default:{
        header("HTTP/1.0 400 Bad Request",false,400);
        die(json_encode(array(
          'error_code'=>1,
          'text'=>"Что-то пошло не так."
        )));
      }break;
    }
  }else {
    header("HTTP/1.0 401 Unauthorized",false,401);
    die(json_encode(array(
      'error_code'=>2,
      'text'=>"Сессия окончена. Авторизуйся заново.<br>Сейчас я перезагружу страницу..."
    )));
  }
}
?>

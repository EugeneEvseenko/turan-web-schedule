<?php
  session_start();
  if(!empty($_SESSION['login']) && !empty($_SESSION['id']) && !empty($_SESSION['gid'])){
    require_once("../functions.php");
    if($_SERVER['REQUEST_METHOD'] == "GET"){
      if (isset($_GET['action'])) {
        $action = trim($_GET['action']);
      }
    }else if($_SERVER['REQUEST_METHOD'] == "POST"){
      if (isset($_POST['action'])) {
        $action = trim($_POST['action']);
      }
    }
    if(empty($action)) {
      header("HTTP/1.0 400 Bad Request",false,400);die();
    }
    $link = db_connect();
    switch ($action) {
      case 'loadTeacher':{
        if(isset($_GET['tid'])){
          if($_GET['tid'] != -1){
            if (get_data($link, 'Teachers', 'group-id', 'id', $_GET['tid']) != $_SESSION['gid']) {
              header("HTTP/1.0 400 Bad Request",false,400);
              die(array(
                      'error_code'=> 1,
                      'text'=> 'Преподаватель не найден в вашей группе'));
            }
          }
        }
        $teacher = get_data($link, 'Teachers', '*', 'id', $_GET['tid']);
        $teacher['isCurator'] = get_data($link, 'Groups', 'teacher-id', 'id', $_SESSION['gid']) == $_GET['tid'];
        exit(json_encode($teacher));
      }break;
      case 'getTeachers':{
        exit(json_encode(get_all_data($link, 'Teachers', '*', 'group-id', $_SESSION['gid'])));
      }break;
      case 'teacher':{
        $errors = array();
        if(isset($_POST['tid'])){
          if($_POST['tid'] != -1){
            if (get_data($link, 'Teachers', 'group-id', 'id', $_POST['tid']) != $_SESSION['gid']) {
              $errors[] = array(
                      'error_code'=> 1,
                      'text'=> 'Преподаватель не найден в вашей группе');
            }
          }
        }else {
          $errors[] = array(
                  'error_code'=> 1,
                  'text'=> 'Неправильное обращение');
        }
        if(isset($_POST['name'])){
          if (strlen($_POST['name']) == 0) {
            $errors[] = array(
              'error_code'=>5,
              'text'=>"Заполните ФИО"
            );
          }
        }else {
          $errors[] = array(
                  'error_code'=> 5,
                  'text'=> 'Неправильное обращение');
        }
        if(isset($_POST['phone'])){
          if (strlen($_POST['phone']) == 0) {
            $errors[] = array(
              'error_code'=>2,
              'text'=>"Заполните номер телефона"
            );
          }else if (strlen($_POST['phone']) != 16) {
            $errors[] = array(
              'error_code'=>2,
              'text'=>"Неверный формат номера телефона 🤷‍♂️"
            );
          }
        }else {
          $errors[] = array(
                  'error_code'=> 2,
                  'text'=> 'Неправильное обращение');
        }
        if(isset($_POST['email'])){
          if (strlen($_POST['email']) == 0) {
            $errors[] = array(
              'error_code'=>3,
              'text'=>"Заполните почтовый адрес"
            );
          }else if (strlen($_POST['email']) != strlen(str_replace(" ", "", $_POST['email']))) {
            $errors[] = array(
              'error_code'=>3,
              'text'=>"Тебе не кажется что пробелы тут лишние? Я их уберу сам."
            );
    			}else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = array(
              'error_code'=>4,
              'text'=>"Некорректный почтовый адрес 🤷‍♂️"
            );
    			}else if(get_more_count($link, 'Teachers', "`email`='".$_POST['email']."' AND `group-id`=".$_SESSION['gid']) > 0 && $_POST['tid'] == -1){
            $errors[] = array(
              'error_code'=>4,
              'text'=>"Этот почтовый адрес уже закреплен другим учителем"
            );
    			}
        }else {
          $errors[] = array(
                  'error_code'=> 3,
                  'text'=> 'Неправильное обращение');
        }
        if(count($errors) > 0){
          header("HTTP/1.0 400 Bad Request",false,400);
          die(json_encode(array(
            'errors'=> $errors
          )));
        }
        if ($_POST['tid'] == -1){
          if(!send_in_base($link,"INSERT INTO `Teachers` (`id`, `name`, `phone`, `email`, `group-id`) VALUES (NULL, '".trim($_POST['name'])."', '".trim($_POST['phone'])."', '".trim($_POST['email'])."', ".$_SESSION['gid'].")")){
            header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
          }else {
            $_POST['tid'] = $link->insert_id;
          }
        }else {
          if(!send_in_base($link,"UPDATE `Teachers` SET `name`='".trim($_POST['name'])."', `phone`='".trim($_POST['phone'])."', `email`='".trim($_POST['email'])."' WHERE `group-id`=".$_SESSION['gid']." AND `id`=".$_POST['tid'])){
            header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
          }
        }
        if(isset($_POST['isCurator'])){
          if($_POST['isCurator'] == 'true'){
            if(!send_in_base($link,"UPDATE `Groups` SET `teacher-id`=".$_POST['tid']." WHERE `id`=".$_SESSION['gid'])){
              header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
            }
          }else if($_POST['isCurator'] == 'false' && get_data($link, 'Groups', 'teacher-id', 'id', $_SESSION['gid']) == $_POST['tid']){
            if(!send_in_base($link,"UPDATE `Groups` SET `teacher-id`=NULL WHERE `id`=".$_SESSION['gid'])){
              header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
            }
          }
        }
        exit(json_encode(array('response'=> 'ok', 'id'=>$_POST['tid'], 'gid'=>$_SESSION['gid'], 'isCurator'=>$_POST['isCurator'])));
      }break;
      case 'preRemove':{
        $out = array();
        if(isset($_GET['lid'])){
          $out['schedules'] = (int)get_count($link, 'Schedule', 'lesson-id', $_GET['lid']);
        }else if (isset($_GET['tid']) && !isset($_GET['lid'])) {
          $lessons = get_all_data($link, 'Lessons', '*', 'teacher-id', $_GET['tid']);
          $out['lessons'] = count($lessons);
          if(count($lessons) > 0){
            $counter = 0;
            $out['schedules'] = 0;
            foreach ($lessons as $key => $lesson) {
              $counter += (int)get_count($link, 'Schedule', 'lesson-id', $lesson['id']);
            }
            $out['schedules'] = $counter;
          }else {
            $out['schedules'] = 0;
          }
        }else if(isset($_GET['sid'])){
          if(get_more_count($link, 'Students', "`id`=".$_GET['sid']." AND `group-id`=".$_SESSION['gid'])){
            exit(json_encode(array('response'=>'ok')));
          }else {
            header("HTTP/1.0 400 Bad Request",false,400);
          }
        }else {
          header("HTTP/1.0 500 Internal Server Error",false,500);die();
        }
        exit(json_encode($out));
      }break;
      case 'remove':{
        if( $_POST['tid'] == -1 && $_POST['lid'] != -1){
          if (get_count($link, 'Lessons', 'id', $_POST['lid']) == 0) {
            header("HTTP/1.0 500 Internal Server Error",false,500);die(json_encode(array('error_code'=> 3,'text'=> 'Такого урока нет, обновите страницу')));
          }else {
            $countlessons = 0;
            if(!send_in_base($link,"DELETE FROM `Lessons` WHERE `id`=".$_POST['lid']." AND `group-id`=".$_SESSION['gid'])){
              header("HTTP/1.0 500 Internal Server Error",false,500);die();
            }else {
              $countlessons = get_count($link, 'Schedule', 'lesson-id', $_POST['lid']);
              if($countlessons != 0){
                if(!send_in_base($link,"DELETE FROM `Schedule` WHERE `lesson-id`=".$_POST['lid']." AND `group-id`=".$_SESSION['gid'])){
                  header("HTTP/1.0 500 Internal Server Error",false,500);die();
                }
              }
            }
            exit(json_encode(array('deleted_id'=>$_POST['lid'], 'deleted_schedule'=>$countlessons)));
          }
        }elseif ($_POST['tid'] != -1 && $_POST['lid'] == -1) {
          if (get_count($link, 'Teachers', 'id', $_POST['tid']) == 0) {
            header("HTTP/1.0 500 Internal Server Error",false,500);die(json_encode(array('error_code'=> 3,'text'=> 'Такого преподавателя нет, обновите страницу')));
          }else {
            if(!send_in_base($link,"DELETE FROM `Teachers` WHERE `id`=".$_POST['tid']." AND `group-id`=".$_SESSION['gid'])){
              header("HTTP/1.0 500 Internal Server Error",false,500);die();
            }else {
              $lessons = get_all_data($link, 'Lessons', '*', 'teacher-id', $_POST['tid']);
              if(count($lessons) != 0){
                if(!send_in_base($link,"DELETE FROM `Lessons` WHERE `teacher-id`=".$_POST['tid']." AND `group-id`=".$_SESSION['gid'])){
                  header("HTTP/1.0 500 Internal Server Error",false,500);die();
                }
                foreach ($lessons as $key => $lesson) {
                  if(!send_in_base($link,"DELETE FROM `Schedule` WHERE `lesson-id`=".$lesson['id']." AND `group-id`=".$_SESSION['gid'])){
                    header("HTTP/1.0 500 Internal Server Error",false,500);die();
                  }
                }
              }
            }
            exit(json_encode(array('deleted_id'=>$_POST['tid'])));
          }
        }else if($_POST['sid'] != -1){
          if(!send_in_base($link,"DELETE FROM `Students` WHERE `id`=".$_POST['sid']." AND `group-id`=".$_SESSION['gid'])){
            header("HTTP/1.0 500 Internal Server Error",false,500);die();
          }
          exit(json_encode(array('deleted_id'=>$_POST['sid'])));
        } else {
          header("HTTP/1.0 500 Internal Server Error",false,500);die(json_encode(array('error_code'=> 3,'text'=> 'Не удалось определить операцию')));
        }
      }break;
      case 'addLesson':{
        $errors = array();
        if(!isset($_POST['lesson']) || empty(trim($_POST['lesson']))){
          $errors[] = array(
                  'error_code'=> 1,
                  'text'=> 'Поле не может быть пустым');
        }elseif (mb_strlen(trim($_POST['lesson'])) > 50) {
          $errors[] = array(
                  'error_code'=> 1,
                  'text'=> 'Поле должно содержать не более 50 символов');
        }
        if(!isset($_POST['tid']) || $_POST['tid'] == -1){
          $errors[] = array(
                  'error_code'=> 2,
                  'text'=> 'Нужно выбрать преподавателя');
        }
        if(count($errors) > 0){
          header("HTTP/1.0 400 Bad Request",false,400);
          die(json_encode(array(
            'errors'=> $errors
          )));
        }
        if (get_count($link, 'Teachers', 'id', $_POST['tid']) == 0) {
          header("HTTP/1.0 500 Internal Server Error",false,500);die(json_encode(array('error_code'=> 3,'text'=> 'Такого преподавателя нет, обновите страницу')));
        }
        if (!isset($_POST['lid']) || empty($_POST['lid'])){
          header("HTTP/1.0 500 Internal Server Error",false,500);die(json_encode(array('error_code'=> 3,'text'=> 'Не удалось определить операцию')));
        }
        $name = trim($_POST["lesson"]);
        if($_POST['lid'] == -1){
          if(!send_in_base($link,"INSERT INTO `Lessons` (`id`, `name`, `group-id`, `teacher-id`) VALUES (NULL, '".$name."', ".$_SESSION['gid'].", ".$_POST['tid'].")")){
            header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
          }
        }else {
          if(!send_in_base($link,"UPDATE `Lessons` SET `name`='".$name."', `teacher-id`=".$_POST['tid']." WHERE `group-id`=".$_SESSION['gid']." AND `id`=".$_POST['lid'])){
            header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
          }
        }
        exit(json_encode(array('response'=>'ok')));
      }break;
      case 'loadLessons':{
        $param = array(
          'group-id'=>$_SESSION['gid'],
          'num-day'=> $_GET['day']
        );
        $schedule = get_all_data($link, "Schedule", "*", $param, null);
        $lessons = get_all_data($link, "Lessons", "*", 'group-id', $_SESSION['gid']);
        $calls = count(get_all_data($link, 'Calls', '*'));
        $out = array(
          'target' => $schedule,
          'lessons' => $lessons,
          'calls' => $calls
        );
        exit(json_encode($out));
      }break;
      case 'putLesson':{
        $errors = array();
        if(!isset($_POST['day'])){
          $errors[] = array(
                  'error_code'=> 1,
                  'text'=> 'Неправильный запрос данных.');
        }else if($_POST['day'] < 1 || $_POST['day'] > 7){
          $errors[] = array(
                  'error_code'=> 2,
                  'text'=> 'Неправильный запрос данных.');
        }
        if(!isset($_POST['lessons'])){
          $errors[] = array(
                  'error_code'=> 3,
                  'text'=> 'Неправильный запрос данных.');
        }else if(count($_POST['lessons']) != count(get_all_data($link, 'Calls','*'))){
          $errors[] = array(
                  'error_code'=> 4,
                  'text'=> 'Неправильный запрос данных.');
        }
        if(count($errors) > 0){
          header("HTTP/1.0 400 Bad Request",false,400);
          die(json_encode(array(
            'is_error'=>true,
            'errors'=> $errors
          )));
        }
        foreach ($_POST['lessons'] as $key => $lesson) {
          if(($lesson['lesson-id'] != -1) && (is_null($lesson['num-room']) || empty($lesson['num-room']))){
            $errors[] = array(
                    'error_code'=> 5,
                    'text'=> 'Заполните поле ввода аудитории.',
                    'item'=> $lesson);
          }else if(strlen($lesson['num-room']) < 0 || strlen($lesson['num-room']) > 4){
            $errors[] = array(
                    'error_code'=> 5,
                    'text'=> 'Максимум четырёхзначное число.',
                    'item'=> $lesson);
          }
        }
        if(count($errors) > 0){
          header("HTTP/1.0 400 Bad Request",false,400);
          die(json_encode(array(
            'is_error'=>true,
            'errors'=> $errors
          )));
        }
        $added = 0;
        $updated = 0;
        $deleted = 0;
        foreach ($_POST['lessons'] as $key => $lesson) {
          if($lesson['lid'] == -1 && $lesson['lesson-id'] != -1){
            if(!send_in_base($link,"INSERT INTO `Schedule` (`id`, `lesson-id`, `group-id`, `num-lesson`, `num-day`, `num-room`) VALUES (NULL, ".$lesson['lesson-id'].", ".$_SESSION['gid'].", ".$lesson['num-lesson'].", ".$_POST['day'].", ".$lesson['num-room'].")")){
              header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
            }
            $added++;
          }else if ($lesson['lid'] != -1 && $lesson['lesson-id'] != -1){
            if(!send_in_base($link,"UPDATE `Schedule` SET `lesson-id`=".$lesson['lesson-id'].", `num-room`=".$lesson['num-room'].", `num-lesson`=".$lesson['num-lesson']."  WHERE `group-id`=".$_SESSION['gid']." AND `id`=".$lesson['lid'])){
              header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
            }
            $updated++;
          }else if ($lesson['lid'] != -1 && $lesson['lesson-id'] == -1) {
            if(!send_in_base($link,"DELETE FROM `Schedule` WHERE `id`=".$lesson['lid']." AND `group-id`=".$_SESSION['gid'])){
              header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
            }
            $deleted++;
          }
        }
        exit(json_encode(array('response'=>'ok')));
      }break;
      case 'sendInvite':{
        $errors = array();
        if(!isset($_POST['name'])){
          $errors[] = array(
                  'error_code'=> 1,
                  'text'=> 'Неправильный запрос');
        }elseif (empty(trim($_POST['name']))) {
          $errors[] = array(
                  'error_code'=> 1,
                  'text'=> 'Заполни имя');
        }
        if(!isset($_POST['email'])){
          $errors[] = array(
                  'error_code'=> 2,
                  'text'=> 'Неправильный запрос');
        }elseif (empty(trim($_POST['email']))) {
          $errors[] = array(
                  'error_code'=> 2,
                  'text'=> 'Заполни почтовый адрес');
        }else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          $errors[] = array(
                  'error_code'=> 2,
                  'text'=> 'Некорректный почтовый адрес');
  			}else if(get_more_count($link, 'Students', "`email`='".$_POST['email']."' AND `group-id`=".$_SESSION['gid']) > 0){
          if($_POST['sid'] != -1){
            if(get_data($link, 'Students', 'id', 'email', "'".$_POST['email']."'") != $_POST['sid']){
              $errors[] = array(
                      'error_code'=> 2,
                      'text'=> 'Такой адрес уже зарегистрирован в этой группе');
            }
          }else {
            $errors[] = array(
                    'error_code'=> 2,
                    'text'=> 'Такой адрес уже зарегистрирован в этой группе');
          }
  			}
        if(count($errors) > 0){
          header("HTTP/1.0 400 Bad Request",false,400);
          die(json_encode(array(
            'is_error'=>true,
            'errors'=> $errors
          )));
        }
        $_POST['name'] = stripslashes($_POST['name']);
		    $_POST['name'] = htmlspecialchars($_POST['name']);
		    $_POST['name'] = trim($_POST['name']);
        $_POST['email'] = stripslashes($_POST['email']);
		    $_POST['email'] = htmlspecialchars($_POST['email']);
		    $_POST['email'] = trim($_POST['email']);
        $token = RandomToken();
        if ($_POST['sid'] == '-1') {
          if(!send_in_base($link,"INSERT INTO `Students`
            (`id`, `login`, `password`, `name`, `email`, `gender`, `date-of-birth`, `phone`, `status`, `group-id`, `last-session`, `settings`, `token`, `state`)
            VALUES (NULL, NULL, NULL, '".$_POST['name']."', '".$_POST['email']."', NULL, NULL, NULL, NULL, '".$_SESSION['gid']."', NULL, NULL, '".$token."', 'waiting-registration')")){
            header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
          }
        }else {
          if(!send_in_base($link,"UPDATE `Students` SET
            `name`='".$_POST['name']."', `email`='".$_POST['email']."', `token`='".$token."', `state`='waiting-registration' WHERE `group-id`=".$_SESSION['gid']." AND `id`=".$_POST['sid'])){
            header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
          }
        }
        $subject = "Приглашение на регистрацию аккаунта Turan Schedule";
        $message = "<html>
        <head>
        <title>Регистрация аккаунта " . $_POST['email'] . "</title>
        </head>
        <body>";
        $message .= "<p>Здравствуйте ".$_POST['name']."! ";
        $message .= "Для того чтобы использовать свой аккуант, нужно пройти регистрацию.<br />
        Чтобы зарегистрировать ваш аккаунт, перейдите по ссылке:<br /><br />
        https://turan.evseenko.kz/registration?token=".$token."<br /><br />
        С уважением, Администрация сайта.</p>";
        $message .= "<br /> <p>------------------------------------------------------------------------------------------------------</p> <br /> <p>На это сообщение не нужно отвечать! </p><br />";
        $message .= "</body>
        </html>";
        $from =  "Turan Schedule Invite <register@evseenko.kz>";
        $headers = "From: ".$from."\r\n";
        $headers .= "Subject: Приглашение на регистрацию аккаунта Turan Schedule\r\n";
        $headers .= "Reply-To: register@evseenko.kz\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=\"utf-8\"";
        $headers .= "X-Mailer: PHP/" . phpversion();
        ini_set("sendmail_from", $_POST['email']);
        if (mail($_POST['email'], $subject, $message, $headers)){
          exit(json_encode(array('response'=>'ok')));
        } else {
          header("HTTP/1.0 500 Internal Server Error",false,500);die();
        }
      }break;
      default:{
        header("HTTP/1.0 400 Bad Request",false,400);die();
      }break;
    }
  }else {
    header("HTTP/1.0 401 Unauthorized",false,401);die();
  }
?>

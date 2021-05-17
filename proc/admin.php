<?php
  session_start();
  if(!empty($_SESSION['login']) && !empty($_SESSION['id'])){
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
    $link = db_connect();
    switch ($action) {
      case 'changeGroup':{
        if(isset($_POST['gid'])){
          $_SESSION['gid'] = $_POST['gid'];
          exit(json_encode(array('response'=>'ok')));
        }
      }break;
      case 'preAddGroup':{
        if(isset($_GET['group'])){
          $students = array(); $teachers = array();
          if($_GET['group'] != -1){
            $students = get_all_data($link, 'Students', '*', 'group-id', $_GET['group'], null, true, "`id`!=".$_SESSION['id']);
            $teachers = get_all_data($link, 'Teachers', '*', 'group-id', $_GET['group']);
          }
          exit(json_encode(array('students'=>$students, 'teachers'=>$teachers)));
        }else {
          header("HTTP/1.0 400 Bad Request",false,400);
          die(json_encode(array(
            'errors'=> array(put_error(1, 'Неправильное обращение'))
          )));
        }
      }break;
      case 'addGroup':{
        if(!isset($_POST['gid'])){
          header("HTTP/1.0 400 Bad Request",false,400);
          die(json_encode(array(
            'errors'=> array(put_error(0, 'Неправильное обращение'))
          )));
        }
        $errors = array();
        if(isset($_POST['name'])){
          if (mb_strlen(trim($_POST['name'])) == 0) {
            $errors[] = array(
              'error_code'=>1,
              'text'=>"Заполните название группы"
            );
          }elseif (mb_strlen(trim($_POST['name'])) > 50) {
            $errors[] = array(
              'error_code'=>1,
              'text'=>"Название группы должно быть не более 50 символов"
            );
          }
        }else {
          $errors[] = array(
                  'error_code'=> 1,
                  'text'=> 'Неправильное обращение');
        }
        if(count($errors) > 0){
          header("HTTP/1.0 400 Bad Request",false,400);
          die(json_encode(array(
            'errors'=> $errors
          )));
        }
        if ($_POST['gid'] == -1) {
          if(!send_in_base($link,"INSERT INTO `Groups` (`id`, `name`, `teacher-id`, `headman`) VALUES (NULL, '".trim($_POST['name'])."', NULL, NULL)")){
            header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
          }else {
            exit(json_encode(array('response'=>'ok', 'gid'=>$link->insert_id)));
          }
        }else {
          if(get_count($link, 'Groups', 'id', $_POST['gid']) == 0){
            header("HTTP/1.0 400 Bad Request",false,400);
            die(json_encode(array(
              'errors'=> array(put_error(0, 'Группа не существует'))
            )));
          }
          if(isset($_POST['tid'])){
            if($_POST['tid'] != -1){
              if (get_count($link, 'Teachers', 'id', $_POST['tid']) == 0) {
                $errors[] = array(
                  'error_code'=>2,
                  'text'=>"Такого преподавателя уже нет, обновите страницу"
                );
              }
            }else {
              $_POST['tid'] = 'NULL';
            }
          }else {
            $errors[] = array(
                    'error_code'=> 2,
                    'text'=> 'Неправильное обращение');
          }
          if(isset($_POST['hid'])){
            if($_POST['hid'] != -1){
              if (get_count($link, 'Students', 'id', $_POST['hid']) == 0) {
                $errors[] = array(
                  'error_code'=>3,
                  'text'=>"Такого студента уже нет, обновите страницу"
                );
              }
            }else {
              $_POST['hid'] = 'NULL';
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
          if(!send_in_base($link,"UPDATE `Groups` SET `name`='".trim($_POST['name'])."', `teacher-id`=".$_POST['tid'].", `headman`=".$_POST['hid']." WHERE `id`=".$_POST['gid'])){
            header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
          }else {
            exit(json_encode(array('response'=>'ok', 'gid'=>$_POST['gid'])));
          }
        }
      }break;
      case 'preRemove':{
        if (isset($_GET['gid'])) {
          $teachers = (int)get_count($link, 'Teachers', 'group-id', $_GET['gid']);
          $lessons = (int)get_count($link, 'Lessons', 'group-id', $_GET['gid']);
          $schedules = (int)get_count($link, 'Schedule', 'group-id', $_GET['gid']);
          $students = (int)get_count($link, 'Students', 'group-id', $_GET['gid']);
          exit(json_encode(array('gid'=>(int)$_GET['gid'], 'teachers'=>$teachers, 'lessons'=>$lessons, 'schedules'=>$schedules, 'students'=>$students)));
        }else {
          header("HTTP/1.0 400 Bad Request",false,400);
          die(json_encode(array(
            'errors'=> array(put_error(0, 'Неправильное обращение'))
          )));
        }
        exit(json_encode($out));
      }break;
      case 'remove':{
        if(isset($_POST['gid'])){
          if(!send_in_base($link,"DELETE FROM `Schedule` WHERE `group-id`=".$_POST['gid'])){
            header("HTTP/1.0 500 Internal Server Error",false,500);die();
          }else {
            if(!send_in_base($link,"DELETE FROM `Lessons` WHERE `group-id`=".$_POST['gid'])){
              header("HTTP/1.0 500 Internal Server Error",false,500);die();
            }else {
              if(!send_in_base($link,"DELETE FROM `Teachers` WHERE `group-id`=".$_POST['gid'])){
                header("HTTP/1.0 500 Internal Server Error",false,500);die();
              }else {
                if(!send_in_base($link,"DELETE FROM `Students` WHERE `group-id`=".$_POST['gid'])){
                  header("HTTP/1.0 500 Internal Server Error",false,500);die();
                }else {
                  if(!send_in_base($link,"DELETE FROM `Groups` WHERE `id`=".$_POST['gid'])){
                    header("HTTP/1.0 500 Internal Server Error",false,500);die();
                  }
                }
              }
            }
          }
          exit(json_encode(array('response'=>'ok', 'gid'=>$_POST['gid'])));
        }else {
          header("HTTP/1.0 400 Bad Request",false,400);
          die(json_encode(array(
            'errors'=> array(put_error(0, 'Неправильное обращение'))
          )));
        }
      }break;
      default:{
        header("HTTP/1.0 400 Bad Request",false,400);
        die(json_encode(array(
          'errors'=> array(put_error(0, 'Такого запроса нет.'))
        )));
      }break;
    }
  }else {
    header("HTTP/1.0 401 Unauthorized",false,401);die();
  }
?>

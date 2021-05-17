<?php
  require_once("functions.php");
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
    header("HTTP/1.0 400 Bad Request",false,400);die('Неправильное обращение');
  }
  $link = db_connect();
  switch ($action) {
    case 'auth':{
      if (isset($_POST['inputLogin'])) {
  			$login = $_POST['inputLogin'];
  			if ($login == '') {
  				unset($login);
  			}
  		}
  		if (isset($_POST['inputPassword'])) {
  			$password=$_POST['inputPassword'];
  			if ($password =='') {
  				unset($password);
  			}
  		}
      $errors = validateLoginFields($login, $password);
      if(count($errors) > 0){
        header("HTTP/1.0 400 Bad Request",false,400);
        die(json_encode(array(
          'is_error'=>true,
          'errors'=> $errors
        )));
      }
  		$login = stripslashes($login);
  		$login = htmlspecialchars($login);
  		$password = stripslashes($password);
  		$password = htmlspecialchars($password);
  		$login = trim($login);
  		$password = trim($password);
  		$dbcon = db_connect();
  		$query = "SELECT * FROM `Students` WHERE `login`='$login'";
  			$result = mysqli_query($dbcon,$query);
  			if(!$result){
  				die(mysqli_error($dbcon));
  			}
  		$myrow = mysqli_fetch_assoc($result);
  		if (empty($myrow["password"])){
        header("HTTP/1.0 401 Unauthorized",false,401);
        exit (json_encode(
          array(
            'is_error'=> true,
            'errors'=>array(
              array(
                'error_code'=> 3,
                'text'=> 'Неверный логин или пароль.'
              )
            )
          )
        ));
  		}else {
  				if ($myrow["password"]===$password) {
            $token = RandomToken();
  					$sql = mysqli_query($dbcon, "UPDATE `Students` SET `last-session`=CURRENT_TIMESTAMP, `token`='".$token."'  WHERE `id` = ".$myrow["id"]);
  					if (!$sql) {
              header("HTTP/1.0 500 Internal Server Error",false,500);
  					  exit ('<p>Произошла ошибка: ' . mysqli_error($dbcon) . '</p>');
  					}else{
              exit (json_encode(
                array(
                  'is_error'=> false,
                  'response'=> 'Авторизация пройдена',
                  'token'=>$token
                  )
              ));
  					}
  				}else {
            header("HTTP/1.0 401 Unauthorized",false,401);
            exit (json_encode(
              array(
                'is_error'=> true,
                'errors'=>array(
                  array(
                    'error_code'=> 3,
                    'text'=> 'Неверный логин или пароль.'
                  )
                )
              )
            ));
  		    }
  		}
    }break;
    case 'checkAuth':{
      if (!isset($_POST['token'])) {
        header("HTTP/1.0 400 Bad Request",false,400);die('Неправильное обращение');
      }else {
        if (get_count($link, 'Students', 'token', $_POST['token'])) {
          exit (json_encode(
            array(
              'is_error'=> false,
              'response'=> 'Сессия актуальна',
              'token'=>$_POST['token']
              )
          ));
        }else {
          header("HTTP/1.0 401 Unauthorized",false,401);
          exit (json_encode(
            array(
              'is_error'=> true,
              'response'=> 'Сессия прекращена'
              )
          ));
        }
      }
    }break;
    case 'profile':{
      if (!isset($_POST['token'])) {
        header("HTTP/1.0 400 Bad Request",false,400);die('Неправильное обращение');
      }else {
        if (get_count($link, 'Students', 'token', $_POST['token'])) {
          update_activity($link, $_POST['token']);
          $default_settings = array(
      			'hide-phone' => false,
      			'hide-email' => false
      		);
      		$self = get_data($link, 'Students', '*', 'token', $_POST['token']);
      		$self['settings'] = (is_null($self['settings'])) ? $default_settings : json_decode($self['settings'], true);
          if (!is_null($self['group-id'])) {
            $group = get_data($link, 'Groups', '*', 'id', $self['group-id']);
            $group['teacher'] = (!is_null($group['teacher-id'])) ? get_data($link, 'Teachers', '*', 'id', $group['teacher-id']) : null;
            unset($group['teacher-id']);
            if (!is_null($group['headman'])) {
              $group['headman'] = get_data($link, 'Students', '*', 'id', $group['headman']);
              array_splice($group['headman'], 1, 2);
              array_splice($group['headman'], 8);
            }
          }else {
            $group = null;
          }
          $self['group'] = $group;
          unset($self['group-id']);
          exit (json_encode(
            array(
              'is_error'=> false,
              'response'=> 'Сессия актуальна',
              'profile'=>$self
              )
          ));
        }else {
          header("HTTP/1.0 401 Unauthorized",false,401);
          exit (json_encode(
            array(
              'is_error'=> true,
              'response'=> 'Сессия прекращена'
              )
          ));
        }
      }
    }break;
    case 'calls':{
      if (!isset($_POST['token'])) {
        header("HTTP/1.0 400 Bad Request",false,400);die('Неправильное обращение');
      }else {
        if (get_count($link, 'Students', 'token', $_POST['token'])) {
          update_activity($link, $_POST['token']);
          $calls = get_calls($link);
          exit (json_encode(
            array(
              'is_error'=> false,
              'response'=> 'Сессия актуальна',
              'calls'=>$calls
              )
          ));
        }else {
          header("HTTP/1.0 401 Unauthorized",false,401);
          exit (json_encode(
            array(
              'is_error'=> true,
              'response'=> 'Сессия прекращена'
              )
          ));
        }
      }
    }break;
    case 'schedule':{
      if (!isset($_POST['token'])) {
        header("HTTP/1.0 400 Bad Request",false,400);die('Неправильное обращение');
      }else {
        if (get_count($link, 'Students', 'token', $_POST['token'])) {
          update_activity($link, $_POST['token']);
          $self = get_data($link, 'Students', '*', 'token', $_POST['token']);
          if (!is_null($self['group-id'])) {
            $schedule = get_schedule($link, $self['group-id'], get_calls($link));
            exit (json_encode(
              array(
                'is_error'=> false,
                'response'=> 'Сессия актуальна',
                'schedule'=> $schedule
                )
            ));
          }else {
            exit (json_encode(
              array(
                'is_error'=> true,
                'response'=> 'У тебя нет группы'
                )
            ));
          }
        }else {
          header("HTTP/1.0 401 Unauthorized",false,401);
          exit (json_encode(
            array(
              'is_error'=> true,
              'response'=> 'Сессия прекращена'
              )
          ));
        }
      }
    }break;
    default:{
      header("HTTP/1.0 400 Bad Request",false,400);die('Неправильное обращение');
    }break;
  }
?>

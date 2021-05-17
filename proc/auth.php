<?php
    session_start();
	if($_POST) {
    require_once("../functions.php");
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
					$_SESSION['login']=$myrow["login"];
					$_SESSION['id']=$myrow["id"];
          $_SESSION['gid']=($myrow['is-admin'])?'0':$myrow["group-id"];
					$sql = mysqli_query($dbcon, "UPDATE `Students` SET `last-session`=CURRENT_TIMESTAMP WHERE `id` = ".$myrow["id"]);
					if (!$sql) {
            header("HTTP/1.0 500 Internal Server Error",false,500);
					  exit ('<p>Произошла ошибка: ' . mysqli_error($dbcon) . '</p>');
					}else{
            exit (json_encode(
              array(
                'is_error'=> false
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
	}else {
    header("HTTP/1.0 403 Forbidden",false,403);die();
  }
?>

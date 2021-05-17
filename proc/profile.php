<?php
header('Content-type: text/html; charset=utf-8');
  session_start();
	if($_POST) {
    if(!empty($_SESSION['login']) && !empty($_SESSION['id'])){
    require_once("../functions.php");
    if (isset($_POST['action'])) {
			$action = trim($_POST['action']);
			if ($action == '') {
				unset($action);
			}
		}
    $link = db_connect();
    switch ($action) {
      case 'status':{
        if (isset($_POST['data'])) {
    			$data = trim($_POST['data']);
    		}
        $data = stripslashes($data);
    		$data = htmlspecialchars($data);
        $query = "UPDATE `Students` SET `status`='".$data."' WHERE `id` = ".$_SESSION["id"];
        if ($data == '') {
          $query = "UPDATE `Students` SET `status`=NULL WHERE `id` = ".$_SESSION["id"];
        }
        mysqli_query ($link,"set character_set_results='utf8mb4'");
        mysqli_query ($link,"set collation_connection='utf8mb4_general_ci'");
        mysqli_query($link,"set character_set_client='utf8mb4'");
  			$result = mysqli_query($link,$query);
  			if(!$result){
          exit(json_encode(array(
            'is_error'=>true,
            'error_id'=>3,
            'error_text'=>mysqli_error($link)
          )));
  			}
        if ($data == '') {
          exit(json_encode(array(
            'is_error'=>false,
            'response'=>false
          )));
        }
        exit(json_encode(array(
          'is_error'=>false,
          'response'=>true
        )));
      }break;
      case 'update':{
        $out = array(
          'is_error'=>false,
          'balance' => get_data($link, 'Students', 'balance', 'id', $_SESSION['id']),
          'achievements' => get_more_count($link, 'History', "`student-id`=".$_SESSION['id']." AND `operation-type`=1 AND `action-id`>0"),
          'likes' => get_count($link, 'Likes', "whom", $_SESSION['id'])
        );
        exit(json_encode($out));
      }break;
      case 'like':{
        if (empty($_POST['id'])) {
          exit(json_encode(array(
            'is_error'=>true,
            'error_text'=>"Что-то пошло не так."
          )));
    		}
        if (get_more_count($link, 'Likes', "`who`=".$_SESSION['id']." AND `whom`=".$_POST['id'])) {
          $query = "DELETE FROM `Likes` WHERE `who`=".$_SESSION['id']." AND `whom`=".$_POST['id'];
        }else {
          $query = "INSERT INTO `Likes` (`who`, `whom`) VALUES (".$_SESSION['id'].", ".$_POST['id'].")";
        }
        if(send_in_base($link, $query)){
          exit(json_encode(array(
            'is_error'=>false,
            'likes' => get_count($link, 'Likes', "whom", $_POST['id'])
          )));
        }else {
          exit(json_encode(array(
            'is_error'=>true,
            'error_text'=>mysqli_error($link)
          )));
        }
      }break;
      default:{
        exit(json_encode(array(
          'is_error'=>true,
          'error_id'=>1,
          'error_text'=>"Что-то пошло не так."
        )));
      }break;
    }
  }else {
    exit(json_encode(array(
      'is_error'=>true,
      'error_id'=>2,
      'error_text'=>"Сессия окончена. Авторизуйся заново.<br>Сейчас я перезагружу страницу..."
    )));
  }
}
?>

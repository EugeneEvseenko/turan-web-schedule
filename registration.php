<?php
$settings = json_decode(file_get_contents('./.settings.json') , true);
if ($settings['all-active'] && $settings['active']['registration']){
    session_start();
    require_once ("functions.php");
    $link = db_connect();
    if ($link){
      if(empty($_SESSION['login']) && empty($_SESSION['id']) && empty($_SESSION['gid'])){
        if(isset($_GET['token'])){
          if (get_more_count($link, 'Students', "`token`='".$_GET['token']."' AND (`state`='waiting-registration' OR `state`='registration-started')")){
            if(!send_in_base($link,"UPDATE `Students` SET `state`='registration-started' WHERE `token`='".$_GET['token']."'")){
              header("HTTP/1.0 500 Internal Server Error",false,500);die(mysqli_error( $link ));
            }
            $data = get_data($link,'Students', '*', 'token', $_GET['token']);
            include ("views/registration-view.php");
          } else {
            $error_text = 'unaviable-token';
            include ("views/error.php");
          }
        } else {
          header('Location: /');
        }
      }else {
        $error_text = 'destroy-session';
        include ("views/error.php");
      }
    } else {
        $error_text = 'db-unavailable';
        include ("views/error.php");
    }
  } else {
    include ("views/tech-work.php");
  }
?>

<?php
$settings = json_decode(file_get_contents('./.settings.json') , true);
session_start();
require_once ("functions.php");
$link = db_connect();
if ($link)
{
  if(!empty($_SESSION['login']) && !empty($_SESSION['id'])){
		header('Location: /profile');
	}else{
    if ($settings['all-active'] && $settings['active']['auth']){
			include ("views/login.php");
    } else {
      include ("views/tech-work.php");
    }
	}
}else {
  $error_text = 'db-unavailable';
  include ("views/error.php");
}
?>

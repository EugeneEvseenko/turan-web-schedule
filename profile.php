<?php
$settings = json_decode(file_get_contents('./.settings.json') , true);
session_start();
require_once ("functions.php");
$link = db_connect();
if ($link){
  if(!empty($_SESSION['login']) && !empty($_SESSION['id'])){
    if ($settings['all-active'] && $settings['active']['profile']){
      update_activity($link);
      if (!empty($_GET['id']) && empty($_GET['go'])) {
        $self = get_self_data($link,$_GET['id']);
      }else {
        $self = get_self_data($link,$_SESSION['id']);
      }
      $colorback = 'info';
      if (!is_null($self['group-id'])) {
        $my_group = get_my_group($link, $self['group-id']);
        $headman = $my_group['headman']['id'] == $_SESSION['id'];
      }
      if($self['is-admin']){
        $groups = get_all_data($link, 'Groups', '*');
        if(count($groups) > 0 && $_SESSION['gid'] != 0){
          if(get_count($link, 'Groups', 'id', $_SESSION['gid']) == 0){
            $_SESSION['gid'] = $groups[0]['id'];
          }
        }else if(count($groups) > 0 && $_SESSION['gid'] == 0) {
          $_SESSION['gid'] = $groups[0]['id'];
        }else {
          $_SESSION['gid'] = 0;
        }
      }
      $page = (empty($_GET['go'])) ? "main" : $_GET['go'] ;
      switch ($page) {
        case 'settings':{
          $title = "Мои настройки";
          include ("views/main-head.php");
          include ("views/settings.php");
          include ("views/main-footer.php");
        }break;
        case 'calls':{
          $title = "Расписание звонков";
          $calls = get_calls($link);
          include ("views/main-head.php");
          include ("views/calls.php");
          include ("views/main-footer.php");
        }break;
        case 'calc':{
          $title = "Калькулятор итоговой оценки";
          include ("views/main-head.php");
          include ("views/calc.php");
          include ("views/main-footer.php");
        }break;
        case 'sign-out':{
          session_destroy();
          header("Location:/");
        }break;
        default:{
          $title = $self['name'];
          $calls = get_all_data($link, 'Calls', '*');
          $schedule = get_schedule($link, $self['group-id'],$calls);
          $countlessons = get_count($link, 'Lessons', 'group-id', $_SESSION['gid']);
          include ("views/main-head.php");
          include ("views/profile.php");
          include ("views/main-footer.php");
        }break;
      }
    }else{
      include ("views/tech-work.php");
    }
  }else{
    header('Location: /?redirect=profile');
  }
}else{
    $error_text = 'db-unavailable';
    include ("views/error.php");
}
?>

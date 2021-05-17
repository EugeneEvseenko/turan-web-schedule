<?php
$settings = json_decode(file_get_contents('./.settings.json') , true);
if ($settings['all-active'] && $settings['active']['edit']){
    session_start();
    require_once ("functions.php");
    $link = db_connect();
    if ($link){
      if(!empty($_SESSION['login']) && !empty($_SESSION['id'])){
        update_activity($link);
        $self = get_self_data($link,$_SESSION['id']);
        $colorback = 'info';
        $headman = ((!empty($_SESSION['gid'])) ? get_data($link, 'Groups', 'headman', 'id', $_SESSION['gid']) : 0) == $_SESSION['id'] || $self['is-admin'];
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
        if(!$headman){
          header('Location: /');die();
        }
        $data = get_lessons($link, $_SESSION['gid']);
        $students = (empty($_SESSION['gid'])) ? array() : get_my_group($link, $_SESSION['gid'])['students'];
        if($self['is-admin']){
          $groups = get_all_data($link, 'Groups', '*');
        }
        $title = "Редактор";
        include ("views/main-head.php");
        include ("views/edit.php");
        include ("views/main-footer.php");

  		}else{
  			header('Location: /?redirect=edit');
  		}
    }
    else{
        $error_text = 'db-unavailable';
        include ("views/error.php");
    }
  }
  else{
    include ("views/tech-work.php");
  }
?>

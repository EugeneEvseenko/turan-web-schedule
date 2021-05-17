<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Сервис для просмотра расписания университета Туран." />
        <meta name="author" content="Eugene Evseenko" />
        <title><?=$title?> | Turan Sсhedule</title>
        <link rel="icon" type="image/x-icon" href="/favicon.ico" />
				<!-- Google fonts-->
        <link rel="preconnect" href="https://fonts.gstatic.com">
	      <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
        <script src="<?=auto_version("/js/jquery.3.5.1.min.js")?>"></script>
        <script src="<?=auto_version("/js/popper.min.js")?>" crossorigin="anonymous"></script>
        <script src="<?=auto_version("/js/bootstrap.min.js")?>" crossorigin="anonymous"></script>
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Core theme CSS (includes Bootstrap)-->
        <link rel="stylesheet" href="<?=auto_version("/css/bootstrap.min.css")?>" crossorigin="anonymous">
        <link href="<?=auto_version("/css/profile.css")?>" rel="stylesheet" />
    </head>
    <body>
      <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-<?=$colorback?> shadow-sm">
    		<div class="container">
    			<a class="navbar-brand" href="/profile">Turan Sсhedule</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
    			<div class="collapse navbar-collapse" id="navbarSupportedContent">
    		    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
    		      <li class="nav-item<?if(!strcmp($_SERVER['SCRIPT_NAME'],"/profile.php") && empty($_GET['go']) && empty($_GET['id'])) echo(" active")?>">
    		        <a class="nav-link" href="/profile">
    							Расписание
                </a>
    		      </li>
    		      <li class="nav-item<?if(!strcmp($_SERVER['SCRIPT_NAME'],"/profile.php") && !empty($_GET['go'])) if($_GET['go']=='calls') echo(" active")?>">
    		        <a class="nav-link" href="/profile?go=calls">
    							Звонки
    						</a>
    		      </li>
              <?if(!$self['is-admin']):?>
    		      <li class="nav-item<?if(!strcmp($_SERVER['SCRIPT_NAME'],"/profile.php") && !empty($_GET['go'])) if($_GET['go']=='calc') echo(" active")?>">
    		        <a class="nav-link" href="/profile?go=calc">
    							Калькулятор
    						</a>
    		      </li>
              <?endif?>
              <?if($headman || $self['is-admin']):?>
    					<li class="nav-item<?if(!strcmp($_SERVER['SCRIPT_NAME'],"/edit.php")) echo(" active")?>">
    		        <a class="nav-link" href="/edit">
    							Редактор
    						</a>
    		      </li>
              <?endif?>
              <?if($self['is-admin']):?>
              <?if(count($groups) > 0):?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Группа
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <?foreach ($groups as $key => $group):?>
                  <a class="group-select dropdown-item<?if($group['id']==$_SESSION['gid']) echo " active";?>" href="#" data-gid="<?=$group['id']?>"><?=$group['name']?></a>
                  <?endforeach?>
                </div>
              </li>
              <?else:?>
              <span class="navbar-text">
                Добавьте группы в редакторе
              </span>
              <?endif?>
              <?endif?>
    		    </ul>
    				<ul class="navbar-nav">
              <li class="nav-item text-nowrap <?if(!strcmp($_SERVER['SCRIPT_NAME'],"/profile.php") && !empty($_GET['go'])) {if($_GET['go'] == 'settings')echo " active";}?>" data-toggle="tooltip" data-placement="top" title="Настройки">
    		        <a class="nav-link" href="/profile?go=settings">
    							<i class="fas fa-cog d-none d-lg-inline-block"></i><span class="d-lg-none text-nowrap">Настройки</span>
    						</a>
    		      </li>
    					<li class="nav-item" data-toggle="tooltip" data-placement="top" title="Выход">
    						<a class="nav-link" href="/profile?go=sign-out"><i class="fas fa-sign-out-alt d-none d-lg-inline-block"></i><span class="d-lg-none text-nowrap">Выход</span></a>
    					</li>
    				</ul>
    		  </div>
    		</div>
    	</nav>
      <div class="content mt-3">
        <div class="container">
          <?if(!$self['is-admin']):?>
            <div class="row">
                <div class="col-sm-12">
                    <!-- meta -->
                    <div class="profile-user-box shadow-sm card-box bg-<?=$colorback?>">
                        <div class="row">
                            <div class="col-sm-12">
                              <div class="row">
                                <div class="col d-flex justify-content-center justify-content-lg-start">
                                  <div class="avatar avatar-border avatar-xl rounded-circle">
                                      <span class="avatar-text avatar-text-info rounded-circle">
                                        <span class="initial-wrap">
                                          <span><?=get_initials($self['name'])?></span>
                                        </span>
                                      </span>
                                  </div>
                                </div>
                              </div>
                                <div class="media-body text-white">
                                  <div class="row">
                                    <div class="col-lg d-flex justify-content-center justify-content-lg-start">
                                      <h4 class="my-1 font-18"><?=$self['name']?></h4>
                                    </div><?php $online = get_entry_time($self['last-session'], true);?>
                                    <div class="col-lg align-self-center justify-content-center d-flex justify-content-lg-end">
                                      <p class="font-13 text-light mb-0"><?php if($online != 'online' && !is_null($self['gender'])) echo ($self['gender']) ? "был " : "была " ;?><?= ($online != 'online') ? (!is_null($online)) ? 'в сети ' : '' : '<span class="online"></span> ' ;?><?=get_entry_time($self['last-session'], true)?></p>
                                    </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-lg d-flex justify-content-center justify-content-lg-start">
                                        <p class="my-2 text-break text-center text-lg-left">
                                          <span <?php if($self['self']):?>role="button" id="editStatus" <?php endif?>class="text-light text-decoration-none"><?php if(is_null($self['status']) && $self['self']):?>изменить статус<?php else:?><?=$self['status']?><?php endif?></span>
                                          <?php if($self['self']):?>
                                          <form id="statusForm" class="input-group input-group-sm my-2 collapse">
                                            <div class="input-group-prepend">
                                              <button class="btn btn-danger" type="button" id="btnClearStatus"><i class="fas fa-trash-alt"></i></button>
                                            </div>
                                            <input id="statusInput" type="text" maxlength="140" class="form-control" placeholder="Максимум 140 символов" aria-label="Максимум 140 символов" aria-describedby="btnStatus">
                                            <div class="input-group-append">
                                              <button class="btn btn-success" type="submit" id="btnStatus"><i class="fas fa-check"></i></button>
                                            </div>
                                          </form>
                                          <?php endif?>
                                        </p>
                                      </div>
                                  </div>
                                  <?php if(is_null($self['group-id']) && !$self['is-admin']):?>
                                  <div class="row">
                                      <div class="col-lg d-flex justify-content-center justify-content-lg-start">
                                        <p class="text-light mb-0">На данный момент у тебя нет группы.</p>
                                      </div>
                                  </div>
                                  <?php endif?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ meta -->
                </div>
            </div>
            <?php endif?>
            <!-- end row -->

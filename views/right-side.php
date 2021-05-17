<div class="col-lg-4">
  <?php if(isset($my_group)):?>
  <div class="card-box ribbon-box shadow-sm">
      <div class="ribbon rounded-lg bg-<?=$colorback?>">Куратор</div>
      <div class="clearfix"></div>
      <div class="panel-body info">
        <?if(!is_null($my_group['teacher'])):?>
        <span>
          <i class="fas fa-user-tie"></i>
          <?=$my_group['teacher']['name']?>
        </span>
        <span>
          <i class="fas fa-phone"></i>
          <a class="text-<?=$colorback?> text-decoration-none" href="tel:<?=$my_group['teacher']['phone']?>"><?=$my_group['teacher']['phone']?></a>
        </span>
        <span><i class="fas fa-at"></i>
          <a class="text-<?=$colorback?> text-decoration-none" href="mailto:<?=$my_group['teacher']['email']?>"><?=$my_group['teacher']['email']?></a>
        </span>
        <?else:?>
        <small class="form-text text-muted text-center lead">
          У твоей группы нет куратора
        </small>
        <?endif?>
      </div>
  </div>
  <?endif?>
  <?php if(!is_null($my_group['headman']) && !$headman):?>
  <div class="card-box ribbon-box shadow-sm">
      <div class="ribbon rounded-lg bg-<?=$colorback?>">
        Модератор
        <?
          if(!is_null($my_group['headman']['last-session'])){
            $headman_online = get_entry_time($my_group['headman']['last-session'],true);
            if($teacher_online != 'online') echo 'был в сети ';
            echo $headman_online;
          }
        ?>
      </div>
      <div class="clearfix"></div>
      <div class="panel-body info">
        <span>
          <i class="fas fa-user-graduate"></i>
          <?=$my_group['headman']['name']?>
        </span>
        <span>
          <i class="fas fa-phone"></i>
          <?if(!is_null($my_group['headman']['phone'])):?>
          <a class="text-<?=$colorback?> text-decoration-none" href="tel:<?=$my_group['headman']['phone']?>"><?=$my_group['headman']['phone']?></a>
          <?else:?>Номер телефона не указан<?endif?>
        </span>
        <span><i class="fas fa-at"></i>
          <?if(!is_null($my_group['headman']['email'])):?>
          <a class="text-<?=$colorback?> text-decoration-none" href="mailto:<?=$my_group['headman']['email']?>"><?=$my_group['headman']['email']?></a>
          <?else:?>Адрес почты не указан<?endif?>
        </span>
      </div>
  </div>
  <?endif?>
    <!-- Personal-Information -->
    <div class="card-box ribbon-box shadow-sm">
      <div class="ribbon rounded-lg bg-<?=$colorback?>">Персональные данные</div>
      <div class="clearfix"></div>
        <div class="panel-body info">
            <span><i class="fas fa-birthday-cake"></i>
                <?php
                if(!is_null($self['date-of-birth'])){
                  echo get_forced_time($self['date-of-birth'],true,true);
                }else{
                  echo "Дата рождения не указана";
                }?>
            </span>
            <?php if($self['self']):?>
            <span><i class="fas fa-id-card-alt"></i>
                <?=$self['login']?>
            </span>
            <span><i class="fas fa-key"></i>
              <span id="togglePassword" data-password="<?=$self['password']?>" data-state="hided" role="button" data-toggle="tooltip" data-placement="top" title="Показать пароль">
              <?php
                for($i=0; $i < mb_strlen($self['password']); $i++){
                  echo "•";
                }
              ?>
              </span>
            </span>
            <?php endif?>
            <span>
              <i class="fas fa-phone"></i>
              <?php if(!is_null($self['phone'])):?>
              <?if(!$self['settings']['hide-phone'] || $self['self']):?>
              <a class="text-<?=$colorback?> text-decoration-none" href="tel:<?=$self['phone']?>"><?=$self['phone']?></a>
              <?php else:?>Номер телефона скрыт<?php endif?><?php else:?>Номер телефона не указан<?php endif?>
            </span>
            <span><i class="fas fa-at"></i>
              <?php if(!is_null($self['email'])):?>
              <?if(!$self['settings']['hide-email'] || $self['self']):?>
              <a class="text-<?=$colorback?> text-decoration-none" href="tel:+<?=$self['email']?>"><?=$self['email']?></a>
              <?php else:?>
                Адрес почты скрыт
              <?php endif?>
              <?php else:?>
                Адрес почты не указан
              <?php endif?>
            </span>

        </div>
    </div>
    <!-- My Group -->
    <?php if(!is_null($self['group-id'])):?>
    <div class="card-box ribbon-box shadow-sm">
        <div class="ribbon rounded-lg bg-<?=$colorback?>">Моя группа<?if(!is_null($my_group['name'])):?> - <?=$my_group['name']?><?endif?></div>
        <div class="clearfix"></div>
        <div class="inbox-widget">
          <? if (count($my_group['students']) != 0):?>
          <?php foreach ($my_group['students'] as $key => $student) :?>
          <a class="text-<?=$colorback?> text-decoration-none" href="/profile?id=<?=$student['id']?>">
            <div class="inbox-item">
              <div class="inbox-item-img avatar avatar-md rounded-circle">
                <span class="avatar-text avatar-text-info rounded-circle">
                  <span class="initial-wrap">
                    <span><?=get_initials($student['name'])?></span>
                  </span>
                </span>
              </div>
              <p class="inbox-item-author">
                <?=$student['name']?>
              </p>
              <?php $sonline = get_entry_time($student['last-session'], true);?>
              <p class="inbox-item-date">
                  <?php if($sonline != 'online' && !is_null($student['gender'])) echo ($student['gender']) ? "заходил " : "заходила " ;?><?=get_entry_time($student['last-session'], true)?>
              </p>
              <?php if(!is_null($student['status'])):?><p class="inbox-item-text text-truncate"><?=$student['status']?></p><?php endif?>
            </div>
          </a>
          <?php if($student != end($my_group['students'])) echo '<hr class="m-0">';?>
          <?php endforeach?>
          <? else:?>
          <small class="form-text text-muted text-center lead">
            В группе пока никого
          </small>
          <?endif?>
        </div>
    </div>
  <?php endif?>
</div>

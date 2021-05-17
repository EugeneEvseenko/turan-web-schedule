              <div class="row">
                <div class="col-lg-8">
                  <?foreach ($schedule as $keyday => $day):?>
                  <?if(count($day) > 0 || $self['is-admin'] || $headman):?>
                  <div id="day<?=$keyday?>" class="card-box shadow-sm text-center text-xl-left p-0 py-2<?if(date("N") == $keyday) {echo " bg-info";}?>">
                    <div class="row px-3">
                      <div class="col-10 p-0 d-flex justify-content-start">
                        <h4 class="header-title m-0 py-2 px-3 text-<?=(date("N") == $keyday) ?'white':'info'?>"><?=convert_day($keyday)?></h4>
                      </div>
                      <?if($self['is-admin'] || $headman):?>
                      <div class="col-2 align-self-center d-flex justify-content-end">
                        <button type="button" aria-label="Изменить" <?if($countlessons > 0):?>data-toggle="modal" data-target="#editScheduleModal"<?else:?>data-toggle="tooltip" data-placement="left" title="Прежде чем редактировать расписание нужно добавить актуальные уроки"<?endif?> data-gid="<?=$self['group-id']?>" data-day="<?=$keyday?>" class="<?if($countlessons == 0) echo "disabled ";?>button-edit-day ml-2 btn btn-sm <?=(date("N") == $keyday) ?'btn-light text-info':'btn-info text-white'?>"><i class="fas fa-cog"></i></button>
                      </div>
                      <?endif?>
                    </div>
                    <?php if(count($day) > 0) echo "<hr>";?>
                    <div class="accordion lesson-item <?=(date("N") == $keyday) ?'active':'inactive'?>" id="accordionDay<?=$keyday?>">
                      <?foreach ($day as $keylesson => $lesson):?>
                      <div class="lesson<?if($lesson['active']) { echo " active" ;}?>" role="button" data-toggle="collapse" data-target="#collapseLesson<?=$keyday.$keylesson?>" aria-controls="collapseLesson<?=$keyday.$keylesson?>" aria-expanded="false" data-parent="#accordionDay<?=$keyday?>">
                        <div class="row py-2 px-4">
                          <div class="col-md-11 d-flex justify-content-center justify-content-md-start">
                            <strong class="">
                              <?=$lesson['num-lesson']?>. <?=$lesson['name']?>
                            </strong>
                          </div>
                          <div class="col-md-1 align-self-center justify-content-center d-flex justify-content-md-end mt-2 m-md-0">
                            <span class="badge badge-pill shadow-sm badge-<?=(date("N") == $keyday) ?'light text-info':'info'?>">
                              <?=$lesson['num-room']?> ауд.
                            </span>
                          </div>
                        </div>
                        <div class="row pb-2 px-4">
                          <div class="col-md align-self-center justify-content-center d-flex justify-content-md-start">
                          <p class="font-13 mb-0">
                            С <?=date("G:i", strtotime($calls[$lesson['num-lesson'] - 1]['start']))?> до <?=date("G:i", strtotime($calls[$lesson['num-lesson'] - 1]['end']))?> пару ведёт <?=$lesson['teacher']['name']?>.
                            <?if($lesson['active']) {echo "До конца ".calc_diff($calls[$lesson['num-lesson'] - 1]['end']).".";
                            }?>
                          </p>
                          </div>
                        </div>
                        <div class="row collapse pb-2 px-4<?=(date("N") != $keyday) ? " text-muted" : " text-white-50" ?>" id="collapseLesson<?=$keyday.$keylesson?>" data-parent="#accordionDay<?=$keyday?>">
                          <div class="col-md">
                            <p class="font-13 mb-0">
                              <i class="fas fa-bell"></i>
                              <?php if($lesson != end($day)):?>Перемена <?=$calls[$lesson['num-lesson'] - 1]['pause-min']?> минут.<?else:?>Конец занятий.<?endif?>
                            </p>
                          </div>
                          <div class="col-md mt-2 mt-md-0">
                            <p class="font-13 mb-0">
                              <i class="fas fa-phone"></i>
                              <a class="text-decoration-none<?=(date("N") != $keyday) ? " text-muted" : " text-white-50" ?>" href="tel:<?=$lesson['teacher']['phone']?>"><?=$lesson['teacher']['phone']?></a>
                            </p>
                          </div>
                          <div class="col-md mt-2 mt-md-0">
                            <p class="font-13 mb-0">
                              <i class="fas fa-at"></i>
                              <a class="text-decoration-none<?=(date("N") != $keyday) ? " text-muted" : " text-white-50" ?>" href="mailto:<?=$lesson['teacher']['email']?>"><?=$lesson['teacher']['email']?></a>
                            </p>
                          </div>
                        </div>
                      </div>
                      <?php if($lesson != end($day)) echo "<hr>";?>
                      <?endforeach?>
                    </div>
                  </div>
                  <?endif?>
                  <?endforeach?>
                  <?
                  $empty = true;
                  foreach ($schedule as $key => $value) {
                    if(count($schedule[$key]) > 0) $empty = false;
                  }
                  if($empty):
                  ?>
                  <div class="card-box shadow-sm text-center text-xl-left p-0 pt-2">
                    <p class="lead mt-3 text-muted text-center pb-4" id="history-nf">
                      Активного расписания нет.
                    </p>
                  </div>
                  <?endif?>
                </div>
                <?php include ('right-side.php');?>
              </div>

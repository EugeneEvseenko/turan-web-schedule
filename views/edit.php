
              <div class="row">
                <div class="col-lg">
                    <div class="card-box shadow-sm text-center text-xl-left p-0 py-2">
                      <div class="row px-3">
                        <div class="col-10 p-0 d-flex justify-content-start">
                          <h4 class="header-title m-0 py-2 px-3 text-info">Преподаватели</h4>
                        </div>
                        <div class="col-2 align-self-center d-flex justify-content-end">
                          <button<? if($self['is-admin']){ if(count($groups) == 0){ echo " disabled";}}?> type="button" data-action="add" data-toggle="modal" data-target="#TeacherModal" class="ml-2 btn btn-sm btn-info text-white action-teacher"><i class="fas fa-plus"></i></button>
                        </div>
                      </div>
                      <hr class="mt-2">

                      <div id="listTeachers" class="lesson-item inactive">
                        <? if (count($data['teachers']) > 0) :?>
                        <?foreach ($data['teachers'] as $key => $teacher) :?>
                        <div class="teacher" id="teacher<?=$teacher['id']?>">
                          <div class="row pt-2 px-4">
                            <div class="col-md-10 d-flex align-items-center justify-content-center justify-content-md-start">
                              <strong><?=$teacher['name']?></strong>
                            </div>
                            <div class="col-md-2 align-self-center justify-content-center d-flex justify-content-md-end my-2 m-md-0">
                              <button type="button" data-toggle="modal" data-target="#TeacherModal" data-action="edit" data-tid="<?=$teacher['id']?>" data-gid="<?=$self['group-id']?>" class="action-teacher ml-2 btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></button>
                              <button type="button" data-toggle="modal" data-action="teacher" data-target="#removeModal" data-tid="<?=$teacher['id']?>" data-name="<?=$teacher['name']?>" class="remove-action ml-2 btn btn-sm btn-danger text-white"><i class="fas fa-trash"></i></button>
                            </div>
                          </div>
                          <div class="row pb-2 px-4 align-self-center justify-content-center d-flex justify-content-md-start">
                            <div class="col-md">
                              <p class="font-13 text-muted mb-0">
                                <i class="fas fa-phone"></i>
                                <a class="text-muted text-decoration-none" href="tel:<?=$teacher['phone']?>"><?=$teacher['phone']?></a>
                              </p>
                            </div>
                            <div class="col-md">
                              <p class="font-13 text-muted mb-0">
                                <i class="fas fa-at"></i>
                                <a class="text-muted text-decoration-none" href="mailto:<?=$teacher['email']?>"><?=$teacher['email']?></a>
                              </p>
                            </div>
                          </div>
                        </div>
                        <?php if($teacher != end($data['teachers'])) echo "<hr>";?>
                        <?endforeach?>
                        <?else:?>
                        <small class="teachers-not-found form-text text-muted text-center p-3 lead">
                          Преподавателей пока нет.
                          <?if($self['is-admin']){
                            if(count($groups) == 0){
                              echo "Прежде чем добавлять преподавателя, надо создать для него группу.";
                            }
                          }else {
                            echo "Давай уже добавим?";
                          }
                          ?>
                        </small>
                        <?endif?>
                      </div>
                    </div>
                </div>
                <div class="col-lg">
                  <div class="card-box shadow-sm text-center text-xl-left p-0 py-2">
                    <div class="row px-3">
                      <div class="col-10 p-0 d-flex justify-content-start">
                        <h4 class="header-title m-0 py-2 px-3 text-info">Занятия</h4>
                      </div>
                      <div class="col-2 align-self-center d-flex justify-content-end">
                        <button<? if (count($data['teachers']) == 0) echo " disabled";?> id="addLesson" type="button" data-toggle="modal" data-target="#LessonModal" data-action="add" data-gid="<?=$self['group-id']?>" class="ml-2 btn btn-sm btn-info text-white action-lesson"><i class="fas fa-plus"></i></button>
                      </div>
                    </div>
                    <hr class="mt-2">
                    <? if (count($data['lessons']) > 0) :?>
                    <div id="listLessons" class="lesson-item inactive">
                      <?foreach ($data['lessons'] as $key => $lesson) :?>
                      <div class="lesson" id="lesson<?=$lesson['id']?>">
                        <div class="row pt-2 px-4">
                          <div class="col-md-10 d-flex align-items-center justify-content-center justify-content-md-start">
                            <strong class="">
                              <?=$lesson['name']?>
                            </strong>
                          </div>
                          <div class="col-md-2 align-self-center justify-content-center d-flex justify-content-md-end my-2 m-md-0">
                              <button type="button" data-action="edit" data-toggle="modal" data-target="#LessonModal" data-action="edit" data-lid="<?=$lesson['id']?>" data-name="<?=$lesson['name']?>" data-tid="<?=$lesson['teacher']['id']?>" class="ml-2 btn btn-sm btn-info text-white action-lesson"><i class="fas fa-edit"></i></button>
                              <button type="button" data-action="lesson" data-toggle="modal" data-target="#removeModal" data-lid="<?=$lesson['id']?>" data-name="<?=$lesson['name']?>" class="ml-2 remove-action btn btn-sm btn-danger text-white"><i class="fas fa-trash"></i></button>
                          </div>
                        </div>
                        <div class="row pb-2 px-4">
                          <div class="col-md align-self-center justify-content-center d-flex justify-content-md-start">
                          <p class="text-muted font-13 mb-0">
                            Урок преподаёт <?=$lesson['teacher']['name']?>.
                          </p>
                          </div>
                        </div>
                        <?php if($lesson != end($data['lessons'])) echo "<hr>";?>
                      </div>
                      <?endforeach?>
                    </div>
                    <?else:?>
                    <small class="form-text text-muted text-center p-3 lead">
                      Актуальных уроков нет.<?=(count($data['teachers']) == 0) ? " Чтобы добавить урок, сначала добавь хотя бы одного преподавателя.":" Добавим?"?>
                    </small>
                    <?endif?>
                  </div>
                </div>
                <!-- end col -->
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="card-box shadow-sm text-center text-xl-left p-0 py-2">
                    <div class="row px-3">
                      <div class="col-10 p-0 d-flex justify-content-start">
                        <h4 class="header-title m-0 py-2 px-3 text-info">
                          <?if (count($students)>0){
                            echo "В группе ".count($students).num_to_word(count($students),array(' человек', ' человека', ' человек'));
                          }else {
                            echo "Группа";
                          }?>
                        </h4>
                      </div>
                      <div class="col-2 align-self-center d-flex justify-content-end">
                        <button<? if($self['is-admin']){ if(count($groups) == 0){ echo " disabled";}}?> id="addStudent" type="button" data-toggle="modal" data-target="#StudentModal" data-action="add" data-gid="<?=$self['group-id']?>" class="ml-2 btn btn-sm btn-info text-white action-student"><i class="fas fa-plus"></i></button>
                      </div>
                    </div>
                    <hr class="mt-2">
                    <? if (count($students) > 0) :?>
                    <div id="listStudents" class="student-item inactive">
                      <?foreach ($students as $key => $student) :?>
                      <div class="student" id="student<?=$student['id']?>">
                        <div class="row pt-2 px-4">
                          <div class="col-md-10 d-flex align-items-center justify-content-center justify-content-md-start">
                            <strong class="">
                              <?=$student['name']?>
                            </strong>
                          </div>
                          <?if($student['state'] == 'waiting-registration' || $self['is-admin']):?>
                          <div class="col-md-2 align-self-center justify-content-center d-flex justify-content-md-end my-2 m-md-0">
                              <?if($student['state'] == 'waiting-registration'):?>
                              <button type="button" data-action="edit" data-toggle="modal" data-target="#StudentModal" data-action="edit" data-sid="<?=$student['id']?>" data-name="<?=$student['name']?>" data-email="<?=$student['email']?>" class="ml-2 btn btn-sm btn-info text-white action-student"><i class="fas fa-edit"></i></button>
                              <?endif?>
                              <button type="button" data-action="student" data-toggle="modal" data-target="#removeModal" data-sid="<?=$student['id']?>" data-name="<?=$student['name']?>" class="ml-2 remove-action btn btn-sm btn-danger text-white"><i class="fas fa-trash"></i></button>
                          </div>
                          <?endif?>
                        </div>
                        <div class="row pb-2 px-4">
                          <div class="col-md align-self-center justify-content-center d-flex justify-content-md-start">
                          <p class="text-muted font-13 mb-0">
                            <? $sstate = get_student_state($student['state']); echo $sstate;?>
                            <?php
                            if ($student['state'] == 'active'){
                              $sonline = get_entry_time($student['last-session'], true);
                              if($sonline != 'online') {
                                if (!is_null($student['gender'])) {
                                  echo (($student['gender']) ? "Заходил " : "Заходила ").$sonline;
                                }else {
                                  echo "Последняя активность была ".$sonline;
                                }
                              }else {
                                echo "Сейчас в сети";
                              }
                              echo ".";
                            }?>
                          </p>
                          </div>
                        </div>
                        <?php if($student != end($students)) echo "<hr>";?>
                      </div>
                      <?endforeach?>
                    </div>
                    <?else:?>
                    <small class="form-text text-muted text-center p-3 lead">
                      <?if($self['is-admin']){
                        if(count($groups) == 0){
                          echo "Прежде чем добавлять студента, надо создать для него группу.";
                        }else {
                          echo "Надо-бы добавить студентов.";
                        }
                      }else {
                        echo "В группе кроме тебя, никого нет.";
                      }
                      ?>
                    </small>
                    <?endif?>
                  </div>
                </div>
                <?if($self['is-admin']):?>
                <div class="col-lg-6">
                  <div class="card-box shadow-sm text-center text-xl-left p-0 py-2">
                    <div class="row px-3">
                      <div class="col-10 p-0 d-flex justify-content-start">
                        <h4 class="header-title m-0 py-2 px-3 text-info">
                          <?if (count($groups)>0){
                            echo count($groups).num_to_word(count($groups),array(' активная группа', ' активных группы', ' активных групп'));
                          }else {
                            echo "Группы";
                          }?>
                        </h4>
                      </div>
                      <div class="col-2 align-self-center d-flex justify-content-end">
                        <button id="addGroup" type="button" data-toggle="modal" data-target="#GroupModal" data-action="add" class="ml-2 btn btn-sm btn-info text-white action-group"><i class="fas fa-plus"></i></button>
                      </div>
                    </div>
                    <hr class="mt-2">
                    <? if (count($groups) > 0) :?>
                    <div id="listGroup" class="lesson-item inactive">
                      <?foreach ($groups as $key => $group) :?>
                      <div class="group" id="group<?=$group['id']?>">
                        <div class="row pt-2 px-4">
                          <div class="col d-flex align-self-center justify-content-start">
                            <strong class="">
                              <?=$group['name']?>
                            </strong>
                          </div>
                          <div class="col-3 align-items-center d-flex justify-content-end my-2 m-md-0">
                              <button type="button" data-action="edit" data-toggle="modal" data-target="#GroupModal" data-gid="<?=$group['id']?>" data-name="<?=$group['name']?>" data-tid="<?=$group['teacher-id']?>" data-hid="<?=$group['headman']?>" class="ml-2 btn btn-sm btn-info text-white action-group"><i class="fas fa-edit"></i></button>
                              <button type="button" data-action="group" data-toggle="modal" data-target="#removeModal" data-gid="<?=$group['id']?>" data-name="<?=$group['name']?>" class="ml-2 remove-group btn btn-sm btn-danger text-white"><i class="fas fa-trash"></i></button>
                          </div>
                        </div>
                        <div class="row pb-2 px-4">
                          <div class="col-md align-self-center justify-content-center d-flex justify-content-md-start">
                          <p class="text-muted font-13 mb-0">
                            <?if($group['id']==$_SESSION['gid']) echo "<b>Текущая.</b> ";?>
                            <?
                              if(is_null($group['teacher-id']) && is_null($group['headman'])){
                                echo "Куратор и модератор не выбраны.";
                              }else if(!is_null($group['teacher-id']) && is_null($group['headman'])){
                                echo "Куратор <b>".get_data($link, 'Teachers', 'name', 'id', $group['teacher-id'])."</b>, а модератор ещё не выбран.";
                              }else if(is_null($group['teacher-id']) && !is_null($group['headman'])){
                                echo "Куратор не выбран, а модератор в группе <b>".get_data($link, 'Students', 'name', 'id', $group['headman'])."</b>.";
                              }else {
                                echo "Куратор <b>".get_data($link, 'Teachers', 'name', 'id', $group['teacher-id'])."</b>,
                                модератор <b>".get_data($link, 'Students', 'name', 'id', $group['headman'])."</b>.";
                              }
                            ?>
                          </p>
                          </div>
                        </div>
                        <?php if($group != end($groups)) echo "<hr>";?>
                      </div>
                      <?endforeach?>
                    </div>
                    <?else:?>
                    <small class="form-text text-muted text-center p-3 lead">
                      Активных групп нет, будем добавлять?
                    </small>
                    <?endif?>
                  </div>
                </div>
                <?endif?>
              </div>
              <div class="modal fade" id="StudentModal" tabindex="-1" role="dialog" aria-labelledby="modalStudentLabel" aria-hidden="true">
              	<div class="modal-dialog modal-dialog-centered" role="document">
              			<div class="modal-content">
              				<div class="modal-header">
              					<h5 class="modal-title" id="modalStudentLabel">Добавляем студента</h5>
              					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              						<span aria-hidden="true">&times;</span>
              					</button>
              				</div>
                      <div class="modal-body" id="studentBody">
                        <div class="form-group px-3">
                          <label for="inputSName">Имя</label>
                          <input type="text" class="form-control text-center" id="inputSName">
                          <div id="invalidSName" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group px-3">
              						<label for="inputSEmail">Почта</label>
              						<input type="email" class="form-control text-center" placeholder="studentemail@gmail.com" id="inputSEmail">
                          <div id="invalidSEmail" class="invalid-feedback"></div>
                          <small  class="form-text text-muted text-center">
                            Убедись что адрес корректный, на него будет отправлено приглашение на регистрацию
                          </small>
                          <small id="resendInfo" class="form-text text-warning text-center collapse">
                            Приглашение будет отправлено повторно
                          </small>
              					</div>
                      </div>
              				<div class="modal-footer">
              					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
              					<button id="saveStudentChanges" data-action="none" type="button" class="btn btn-info">Добавить</button>
              				</div>
              			</div>
              	</div>
              </div>
              <div class="modal fade" id="LessonModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
              	<div class="modal-dialog modal-dialog-centered" role="document">
              			<div class="modal-content">
              				<div class="modal-header">
              					<h5 class="modal-title" id="modalEditLabel">Редактируем урок</h5>
              					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              						<span aria-hidden="true">&times;</span>
              					</button>
              				</div>
              				<div class="modal-body">
                        <div class="form-group px-3">
                          <label for="inputLName">Название</label>
                          <input type="text" class="form-control text-center" id="inputLName">
                          <div id="invalidLName" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group px-3">
                          <label for="selectTeacher">Преподаватель</label>
                          <select class="custom-select" id="selectTeacher">
                            <option value="-1">Не выбрано</option>
                          </select>
                          <div id="invalidTeacher" class="invalid-feedback"></div>
                        </div>
              				</div>
              				<div class="modal-footer">
              					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
              					<button id="saveLessonChanges" data-action="none" data-lid="-1" type="button" class="btn btn-info">Сохранить</button>
              				</div>
              			</div>
              	</div>
              </div>
              <div class="modal fade" id="TeacherModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
              	<div class="modal-dialog modal-dialog-centered" role="document">
              			<div class="modal-content">
              				<div class="modal-header">
              					<h5 class="modal-title" id="modalTeacherLabel">Редактируем преподавателя</h5>
              					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              						<span aria-hidden="true">&times;</span>
              					</button>
              				</div>
                      <div class="modal-body" id="teacherBody">
                        <div class="form-group px-3">
                          <label for="inputTName">ФИО</label>
                          <input type="text" class="form-control text-center" id="inputTName">
                          <div id="invalidTName" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group px-3">
              						<label for="inputTPhone">Телефон</label>
              						<input type="tel" class="form-control text-center" placeholder="+7(777)777-77-77" id="inputTPhone">
                          <div id="invalidTPhone" class="invalid-feedback"></div>
              					</div>
                        <div class="form-group px-3">
              						<label for="inputTEmail">Почта</label>
              						<input type="email" class="form-control text-center" placeholder="teachermail@gmail.com" id="inputTEmail">
                          <div id="invalidTEmail" class="invalid-feedback"></div>
              					</div>
                        <div class="form-group px-3">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="isCurator">
                            <label class="custom-control-label" for="isCurator">Куратор</label>
                            <small  class="form-text text-muted">
                              Отметь если это куратор твоей группы.
                            </small>
                          </div>
                        </div>
                      </div>
              				<div class="modal-footer">
              					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
              					<button id="saveTeacherChanges" data-action="none" data-tid="-1" type="button" class="btn btn-info">Сохранить</button>
              				</div>
              			</div>
              	</div>
              </div>
              <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
              	<div class="modal-dialog modal-dialog-centered" role="document">
              			<div class="modal-content">
              				<div class="modal-header">
              					<h5 class="modal-title">Подтверждение удаления</h5>
              					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              						<span aria-hidden="true">&times;</span>
              					</button>
              				</div>
              				<div class="modal-body text-center" id="removeBody">

              				</div>
              				<div class="modal-footer">
              					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
              					<button id="removeSubmit" data-action="" data-lid="-1" data-tid="-1" type="button" class="btn btn-danger">Удалить</button>
              				</div>
              			</div>
              	</div>
              </div>
              <?if($self['is-admin']):?>
              <div class="modal fade" id="GroupModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
              	<div class="modal-dialog modal-dialog-centered" role="document">
              			<div class="modal-content">
              				<div class="modal-header">
              					<h5 class="modal-title" id="modalGroupLabel">Редактируем группу</h5>
              					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              						<span aria-hidden="true">&times;</span>
              					</button>
              				</div>
              				<div class="modal-body">
                        <div class="form-group px-3">
                          <label for="inputGName">Название</label>
                          <input type="text" class="form-control text-center" id="inputGName">
                          <div id="invalidGName" class="invalid-feedback"></div>
                        </div>
                        <div id="formGTeacher" class="form-group px-3">
                          <label for="selectGTeacher">Куратор</label>
                          <select class="custom-select" id="selectGTeacher">
                            <option value="-1">Не выбрано</option>
                          </select>
                          <div id="invalidGTeacher" class="invalid-feedback"></div>
                        </div>
                        <div id="formGHeadman" class="form-group px-3">
                          <label for="selectGHeadman">Модератор</label>
                          <select class="custom-select" id="selectGHeadman">
                            <option value="-1">Не выбрано</option>
                          </select>
                          <div id="invalidGHeadman" class="invalid-feedback"></div>
                        </div>
                        <small id="addInfo" class="form-text text-muted text-center"></small>
              				</div>
              				<div class="modal-footer">
              					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
              					<button id="saveGroupChanges" data-action="none" data-gid="-1" type="button" class="btn btn-info">Сохранить</button>
              				</div>
              			</div>
              	</div>
              </div>
              <?endif?>

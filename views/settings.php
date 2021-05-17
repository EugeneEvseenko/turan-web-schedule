<div class="row">
  <div class="col-lg-8">
      <div class="card-box shadow-sm text-center text-xl-left p-0 pt-2">
          <h4 class="header-title py-2 px-3 text-info">Настройки</h4><hr>
          <small class="form-text text-muted text-center px-3">
            Все настройки хранятся на сервере, и будут доступны с любого устройства.
          </small>
          <h4 class="mt-3 text-center">Личные данные</h4>
          <div class="form-row px-3">
  					<div class="form-group col-sm-5">
  						<label for="inputBirth">День рождения</label>
  						<input type="date" min="<?=date('Y-m-d', strtotime("-50 years"))?>" max="<?=date('Y-m-d', strtotime("-5 years"))?>" class="form-control text-center" id="inputBirth" value="<?=$self['date-of-birth']?>" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
              <span class="validity"></span><div id="invalidBirth" class="invalid-feedback"></div>
  					</div>
  					<div class="form-group col-sm-7">
  						<label for="inputPhone">Телефон</label>
  						<input type="tel" class="form-control text-center" placeholder="+7(777)777-77-77" id="inputPhone" value="<?=$self['phone']?>">
              <div id="invalidPhone" class="invalid-feedback"></div>
  					</div>
  				</div>
  				<div class="form-row px-3">
  					<div class="form-group col-sm-4">
  						<label for="inputGender">Пол</label>
  						<select class="custom-select" id="inputGender">
  						  <option value="-1"<?if(is_null($self['gender'])) echo "selected";?>>Не выбрано</option>
  						  <option value="0"<?if(!$self['gender'] && !is_null($self['gender'])) echo "selected";?>>Женский</option>
  						  <option value="1"<?if($self['gender']) echo "selected";?>>Мужской</option>
  						</select>
              <div id="invalidGender" class="invalid-feedback"></div>
  					</div>
  					<div class="form-group col-sm-8">
  						<label for="inputEmail">Почта</label>
  						<input type="email" class="form-control text-center" placeholder="youremail@gmail.com" id="inputEmail" value="<?=$self['email']?>">
              <div id="invalidEmail" class="invalid-feedback"></div>
  					</div>
  				</div>
          <div class="d-flex justify-content-end px-3">
            <small id="okText" class="form-text mr-3 mt-2 text-success collapse">
              Всё ок! Я сохранил.
            </small>
            <button id="btnSave" class="btn btn-<?=$colorback?>" type="submit" disabled>Сохранить</button>
          </div>

          <h4 class="mt-3 text-center">Приватность</h4>
          <div class="form-group px-3 pb-2 text-left">
            <div class="custom-control custom-switch my-4">
              <input type="checkbox" class="custom-control-input" id="phoneOption"<?if($self['settings']['hide-phone']) echo "checked";?>>
              <label class="custom-control-label" for="phoneOption">Скрывать мой номер телефона</label>
              <small id="phoneHelpBlock" class="form-text text-muted">
                Твои одногруппники не увидят твой номер телефона.
              </small>
            </div>
            <div class="custom-control custom-switch my-4">
              <input type="checkbox" class="custom-control-input" id="emailOption"<?if($self['settings']['hide-email']) echo "checked";?>>
              <label class="custom-control-label" for="emailOption">Скрывать мою почту</label>
              <small id="emailHelpBlock" class="form-text text-muted">
                Твои одногруппники не увидят твой почтовый адрес.
              </small>
            </div>
          </div>
      </div>
  </div>
  <?php include ('right-side.php');?>
</div>

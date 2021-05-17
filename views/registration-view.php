<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Регистрация | Turan Schedule</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
	<link href="<?php echo auto_version('/css/profile.min.css'); ?>" rel="stylesheet">
</head>
<style>
.container, .container-lg, .container-md, .container-sm, .container-xl {
    max-width: 720px !important;
}
</style>
<body>
  <div class="container mt-4 text-center text-sm-left">
    <div class="row">
      <div class="col">
        <div class="card-box shadow-lg">
					<div class="form-row">
						<div class="form-group col-sm">
							<label for="inputName">Имя и фамилия *</label>
							<input type="text" class="form-control text-center" id="inputName" value="<?=$data['name']?>">
							<small class="form-text text-muted">
								Перепроверьте это поле, укажите своё настоящее имя, можно без отчества, и можно на латинице. Не более 100 символов.
							</small>
							<div class="invalid-feedback" id="invalidName"></div>
						</div>

						<div class="form-group col-sm">
							<label for="inputEmail">Email *</label>
							<input type="email" class="form-control text-center" id="inputEmail" value="<?=$data['email']?>" placeholder="example@mail.com">
							<small class="form-text text-muted">
								Можно скрыть отображение почтового адреса в настройках.
							</small>
							<div class="invalid-feedback" id="invalidEmail"></div>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-sm">
							<label for="inputLogin">Логин *</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">@</span>
								</div>
								<input type="text" class="form-control text-center" id="inputLogin">
								<small class="form-text text-muted">
									Ваш логин должен содержать латинские буквы или цифры от 6 до 25 символов, и не содержать пробелов, специальных символов или эмоджи.
								</small>
								<div class="invalid-feedback" id="invalidLogin"></div>
							</div>
						</div>
						<div class="form-group col-sm">
							<label for="inputPhone">Мобильный телефон *</label>
							<input type="text" class="form-control text-center" id="inputPhone" placeholder="+7(700)000-00-00">
							<small class="form-text text-muted">
								Номер телефона можно скрыть в настройках кабинета.
							</small>
							<div class="invalid-feedback" id="invalidPhone"></div>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-sm">
							<label for="inputFPassword">Пароль *</label>
							<input type="password" class="form-control text-center" id="inputFPassword">
							<small class="form-text text-muted">
								Ваш пароль должен содержать от 6 до 20 символов, и не содержать пробелов, специальных символов или эмоджи.
							</small>
							<div class="invalid-feedback" id="invalidFPassword"></div>
						</div>
						<div class="form-group col-sm">
							<label for="inputSPassword">Пароль ещё раз *</label>
							<input type="password" class="form-control text-center" id="inputSPassword">
							<small class="form-text text-muted">
								Это поле должно совпадать с предыдущим.
							</small>
							<div class="invalid-feedback" id="invalidSPassword"></div>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-sm">
							<label for="inputGender">Пол</label>
							<select class="custom-select" id="inputGender">
								<option value="-1" selected>Не выбрано</option>
								<option value="0">Женский</option>
								<option value="1">Мужской</option>
							</select>
							<small class="form-text text-muted">
								Необходимо для склонения некоторых слов на сайте и для корректного отображения вашей информации. Необязательное поле.
							</small>
							<div id="invalidGender" class="invalid-feedback"></div>
						</div>
						<div class="form-group col-sm">
							<label for="inputBirth">День рождения</label>
							<input type="date" min="<?=date('Y-m-d', strtotime("-50 years"))?>" max="<?=date('Y-m-d', strtotime("-5 years"))?>" class="form-control text-center" id="inputBirth" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
							<small class="form-text text-muted">
								Укажите свой реальный возраст. Необязательное поле.
							</small>
							<div id="invalidBirth" class="invalid-feedback"></div>
						</div>
					</div>
					<div class="form-group">
						<div id="g-recaptcha"></div>
						<div class="form-text text-danger collapse" id="invalidCaptcha">
							Неверная каптча.
						</div>
				  </div>
					<div class="form-group">
						<div class="custom-control custom-checkbox form-check">
							<input class="custom-control-input" type="checkbox" id="gridCheck">
							<label class="custom-control-label" for="gridCheck">
							Я прочитал и принимаю условия пользования сайтом.
							</label>
							<div class="invalid-feedback">
								Вы сможете продолжить после подтверждения.
								</div>
						</div>
					</div>
					<button type="submit" class="btn btn-info" id="registrationBtn">Регистрация</button>
        </div>
      </div>
    </div>
  </div>
	<div class="modal fade" id="termsOfUseModal" tabindex="-1" role="dialog" aria-labelledby="termsOfUseTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="termsOfUseTitle">Условия пользования сайтом</h5>
		  </div>
		  <div class="modal-body">
			<?php include ("views/conditions.php")?>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Отклонить</button>
			<button type="button" id="acceptTerms" class="btn btn-info">Принять</button>
		  </div>
		</div>
	  </div>
  </div>
	<div class="modal fade" id="finishRegistration" tabindex="-1" role="dialog" aria-labelledby="finishRegistrationTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="finishRegistrationTitle">Отлично!</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
				Регистрация прошла успешно. Можно приступать к авторизации.
		  </div>
		  <div class="modal-footer">
			<a href="/" class="btn btn-info">Авторизация</a>
		  </div>
		</div>
	  </div>
	 </div>
	<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
	<script crossorigin="anonymous" src="<?php echo auto_version('/js/reg.min.js'); ?>"></script>
	<script src="<?=auto_version("/js/jquery.maskedinput.js")?>"></script>
</body>
</html>

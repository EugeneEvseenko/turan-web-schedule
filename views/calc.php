<div class="row">
  <div class="col-lg-8">
    <div class="card-box shadow-sm text-center text-xl-left p-0 pt-2">
      <h4 class="header-title py-2 px-3 text-info">Калькулятор итоговой оценки</h4><hr>
      <form id="calculationForm" class="p-4" novalidate>
		  <div class="form-row">
			<div class="form-group col-md-4">
			  <label for="inputRK1">Рубежный контроль 1</label>
			  <input type="tel" class="form-control" id="inputRK1" required>
			  <small class="form-text text-muted">
				  Оценка за первую рубежку. Только цифры.
			  </small>
			  <div class="invalid-feedback" id="rk1Invalid">

			  </div>
			</div>
			<div class="form-group col-md-4">
			  <label for="inputRK2">Рубежный контроль 2</label>
			  <input type="tel" class="form-control"id="inputRK2" required>
			  <small class="form-text text-muted">
				  Оценка за вторую рубежку. Только цифры.
			  </small>
			  <div class="invalid-feedback" id="rk2Invalid">

			  </div>
			</div>
			<div class="form-group col-md-4">
			  <label for="inputWishEstimation">Желаемая оценка</label>
        <select id="inputWishEstimation" class="custom-select" aria-describedby="groupTypeHelpBlock" required>
  				<option value=0>Желаемая оценка</option>
  				<option value=1>90 - 100 (Отлично)</option>
  				<option value=2>70 - 89 (Хорошо)</option>
  				<option value=3>50 - 69 (Удовлетворительно)</option>
			  </select>
        <small class="form-text text-muted">
				  Можешь не выбирать если хочешь узнать какие итоговые оценки ты можешь получить.
			  </small>
        <div class="invalid-feedback" id="WEInvalid">

			  </div>
			</div>
		  </div>
		  <button type="submit" class="btn btn-info" id="calculationButton">Рассчитать</button>
		</form>
    <div id="variants" class="text-center collapse">

    </div>
    </div>

  </div>
  <?php include ('right-side.php');?>
</div>

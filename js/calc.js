$('form#calculationForm button.btn').click(function () {
		$('#variants').fadeOut(150);
		$('#inputRK1').removeClass("is-valid").removeClass("is-invalid");
		$('#inputRK2').removeClass("is-valid").removeClass("is-invalid");
		$('#inputWishEstimation').removeClass("is-valid").removeClass("is-invalid");
		$('#inputRK1').prop('disabled', true);
		$('#inputRK2').prop('disabled', true);
		$('#inputWishEstimation').prop('disabled', true);
		$('#calculationButton').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Обработка <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>');
		var errors = false;
		var rk1 = $('#calculationForm #inputRK1').val();
		var rk2 = $('#calculationForm #inputRK2').val();
		var wish = parseInt($('#calculationForm #inputWishEstimation').val());
		$('#inputRK1').addClass("is-valid");
		$('#inputRK2').addClass("is-valid");
		$('#inputWishEstimation').addClass("is-valid");
		if (rk1 == ''){
			$('#inputRK1').addClass("is-invalid");
			$('#rk1Invalid').html('Поле не может быть пустым.');
			errors = true;
		}else if (!$.isNumeric(parseFloat(rk1))) {
			$('#inputRK1').addClass("is-invalid");
			$('#rk1Invalid').html('Это должно быть числом.');
			errors = true;
		}else if (parseFloat(rk1) < 0 || parseFloat(rk1) > 100) {
			$('#inputRK1').addClass("is-invalid");
			$('#rk1Invalid').html('Оценки указываются в диапазоне от 0 до 100.');
			errors = true;
		}else{
			rk1 = parseFloat(rk1);
		}
		if (rk2 == ''){
			$('#inputRK2').addClass("is-invalid");
			$('#rk2Invalid').html('Поле не может быть пустым.');
			errors = true;
		}else if (!$.isNumeric(parseFloat(rk2))) {
			$('#inputRK2').addClass("is-invalid");
			$('#rk2Invalid').html('Это должно быть числом.');
			errors = true;
		}else if (parseFloat(rk2) < 0 || parseFloat(rk2) > 100) {
			$('#inputRK2').addClass("is-invalid");
			$('#rk2Invalid').html('Оценки указываются в диапазоне от 0 до 100.');
			errors = true;
		}else{
			rk2 = parseFloat(rk2);
		}
		if (wish < 0 || wish > 3){
			wish = 0;
			$('#inputWishEstimation').addClass("is-invalid");
			$('#WEInvalid').html('Не надо ломать мне сайт.');
		}
		if (!errors) {
			var out = '';
			if (wish == 0){
				for (var i = 100.0; i >= 50.0; i-=4.0) {
					var calc = ((rk1 + rk2) / 2 * 0.6) + i * 0.4;
					if (calc >=90 && calc <= 100){
						out+='<hr><p class="calc-item good">Если наберешь <b>' + i.toFixed(0) + '</b>, то итоговая будет <b>' + calc.toFixed(2) +'</b>.</p>';
					}
					if (calc >=70 && calc <= 89){
						out+='<hr><p class="calc-item normal">Если наберешь <b>' + i.toFixed(0) + '</b>, то итоговая будет <b>' + calc.toFixed(2) +'</b>.</p>';
					}
					if (calc >=50 && calc <= 69){
						out+='<hr><p class="calc-item bad">Если наберешь <b>' + i.toFixed(0) + '</b>, то итоговая будет <b>' + calc.toFixed(2) +'</b>.</p>';
					}
				}
				if (out == '') {
					out = '<hr><p class="calc-item bad">Прости, ты уже никакую оценку не наберешь.</p>'
				}
			}else{
				from = 0;
				to = 0;
				switch (wish) {
					case 1:{
						from = 100;
						to = 90;
					}break;
					case 2:{
						from = 89;
						to = 70;
					}break;
					case 3:{
						from = 69;
						to = 50;
					}break;
				}
				for (var i = 100.0; i >= 50.0; i-=4) {
					var calc = ((rk1 + rk2) / 2 * 0.6) + i * 0.4;
					if (calc >= to && calc <= from){
						out+='<hr><p class="calc-item info">Если наберешь <b>' + i.toFixed(2) + '</b>, то итоговая будет <b>' + calc.toFixed(2) +'</b>.</p>';
					}
				}
				if (out == '') {
					out = '<hr><p class="calc-item bad">Прости, такую оценку ты уже не наберешь.</p>'
				}
			}
			$('#variants').html(out);
			$('#variants').fadeIn(150);
			/*$([document.documentElement, document.body]).animate({
        scrollTop: $("#variants").offset().top-60
    	}, 500);*/
		}
		$('#inputRK1').prop('disabled', false);
		$('#inputRK2').prop('disabled', false);
		$('#inputWishEstimation').prop('disabled', false);
		$('#calculationButton').html('Рассчитать');
		return false;
  });

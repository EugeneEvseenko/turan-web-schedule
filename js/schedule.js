function showError(status, reload = false, title=false, body = false) {
  if (title) {
    $('#errorModalLabel').html(title);
  }
  switch (status) {
    case 401:var body = "Сессия окончена. Авторизуйся заново.<br>Сейчас я перезагружу страницу...";break;
    default:var body = (body)?body:"Что-то пошло не так!";break;
  }
  $('#errorBody').html($('#errorBody').html() + body);
  $('#errorModal').modal({
    keyboard: false
  });
  $('#errorModal').modal('show');
  if (reload) {
    setTimeout(function () {
      window.location.href = "/profile";
    }, 5000);
  }
}
function getEnding(number, titles) {
    cases = [2, 0, 1, 1, 1, 2];
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
}
function $_GET(key) {
  var p = window.location.search;
  p = p.match(new RegExp(key + '=([^&=]+)'));
  return p ? p[1] : false;
}
var spinnerSmall = '<span class="spinner-border text-info" role="status"></span>';
var spinnerSmallWhite = '<div class="spinner-border spinner-border-sm text-white" role="status"></div>';
jQuery(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();

  $('#editStatus').click(function () {
    if ($('#editStatus').html() != "" && $('#editStatus').html() != "изменить статус") {
      $("#statusInput").val($('#editStatus').html());
    }
    $('#editStatus').addClass('collapse');
    $('#statusForm').removeClass('collapse');
    $("#statusInput").focus();
    return false;
  });
  $('#btnClearStatus').click(function () {
    $("#statusInput").val('');
    $("#statusInput").focus();
    return false;
  });
  /*$("div[role='button']").click(function () {
    if (!$_GET('id')) {
      $('#funBalance').html(spinnerSmall);
      $('#achievementsBalance').html(spinnerSmall);
      $('#likeBalance').html(spinnerSmall);
      $.post( "/proc/profile.php", { action: "update"} )
      .done(function( data ) {
        var response = JSON.parse(data);
        if (!response.is_error) {
          $('#funBalance').html(response.balance);
          $('#achievementsBalance').html(response.achievements);
          $('#likeBalance').html(response.likes);
          $counters.each(function (ignore, counter) {
              counterUp(counter, {
                  duration: 200,
                  delay: 5
              });
          });
        }else {
          showError(response.error_text, true);
        }
      });
    }

    return false;
  });*/
  $("#putLike").click(function () {
    $("#putLike span").removeClass('collapse');
    $("#putLike svg").addClass('collapse');
    $.post( "/proc/profile.php", { action: "like", id: $_GET('id')} )
    .done(function( data ) {
      var response = JSON.parse(data);
      if (!response.is_error) {
        $('#putLike').tooltip('dispose');
        if($('#likeBalance').html() < response.likes){
          $("#putLike").attr('title', 'Ну не убирать же теперь!');
          $("#putLike svg[role='img']").removeClass('text-muted');
          $("#putLike svg[role='img']").addClass('text-danger');
        }else {
          $("#putLike").attr('title', 'Можно влепить лойс!');
          $("#putLike svg[role='img']").removeClass('text-danger');
          $("#putLike svg[role='img']").addClass('text-muted');
        }
        $('#putLike').tooltip('update');
        $('#putLike').tooltip('show');
        $('#likeBalance').html(response.likes);
      }else {
        showError(response.error_text, true);
      }
      $("#putLike svg").removeClass('collapse');
      $("#putLike span").addClass('collapse');
    });
    return false;
  });
  $('#btnStatus').click(function () {
    if ($('#statusInput').val() != $('#editStatus').html()) {
      $('#btnStatus').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
      var data = 'action=status&data=' + $('#statusInput').val();
      $.ajax({
        type: "POST",
        url: "/proc/profile.php",
        data: data,
        success: function (msg) {
          var response = JSON.parse(msg);
          if (!response.is_error) {
            if (response.response) {
              $("#editStatus").html($('#statusInput').val());
            }else {
              $("#editStatus").html('изменить статус');
            }
            $('#editStatus').removeClass('collapse');
            $('#statusForm').addClass('collapse');
            $('#btnStatus').html('<i class="fas fa-check"></i>');
          }else {
            showError(response.error_text, true);
          }
        }
      });
    }else {
      $('#editStatus').removeClass('collapse');
      $('#statusForm').addClass('collapse');
    }
    return false;
  });
  $('#togglePassword').click(function () {
    $('#togglePassword').fadeOut( 'fast' );
    $('#togglePassword').tooltip('dispose');
    setTimeout(function () {
      if($('#togglePassword').data('state') === "showed"){
        $("#togglePassword").attr('title', 'Показать пароль');
        var count = $("#togglePassword").html().length;
        $("#togglePassword").html('');
        for (var i = 0; i < count; i++) {
          var temp = $("#togglePassword").html();
          $("#togglePassword").html(temp + "•");
        }
        $('#togglePassword').html();
        $('#togglePassword').data('state', 'hided');
      }else {
        $("#togglePassword").attr('title', 'Скрыть пароль');

        $('#togglePassword').html($('#togglePassword').data('password'));
        $('#togglePassword').data('state', 'showed');
      }
      $('#togglePassword').fadeIn( 'slow' );
      $('#togglePassword').tooltip('update');
      $('#togglePassword').tooltip('show');
    }, 200);


    return false;
  });
});

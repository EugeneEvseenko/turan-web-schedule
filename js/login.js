function $_GET(key) {
  var p = window.location.search;
  p = p.match(new RegExp(key + '=([^&=]+)'));
  return p ? p[1] : false;
}
$('#btnAuth').click(function () {
  $('#loginInput').removeClass("is-valid").removeClass("is-invalid");
  $('#passwordInput').removeClass("is-valid").removeClass("is-invalid");
  $('#loginInput').prop('disabled', true);
  $('#passwordInput').prop('disabled', true);
  $('#btnAuth').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
  var loginField = $('#loginInput').val();
  var passwordField = $('#passwordInput').val();
  $.post("/proc/auth.php", { inputLogin: loginField, inputPassword: passwordField},
   function(response) {
     $('#passwordInput').addClass("is-valid");
     $('#loginInput').addClass("is-valid");
     window.location.href = ($_GET('redirect') ? $_GET('redirect') :'/');
   }, "json")
   .fail(function(response) {
     response.responseJSON.errors.forEach(error => {
       switch (error.error_code) {
         case 3:{
           $('#invalidLogin').html(error.text);
           $('#invalidPassword').html(error.text);
           $('#loginInput').removeClass("is-valid");
           $('#loginInput').addClass("is-invalid");
           $('#passwordInput').removeClass("is-valid");
           $('#passwordInput').addClass("is-invalid");
           setTimeout(function () {
             $("#loginInput").focus();
           }, 500);
         }break;
         case 2:{
           $('#invalidPassword').html(error.text);
           $('#passwordInput').removeClass("is-valid");
           $('#passwordInput').addClass("is-invalid");
           setTimeout(function () {
             $("#passwordInput").focus();
           }, 500);
         }break;
         case 1:{
           $("#loginInput").prop('value', $("#loginInput").prop('value').replaceAll(" ",""));
           $('#invalidLogin').html(error.text);
           $('#loginInput').removeClass("is-valid");
           $('#loginInput').addClass("is-invalid");
           setTimeout(function () {
             $("#loginInput").focus();
           }, 500);
         }break;
       }
     });
     $("#passwordInput").val('');
   }).always(function() {
     $('#loginInput').prop('disabled', false);
     $('#passwordInput').prop('disabled', false);
     $('#btnAuth').html('Вход');
    });
    return false;
});

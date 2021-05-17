jQuery(document).ready(function () {
  var spinner = '<span class="spinner-border spinner-border-sm align-baseline" role="status"></span>';
  let datas = {
    dob: $("#inputBirth").val(),
    phone: $("#inputPhone").val(),
    gender: $("#inputGender").val(),
    email: $("#inputEmail").val()
  };
  $("#inputPhone").mask("+7(999)999-99-99");
  $( "#inputBirth" ).on('input', function() {
    onChange();
  });
  $( "#inputPhone" ).change(function() {
    onChange();
  });
  $( "#inputGender" ).change(function() {
    onChange();
  });
  $( "#inputEmail" ).on('input', function() {
    onChange();
  });
  function onChange(){
    if (datas['dob'] != $("#inputBirth").val() ||
        datas['phone'] != $("#inputPhone").val() ||
        datas['gender'] != $("#inputGender").val() ||
        datas['email'] != $("#inputEmail").val()) {
      $("#btnSave").prop('disabled', false);
    }
    if (datas['dob'] == $("#inputBirth").val() &&
        datas['phone'] == $("#inputPhone").val() &&
        datas['gender'] == $("#inputGender").val() &&
        datas['email'] == $("#inputEmail").val()) {
      $("#btnSave").prop('disabled', true);
    }
  }
  $("#btnSave").click(function () {
    $("#inputBirth").removeClass('is-invalid');
    $("#inputPhone").removeClass('is-invalid');
    $("#inputEmail").removeClass('is-invalid');
    $("#inputGender").removeClass('is-invalid');
    $("#inputBirth").prop('disabled', true);
    $("#inputPhone").prop('disabled', true);
    $("#inputGender").prop('disabled', true);
    $("#inputEmail").prop('disabled', true);
    $("#btnSave").prop('disabled', true);
    $("#btnSave").html($("#btnSave").html() + ' '+ spinner);
    temp = {
      action: "self",
      phone: $("#inputPhone").val(),
      gender: $("#inputGender").val(),
      email: ($("#inputEmail").val().length == 0) ? null : $("#inputEmail").val()
    };
    if($("#inputBirth").val()) temp['dob'] = $("#inputBirth").val().trim();
    console.log(temp);
    $.post("/proc/options.php",  temp ,
     function(data) {
       datas = {
         dob: $("#inputBirth").val(),
         phone: $("#inputPhone").val(),
         gender: $("#inputGender").val(),
         email: $("#inputEmail").val()
       };
       $("#okText").fadeIn("fast");
       setTimeout(function () {
         $("#okText").fadeOut("slow");
       }, 2000);
       console.log(data);
     }, "json").fail(function(data) {
       console.log(data);
       if(data.status == 400){
         $("#btnSave").prop('disabled', false);
         data.responseJSON.errors.forEach(error => {
           switch (error.error_code) {
             case 7:{
               $("#invalidEmail").html(error.text);
               $("#inputEmail").addClass('is-invalid');
               $("#inputEmail").prop('value', $("#inputEmail").prop('value').replaceAll(" ",""));
               $("#inputEmail").focus();
             }break;
             case 6:{
               $("#invalidGender").html(error.text);
               $("#inputGender").addClass('is-invalid');
               $("#inputGender").focus();
             }break;
             case 5:{
               $("#invalidPhone").html(error.text);
               $("#inputPhone").addClass('is-invalid');
               $("#inputPhone").focus();
             }break;
             case 4:{
               $("#invalidBirth").html(error.text);
               $("#inputBirth").addClass('is-invalid');
               $("#inputBirth").focus();
             }break;
             default:showError(error.text, false);
           }
         });
       }else {
         showError(data.status, false);
       }
       return false;
     }).always(function() {
       $("#inputBirth").prop('disabled', false);
       $("#inputPhone").prop('disabled', false);
       $("#inputGender").prop('disabled', false);
       $("#inputEmail").prop('disabled', false);
       $("#btnSave").html('Сохранить');
      });
    $.post("/proc/options.php", temp ).done(function( data ) {
      console.log(data);
      $("#inputBirth").prop('disabled', false);
      $("#inputPhone").prop('disabled', false);
      $("#inputGender").prop('disabled', false);
      $("#inputEmail").prop('disabled', false);
      $("#btnSave").html('Сохранить');
      var response = JSON.parse(data);
      if (!response.is_error) {
        datas = {
          dob: $("#inputBirth").val(),
          phone: $("#inputPhone").val(),
          gender: $("#inputGender").val(),
          email: $("#inputEmail").val()
        };
        $("#okText").fadeIn("fast");
        setTimeout(function () {
          $("#okText").fadeOut("slow");
        }, 2000);
      }else {
        $("#btnSave").prop('disabled', false);
        data.responseJSON.errors.forEach(error => {
          switch (error.error_id) {
            case 7:{
              $("#invalidEmail").html(error.error_text);
              $("#inputEmail").addClass('is-invalid');
              $("#inputEmail").prop('value', $("#inputEmail").prop('value').replaceAll(" ",""));
              $("#inputEmail").focus();
            }break;
            case 6:{
              $("#invalidGender").html(error.error_text);
              $("#inputGender").addClass('is-invalid');
              $("#inputGender").focus();
            }break;
            case 5:{
              $("#invalidPhone").html(error.error_text);
              $("#inputPhone").addClass('is-invalid');
              $("#inputPhone").focus();
            }break;
            case 4:{
              $("#invalidBirth").html(error.error_text);
              $("#inputBirth").addClass('is-invalid');
              $("#inputBirth").focus();
            }break;
            default:showError(error.error_text, false);
          }
        });
      }
    }, "json");
  });
  $("input[type='checkbox']").click(function () {
    $("input[type='checkbox']").prop('disabled', true);
    $.post("/proc/options.php", {
      action: "privacy",
      phone: $("#phoneOption").prop('checked'),
      email: $("#emailOption").prop('checked')
    }).done(function( data ) {
      console.log(data);
      var response = JSON.parse(data);
      if (!response.is_error) {
        $("input[type='checkbox']").prop('disabled', false);
      }else {
        showError(response.error_text, true);
      }
    });
  });
});

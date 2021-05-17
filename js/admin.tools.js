
$('a.group-select').on('click', function(e) {
  $.post("/proc/admin.php", { action: 'changeGroup', gid: $(this).data('gid') },
   function(data) {
     location.reload();
   }, "json").fail(function(data) {
     if(data.status == 400){
       data.responseJSON.errors.forEach(error => {
         switch (error.error_code) {
           case 1:{
             showError(error.text, true);
           }break;
           default:{
             setTimeout(function () {
               $('#editScheduleModal').modal('hide');
             }, 500);
             showError(data.status, false);
           }break;
         }
       });
     }else {
       showError(data.status, false);
     }
     return false;
   });
});
$('.action-group').on('click', function(e) {
  var action = $(this).data('action');
  $('#inputGName').val('');
  $("#inputGName").removeClass("is-invalid");
  $("#selectGTeacher").removeClass("is-invalid");
  $("#selectGHeadman").removeClass("is-invalid");
  $('#saveGroupChanges').attr('data-action', action);
  $('#saveGroupChanges').attr('data-gid', ($(this).data('gid')) ? $(this).data('gid') : '-1');
  $('#modalGroupLabel').text((action == 'add')?'Добавляем группу':'Редактируем группу');
  $('#saveGroupChanges').html("Загрузка...");
  $('#saveGroupChanges').prop('disabled', true);
  var name = ($(this).data('name')) ? $(this).data('name') : '';
  var gid = ($(this).data('gid')) ? $(this).data('gid') : '-1';
  var tid = ($(this).data('tid')) ? $(this).data('tid') : '-1';
  var hid = ($(this).data('hid')) ? $(this).data('hid') : '-1';
  $('#selectGTeacher').html("");
  $('#selectGTeacher').append('<option value="-1">Не выбрано</option>');
  $('#selectGHeadman').html("");
  $('#selectGHeadman').append('<option value="-1">Не выбрано</option>');
  $('#inputGName').prop('disabled', true);
  $('#selectGTeacher').prop('disabled', true);
  $('#selectGHeadman').prop('disabled', true);

  $('#addInfo').slideUp('fast');
  $('#formGTeacher').slideDown('fast');
  $('#formGHeadman').slideDown('fast');
  $.get("/proc/admin.php", { action: 'preAddGroup' , group: gid },
   function(data) {
     console.log(data.students.length);
     data.teachers.forEach((item, i) => {
       $('#selectGTeacher').append('<option value="' + item.id + '">' + item.name + '</option>');
     });
     data.students.forEach((item, i) => {
       $('#selectGHeadman').append('<option value="' + item.id + '">' + item.name + '</option>');
     });
     $('#selectGTeacher').val((action == 'add')?'-1':tid);
     $('#selectGHeadman').val((action == 'add')?'-1':hid);
     $('#inputGName').val((action == 'add')?'':name);
     $('#saveGroupChanges').html((action == 'add')?'Добавить':'Изменить');
     $('#saveGroupChanges').prop('disabled', false);
     $('#inputGName').prop('disabled', false);
     setTimeout(function () {
       if(action == 'add'){
         $('#formGTeacher').slideUp('slow');
         $('#formGHeadman').slideUp('slow');
         $('#addInfo').text('Выбрать куратора и модератора можно после добавления студентов и преподавателей.');
         $('#addInfo').slideDown('slow');
       }else if (data.teachers.length > 0 && data.students.length == 0) {
         $('#formGHeadman').slideUp('slow');
         $('#addInfo').text('Выбрать модератора можно после добавления студентов.');
         $('#addInfo').slideDown('slow');
       }else if (data.teachers.length == 0 && data.students.length > 0) {
         $('#formGTeacher').slideUp('slow');
         $('#addInfo').text('Выбрать куратора можно после добавления преподавателей.');
         $('#addInfo').slideDown('slow');
       }else if (data.teachers.length == 0 && data.students.length == 0) {
         $('#formGTeacher').slideUp('slow');
         $('#formGHeadman').slideUp('slow');
         $('#addInfo').text('Сначала нужно добавить студентов и преподавателей, а уже потом можно назначать кураторов и модераторов.');
         $('#addInfo').slideDown('slow');
       }
     }, 300);
     setTimeout(function () {
       $('#inputGName').focus();
       $('#selectGTeacher').prop('disabled', false);
       $('#selectGHeadman').prop('disabled', false);
     }, 500);
   }, "json").fail(function(data) {
     setTimeout(function () {
       $('#GroupModal').modal('hide');
     }, 500);
     showError(data.status, false);
     return false;
   });
});
$('#saveGroupChanges').on('click', function(e) {
  var action = $(this).data('action');
  $("#inputGName").removeClass("is-invalid");
  $("#selectGTeacher").removeClass("is-invalid");
  $("#selectGHeadman").removeClass("is-invalid");
  $('#saveGroupChanges').html("Загрузка...");
  $('#saveGroupChanges').prop('disabled', true);
  $('#inputGName').prop('disabled', true);
  $('#selectGTeacher').prop('disabled', true);
  $('#selectGHeadman').prop('disabled', true);
  $.post("/proc/admin.php", { action: 'addGroup', name: $('#inputGName').val(), tid: $('#selectGTeacher').val(), hid: $('#selectGHeadman').val() , gid: $('#saveGroupChanges').attr('data-gid')},
   function(data) {
     setTimeout(function () {
       $('#GroupModal').modal('hide');
     }, 500);
     location.reload();
   }, "json").fail(function(data) {
     if(data.status == 400){
       data.responseJSON.errors.forEach(error => {
         switch (error.error_code) {
           case 1:{
             $("#invalidGName").html(error.text);
             $("#inputGName").addClass("is-invalid");
           }break;
           case 2:{
             $("#invalidGTeacher").html(error.text);
             $("#selectGTeacher").addClass("is-invalid");
           }break;
           case 3:{
             $("#invalidGHeadman").html(error.text);
             $("#selectGHeadman").addClass("is-invalid");
           }break;
           default:{
             setTimeout(function () {
               $('#GroupModal').modal('hide');
             }, 500);
             showError(data.status, false, 'Ошибочка ' + data.status, error.text);
           }break;
         }
       });
     }else {
       setTimeout(function () {
         $('#GroupModal').modal('hide');
       }, 500);
       showError(data.status, false, 'Ошибочка ' + data.status);
     }
     return false;
   }).always(function() {
     $('#saveGroupChanges').html((action == 'add')?'Добавить':'Изменить');
     $('#saveGroupChanges').prop('disabled', false);
     $('#inputGName').prop('disabled', false);
     $('#selectGTeacher').prop('disabled', false);
     $('#selectGHeadman').prop('disabled', false);
    });
});
$('.remove-group').on('click', function(e) {
  $('#removeSubmit').prop('disabled', true);
  $('#removeSubmit').text("Загрузка...");
  $('#removeSubmit').attr('data-gid', '-1');
  $('#removeSubmit').attr('data-action', $(this).data('action'));
  $('#removeBody').slideUp('fast');
  var action = $(this).data('action');
  $.get("/proc/admin.php", { action: 'preRemove', gid: $(e.currentTarget).data('gid')},
   function(data) {
     $('#removeSubmit').text("Удалить");
     $('#removeSubmit').prop('disabled', false);
     $('#removeBody').html('Вы действительно ходите удалить группу <b>' + $(e.currentTarget).data('name') + '</b>?');
     if(data.teachers > 0) {
       $('#removeBody').html($('#removeBody').html() + ' Будет ' + getEnding(data.teachers, ['удалён <b>' + data.teachers + '</b> преподаватель','удалено <b>' + data.teachers + '</b> преподавателя','удалено <b>' + data.teachers + '</b> преподавателей']));
       if(data.lessons > 0) {
         $('#removeBody').html($('#removeBody').html() + getEnding(data.teachers, [' который ведёт',' которые ведут',' которые ведут']) +
         ' <b>' + data.lessons + '</b> '+ getEnding(data.lessons, ['урок','урока','уроков'])
         + ' из списка занятий' + ((data.schedules > 0) ? (' и <b>' + data.schedules + '</b> ' + getEnding(data.schedules, ['урок','урока','уроков']) +' из расписания.') : '.'));
       }else {
         $('#removeBody').html($('#removeBody').html() + '.');
       }
     }
     if(data.students > 0) {
       $('#removeBody').html($('#removeBody').html() + ' Так же ' + getEnding(data.students, ['будет удалён <b>' + data.students + '</b> студент','будет удалено <b>'
       + data.students + '</b> студента','будет удалено <b>' + data.teachers + '</b> студентов']) + '.');
     }
     $('#removeSubmit').attr('data-gid', $(e.currentTarget).data('gid'));
     $('#removeBody').slideDown('fast');
   }, "json").fail(function(data) {
     setTimeout(function () {
       $('#removeModal').modal('hide');
     }, 500);
     showError(data.status, false);
     return false;
   });
});
$('#removeSubmit').on('click', function(e) {
  var action = $(this).data('action');
  if(action == 'group'){
    $('#removeSubmit').text("Удаление...");
    $('#removeSubmit').prop('disabled', true);
    $.post("/proc/admin.php", { action: 'remove', gid: $('#removeSubmit').attr('data-gid') },
     function(data) {
       location.reload();
     }, "json").fail(function(data) {
       if(data.status == 400){
         data.responseJSON.errors.forEach(error => {
           switch (error.error_code) {
             default:{
               setTimeout(function () {
                 $('#removeModal').modal('hide');
               }, 500);
               showError(data.status, false);
             }break;
           }
         });
       }else {
         setTimeout(function () {
           $('#removeModal').modal('hide');
         }, 500);
         showError(data.status, false, false, (data.status == 500)? data.responseJSON.error_text: '');
       }
       return false;
     }).always(function() {
       $('#removeSubmit').text("Удалить");
       $('#removeSubmit').prop('disabled', false);
      });
  }
});

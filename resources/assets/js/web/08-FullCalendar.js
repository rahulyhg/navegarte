/*
$(document).ready(function () {
  var $calendar = $('*[data-toggle="fullcalendar"]');
  
  $calendar.each(function (index, element) {
    if (typeof fullCalendar !== undefined) {
      $(element).fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay,listWeek'
        },
        lang: 'pt-br',
        defaultDate: new Date(),
        navLinks: true,
        editable: false,
        eventLimit: true,
        eventStartEditable: false,
        events: {
          url: $(element).data('url'),
          type: 'POST',
          dataType: 'json',
          cache: true,
          success: function (response) {
            // Error
            if (response.error) {
              $(element).empty().html('<div class="alert alert-danger text-center margin-bottom-0"><b>Calendário ERRO: </b>' + response.error + '</div>');
            }
          },
          error: function () {
            $(element).empty().html('<div class="alert alert-danger text-center margin-bottom-0">Não foi possível carregar o calendário, tente novamente atualizando a página.</div>');
          }
        }
      });
    }
  });
});
*/

/**
 * Inicia as configurações do full calendar
 *
 * @param calendars
 *
 * https://fullcalendar.io/
 */

var initFullCalendar = function (calendars) {
  if (calendars.length) {
    $.each(calendars, function (index, element) {
      var option = $(element).data('option');
      
      /* Verifica a URL da requisição */
      if (option.url === undefined || option.url === '') {
        alert('È Preciso passar a URL para o funcionamento do FULL CALENDAR.');
        
        return;
      }
      
      /* Inicia o fullCalendar */
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
          url: option.url,
          data: option.data !== undefined ? option.data : {},
          type: 'POST',
          dataType: 'json',
          cache: true,
          success: function (response) {
            /* Verifica se ocorreu erro */
            if (response.error) {
              $(element).empty().html('<div class="alert alert-danger text-center mb-0"><b>ERRO Calendário: </b>' + response.error + '</div>');
            }
          },
          error: function () {
            $(element)
              .empty()
              .html('<div class="alert alert-danger text-center mb-0">Não foi possível carregar o calendário, se o erro persistir entre em contato conosco.</div>');
          }
        }
      });
    });
  }
};

/* Carrega o documento */
$(document).ready(function () {
  /* INIT :: Full Calendar */
  initFullCalendar($('*[data-toggle="fullcalendar"]'));
});

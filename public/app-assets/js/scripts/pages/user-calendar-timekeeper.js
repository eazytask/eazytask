/**
 * App Calendar
 */

/**
 * ! If both start and end dates are same Full calendar will nullify the end date value.
 * ! Full calendar will end the event on a day before at 12:00:00AM thus, event won't extend to the end date.
 * ! We are getting events from a separate file named app-calendar-events.js. You can add or remove events from there.
 **/

'use-strict';
// RTL Support
var direction = 'ltr',
  assetPath = '../../../app-assets/';
if ($('html').data('textdirection') == 'rtl') {
  direction = 'rtl';
}

if ($('body').attr('data-framework') === 'laravel') {
  assetPath = $('body').attr('data-asset-path');
}

$(document).on('click', '.fc-sidebarToggle-button', function (e) {
  $('.app-calendar-sidebar, .body-content-overlay').addClass('show');
});

$(document).on('click', '.body-content-overlay', function (e) {
  $('.app-calendar-sidebar, .body-content-overlay').removeClass('show');
});

document.addEventListener('DOMContentLoaded', function () {

  $.time = function (dateObject) {
    var d = new Date(dateObject);
    var curr_hour = d.getHours();
    var curr_min = d.getMinutes();
    var date = curr_hour + ':' + curr_min;
    return date;
  };
  var calendarEl = document.getElementById('user_calendar_timekeeper');
  var eventToUpdate;
  var sidebar = $('.event-sidebar');
  var calendarsColor = {
    Business: 'primary',
    Holiday: 'success',
    Personal: 'danger',
    Family: 'warning',
    ETC: 'info'
  };
  var cancelBtn = $('.btn-cancel');
  var toggleSidebarBtn = $('.btn-toggle-sidebar');
  var eventTitle = $('#title');
  var eventLabel = $('#select-label');
  //form id declar start
  var project_id = $('#project-select');
  var roaster_date = $('#roaster_date');
  var shift_start = $('#shift_start');
  var shift_end = $('#shift_end');
  var duration = $('#duration');
  var ratePerHour = $('#rate');
  var amount = $('#amount');
  var job_type_id = $('#job');
  var remarks = $('#remarks');

  // var employeeFilter = $('#employeeFilter');
  // var clientFilter = $('#clientFilter');
  var projectFilter = $('#projectFilter');
  //form id declar end



  var eventUrl = $('#event-url');
  var eventGuests = $('#event-guests');
  var eventLocation = $('#event-location');
  var allDaySwitch = $('.allDay-switch');
  var selectAll = $('.select-all');
  var calEventFilter = $('.calendar-events-filter');
  var filterInput = $('.input-filter');
  var calendarEditor = $('#event-description-editor');

  // --------------------------------------------
  // On add new item, clear sidebar-right field fields
  // --------------------------------------------

  shift_start.prop('disabled', true);
  shift_end.prop('disabled', true);

  if (roaster_date.length) {
    var roaster_date_get = roaster_date.flatpickr({
      enableTime: false,
      altFormat: 'd-m-Y',
      dateFormat: "d-m-Y",
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      },
      onChange: function (selectedDates, dateStr, instance) {
        // shift_start.prop('disabled', false);   
        shift_start_get.set('minDate', moment(new Date(selectedDates)).format('DD-MM-YYYYTHH:mm:ss'));
        shift_start_get.set('maxDate', moment(new Date(selectedDates)).add(24, "h").format('DD-MM-YYYY') + 'T23:59:00');

        // shift_end.prop('disabled', false);        
        shift_end_get.set('minDate', moment(new Date(selectedDates)).format('DD-MM-YYYYTHH:mm:ss'));
        shift_end_get.set('maxDate', moment(new Date(selectedDates)).add(24, "h").format('DD-MM-YYYY') + 'T23:59:00');
      }
    });
  }

  $('.add-event button').on('click', function (e) {
    $('.event-sidebar').addClass('show');
    $('.sidebar-left').removeClass('show');
    $('.app-calendar .body-content-overlay').addClass('show');
  });

  $(document).on('change', '#projectFilter', function () {
    calendar.refetchEvents();
  });
  // Label  select
  if (eventLabel.length) {
    function renderBullets(option) {
      if (!option.id) {
        return option.text;
      }
      var $bullet =
        "<span class='bullet bullet-" +
        $(option.element).data('label') +
        " bullet-sm mr-50'> " +
        '</span>' +
        option.text;

      return $bullet;
    }
    eventLabel.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select value',
      dropdownParent: eventLabel.parent(),
      templateResult: renderBullets,
      templateSelection: renderBullets,
      minimumResultsForSearch: -1,
      escapeMarkup: function (es) {
        return es;
      }
    });
  }

  // Guests select
  if (eventGuests.length) {
    function renderGuestAvatar(option) {
      if (!option.id) {
        return option.text;
      }

      var $avatar =
        "<div class='d-flex flex-wrap align-items-center'>" +
        "<div class='avatar avatar-sm my-0 mr-50'>" +
        "<span class='avatar-content'>" +
        "<img src='" +
        assetPath +
        'images/avatars/' +
        $(option.element).data('avatar') +
        "' alt='avatar' />" +
        '</span>' +
        '</div>' +
        option.text +
        '</div>';

      return $avatar;
    }
    eventGuests.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select value',
      dropdownParent: eventGuests.parent(),
      closeOnSelect: false,
      templateResult: renderGuestAvatar,
      templateSelection: renderGuestAvatar,
      escapeMarkup: function (es) {
        return es;
      }
    });
  }

  // Start date picker

  if (shift_start.length) {
    var shift_start_get = shift_start.flatpickr({
      enableTime: true,
      altFormat: 'd-m-YTH:i:S',
      time_24hr: true,
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }
  if (shift_end.length) {
    var shift_end_get = shift_end.flatpickr({
      enableTime: true,
      altFormat: 'd-m-YTH:i:S',
      time_24hr: true,
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }


  // Add new event
  $("#accept").on('click', function () {
    let action = $("#accept").attr('accept')
    if (action == 'shift') {
      $.ajax({
        url: "/home/unconfirmed/multiple/shift/accept/" + $("#event_id").val(),
        type: "GET",
        dataType: 'json',
        success: function (data) {

        }
      });
    } else if (action == 'event') {
      $.ajax({
        data: $('#signInFrom').serialize(),
        url: "/user/home/event/store",
        type: "POST",
        dataType: 'json',
        success: function (data) {

        }
      });
    }
    toastr['success'](action+' successfully accepted', 'Success!', {
      closeButton: true,
      tapToDismiss: false,
    });
    calendar.refetchEvents();
    $("#add-new-sidebar").modal("hide")
  })

  // Event click function
  function eventClick(info) {
    resetValues()
    eventToUpdate = info.event;
    if (eventToUpdate.url) {
      info.jsEvent.preventDefault();
      window.open(eventToUpdate.url, '_blank');
    }

    let thisRow = eventToUpdate.extendedProps;
    if (thisRow.latest && !thisRow.is_applied && thisRow.is_event) {
      $('#event_id').val(thisRow.id)
      $('#accept').html('Intersted')
      $('#accept').prop('hidden', false)
      $('#accept').attr('accept', 'event')
      $('#request').prop('hidden', true)
    } else if (thisRow.latest && !thisRow.is_applied && thisRow.roaster_type == 'Schedueled') {
      $('#event_id').val(thisRow.id)
      $('#accept').html('Accept')
      $('#accept').prop('hidden', false)
      $('#accept').attr('accept', 'shift')
      $('#request').prop('hidden', true)
    } else {
      if (thisRow.is_event) {
        if (thisRow.is_applied) {
          $('#request').html('Requested')
        } else {
          $('#request').html('Intersted')
        }
      } else {
        $('#request').html('Accepted')
      }
      $('#accept').prop('hidden', true)
      $('#request').prop('hidden', false)
    }

    sidebar.modal('show');
    // cancelBtn.addClass('d-none');

    eventTitle.val(eventToUpdate.title);
    project_id.val(thisRow.project_id).trigger('change')
    ratePerHour.val(thisRow.ratePerHour);
    roaster_date.val(moment(thisRow.roaster_date).format('DD-MM-YYYY'))
    // roaster_date_get.setDate(thisRow.roaster_date, true, 'd-m-Y');
    shift_start.val($.time(thisRow.shift_start));
    shift_end.val($.time(thisRow.shift_end));
    duration.val(thisRow.duration);

    amount.val(thisRow.amount);
    job_type_id.val(thisRow.job_type_id).trigger('change');
    remarks.val(thisRow.remarks);
    //  Delete Event
  }


  // Modify sidebar toggler
  function modifyToggler() {
    $('.fc-sidebarToggle-button')
      .empty()
      .append(feather.icons['menu'].toSvg({ class: 'ficon' }));
  }

  // Selected Checkboxes
  function selectedCalendars() {
    var selected = [];
    $('.calendar-events-filter input:checked').each(function () {
      selected.push($(this).attr('data-value'));
    });
    return selected;
  }

  // --------------------------------------------------------------------------------------------------
  // AXIOS: fetchEvents
  // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
  // --------------------------------------------------------------------------------------------------
  function fetchEvents(info, successCallback) {
    $.ajax(
      {
        url: '/user/dataget',
        data: {
          'projectFilter': projectFilter.val().toString(),
        },
        type: 'GET',
        success: function (result) {
          var calendars = selectedCalendars();
          successCallback(result.events);
        },
        error: function (error) {
          console.log(error);
        }
      }
    );

    // var calendars = selectedCalendars();
    // // We are reading event object from app-calendar-events.js file directly by including that file above app-calendar file.
    // // You should make an API call, look into above commented API call for reference
    // selectedEvents = events.filter(function (event) {
    //   // console.log(event.extendedProps.calendar.toLowerCase());
    //   return calendars.includes(event.extendedProps.calendar.toLowerCase());
    // });
    // // if (selectedEvents.length > 0) {
    // successCallback(selectedEvents);
    // }
  }

  // Calendar plugins
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    firstDay: 1,
    eventDisplay: 'block',
    displayEventTime: false,
    eventDidMount: function (info) {
      $(info.el).tooltip({
        title: "<div class='text-left'>" + info.event.extendedProps.description + "</div>",
        // title: info.event.extendedProps.calendar,                
        html: true,
        placement: 'top',
        trigger: 'hover',
        container: 'body'
      });
    },
    nextDayThreshold: '00:00:00',
    events: fetchEvents,
    editable: true,
    dragScroll: true,
    dayMaxEvents: 5,
    eventResizableFromStart: true,
    eventStartEditable: false,
    customButtons: {
      sidebarToggle: {
        text: 'Sidebar'
      }
    },
    headerToolbar: {
      start: 'sidebarToggle, prev,next, title',
      // end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      end: 'dayGridMonth,dayGridWeek,dayGridDay,listMonth'
    },
    direction: direction,
    initialDate: new Date(),
    navLinks: true, // can click day/week names to navigate views
    eventClassNames: function ({ event: calendarEvent }) {
      // const colorName = calendarsColor[calendarEvent._def.extendedProps.calendar];
      return [
        // Background Color
        // 'bg-light-primary'
        calendarEvent.extendedProps.calendar
      ];
    },
    eventClick: function (info) {
      window.new_info = info;
      eventClick(info);
    },
    datesSet: function () {
      modifyToggler();
    },
    viewDidMount: function () {
      modifyToggler();
    }
  });

  // Render calendar
  calendar.render();
  // Modify sidebar toggler
  modifyToggler();
  // updateEventClass();

  // Validate add new and update form

  jQuery.validator.addMethod("shift_start_check", function (value, element, param) {
    if (param[0] == "") {
      return false;
    }
    else {
      if (new Date(param[0] + ' 00:00:00') - new Date(value) > 0) {
        return false;
      } else {
        return true;
      }
    }

  },);
  jQuery.validator.addMethod("shift_end_check", function (value, element, param) {
    if (param[0] == "" && param[1] == "") {
      return false;
    }
    else {
      if (new Date(param[0] + ' 00:00:00') - new Date(value) > 0 || ((new Date(param[0] + ' 00:00:00') - new Date(value)) == 0)) {
        return false;
      }
      else if (new Date(param[1]) - new Date(value) > 0 || ((new Date(param[1]) - new Date(value)) == 0)) {
        return false;
      }
      else {
        return true;
      }
    }
  },);


  // Sidebar Toggle Btn
  if (toggleSidebarBtn.length) {
    toggleSidebarBtn.on('click', function () {
      cancelBtn.removeClass('d-none');
    });
  }

  // Reset sidebar input values
  function resetValues() {
    //form filed reset

    project_id.val('').trigger('change');
    roaster_date.val('');
    shift_start.val('');
    shift_end.val('');
    duration.val('');
    ratePerHour.val('');
    amount.val('');
    job_type_id.val('').trigger('change');
    remarks.val('');
    //form filed reset
  }

  // When modal hides reset input values
  sidebar.on('hidden.bs.modal', function () {
    resetValues();
  });

  // Hide left sidebar if the right sidebar is open
  $('.btn-toggle-sidebar').on('click', function () {
    $('.app-calendar-sidebar, .body-content-overlay').removeClass('show');
  });

  // Select all & filter functionality
  if (selectAll.length) {
    selectAll.on('change', function () {
      var $this = $(this);

      if ($this.prop('checked')) {
        calEventFilter.find('input').prop('checked', true);
      } else {
        calEventFilter.find('input').prop('checked', false);
      }
      calendar.refetchEvents();
    });
  }

  if (filterInput.length) {
    filterInput.on('change', function () {
      $('.input-filter:checked').length < calEventFilter.find('input').length
        ? selectAll.prop('checked', false)
        : selectAll.prop('checked', true);
      calendar.refetchEvents();
    });
  }
});

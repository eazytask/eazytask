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

$.time = function (dateObject) {
  var t = dateObject.split(/[- :]/);
  // Apply each element to the Date function
  var actiondate = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
  var d = new Date(actiondate);

  // var d = new Date(dateObject);
  var curr_hour = d.getHours();
  var curr_min = d.getMinutes();
  var date = curr_hour + ':' + curr_min;
  return date;
};
// calender event start
document.addEventListener('DOMContentLoaded', function () {
  var calendarEl = document.getElementById('calendar_event_request');
  var eventToUpdate;
  var sidebar = $('.event-sidebar');
  var eventForm = $('.event-form');
  var publish = $('#addToRoaster');
  var sendNotif = $('#sendNotification');
  var addEventBtn = $('#addEventBtn');
  var editEventBtn = $('#editEventBtn');
  var eventTitle = $('#title');
  var eventLabel = $('#select-label');

  var projectFilter = $('#projectFilter');
  //form id declar end



  var eventUrl = $('#event-url');
  var eventGuests = $('#event-guests');
  var eventLocation = $('#event-location');
  var allDaySwitch = $('.allDay-switch');
  var selectAll = $('.select-all');
  var calEventFilter = $('.calendar-events-filter');
  var filterInput = $('.input-filter');
  var btnDeleteEvent = $('.btn-delete-event');
  var calendarEditor = $('#event-description-editor');

  let ids = []
  let totalId = []
  let event_id = null
  let current_ids = []
  // --------------------------------------------
  // On add new item, clear sidebar-right field fields
  // --------------------------------------------


  $(document).on("click", ".checkID", function () {
    if ($(this).is(':checked')) {
      ids.push($(this).val())
    } else {
      let id = $(this).val()
      ids = jQuery.grep(ids, function (value) {
        return value != id
      })
    }

    if (ids.length === 0) {
      $("#addToRoaster").prop('disabled', true)
    } else {
      $("#addToRoaster").prop('disabled', false)
    }

    if (ids.length == totalId.length) {
      $('#checkAllID').prop('checked', true)
    } else {
      $('#checkAllID').prop('checked', false)
    }
  })
  checkAllID = function () {
    if ($("#checkAllID").is(':checked')) {
      ids = totalId
      $('.checkID').prop('checked', true)
    } else {
      ids = []
      $('.checkID').prop('checked', false)
    }

    if (ids.length === 0) {
      $("#addToRoaster").prop('disabled', true)
    } else {
      $("#addToRoaster").prop('disabled', false)
    }

  }
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

  //add event modal
  $('#addEvent').on('click', function () {
    resetValues()
    $('.event-modal-title').html('Add Event')
    $('#editEventBtn').prop('hidden', true)
    $('#addEventBtn').prop('hidden', false)

    $('#addEventModal').modal('show')
  });
  //event update modal
  $('#editEvent').on('click', function () {
    $('.event-modal-title').html('Update Event')
    $('#editEventBtn').prop('hidden', false)
    $('#addEventBtn').prop('hidden', true)

    $("#eventClick").modal("hide")
    $('#addEventModal').modal('show')
  });

  $('#filterStatus').on('change', function () {
    showImployees($('#filterStatus').val())
  });

  //show imployee by status
  function showImployees(status) {
    eventToUpdate = window.info.event;

    ids = []
    totalId = []
    $('#checkAllID').prop('checked', false)
    event_id = window.info.event.extendedProps.id
    let employees = window.info.event.extendedProps.employees

    let rows = ''
    $.each(employees, function (index, employee) {
      if (status == 'requested' && employee.requested) {
        insertThis(employee)
      } else if (status == 'inducted' && employee.inducted) {
        insertThis(employee)
      } else if (status == 'all') {
        insertThis(employee)
      }

    })
    function insertThis(employee) {
      let status = ''
      let employeeId = null
      let checkbox_status = ''
      if (employee.status == 'Added') {
        status = 'badge badge-pill badge-light-success mr-1'
        checkbox_status = 'disabled'
        current_ids.push(employee.id)
      } else {
        totalId.push(employee.id)
        employeeId = employee.id
      }

      rows += `
                <tr>
                    <td><input type="checkbox" class="checkID" value="` + employeeId + `" ` + checkbox_status + `></td>
                    <td>` + employee.fname + `</td>
                    <td>` + employee.contact_number + `</td>
                    <td>` + employee.email + `</td>
                    <td class="` + status + `">` + employee.status + `</td>
                </tr>
                `
    }
    if (totalId == 0) {
      $("#checkAllID").prop('disabled', true)
    } else {
      $("#checkAllID").prop('disabled', false)
    }
    console.log(current_ids)
    // if(current_ids.length > 0) {
    //   $("#sendNotification").prop('disabled', false)
    // }else{
    //   $("#sendNotification").prop('disabled', true)
    // }
    $('#eventClickTable').DataTable().clear().destroy();
    $('#eventClickTbody').html(rows);
    $('#eventClickTable').DataTable();
  }
  // Event click function
  function eventClick(info) {
    // edit event value set
    // console.log(info.event.extendedProps.all_employees)
    resetValues()
    $('#event_id').val(info.event.extendedProps.id);
    $('#project_name').val(info.event.extendedProps.project_name).trigger('change');
    $('#event_date').val(info.event.extendedProps.event_date);
    $('#shift_start').val($.time(info.event.extendedProps.shift_start));
    $('#shift_end').val($.time(info.event.extendedProps.shift_end));
    $('#rate').val(info.event.extendedProps.rate);
    $('#remarks').val(info.event.extendedProps.remarks);
    $('#no_employee_required').val(info.event.extendedProps.no_employee_required);
    $('#job_type_name').val(info.event.extendedProps.job_type_name).trigger('change');

    //event click modal
    $('#eventName').html(info.event.extendedProps.project.pName).trigger('change');
    $('#eventShift').html("Shift-Time: " + $.time(info.event.extendedProps.shift_start) + " to " + $.time(info.event.extendedProps.shift_end));
    $('#eventRemarks').html(info.event.extendedProps.remarks);

    //add to roaster
    window.info = info
    $('#filterStatus').val('all')
    showImployees('all')

    $("#eventClick").modal("show")

    if (eventToUpdate.url) {
      info.jsEvent.preventDefault();
      window.open(eventToUpdate.url, '_blank');
    }
    eventTitle.val(eventToUpdate.title);
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
    // Fetch Events from API endpoint reference
    $.ajax(
      {
        url: '/admin/home/event/search',
        data: {
          'projectFilter': projectFilter.val(),
        },
        type: 'GET',
        success: function (result) {
          // Get requested calendars as Array
          var calendars = selectedCalendars();
          // selectedEvents = result.events.filter(function (event) {
          //   console.log(event.extendedProps.calendar.toLowerCase());
          //   return calendars.includes(event.extendedProps.calendar.toLowerCase());
          // });   
          // console.log("selectedEvents=",selectedEvents);       
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
    initialView: 'listMonth',
    firstDay: 1,
    eventDisplay: 'block',
    displayEventTime: false,
    eventDidMount: function (info) {
      $(info.el).tooltip({
        title: "<b>Start time: "+ info.event.extendedProps.shift_start +"</b><br><b>End time: "+ info.event.extendedProps.shift_end +"</b><br><b>Rate: $"+ info.event.extendedProps.rate +"</b><br><b>Employee required: "+ info.event.extendedProps.no_employee_required +"</b><br><br><div class='text-left'>" + info.event.extendedProps.description + "</div>",
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
    dateClick: function (info) {

    },
    eventClick: function (info) {
      if (info.event.extendedProps.latest) {
        eventClick(info);
      }
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

  // ------------------------------------------------
  // addEvent
  // ------------------------------------------------
  function addEvent(eventData) {
    calendar.addEvent(eventData);
    calendar.refetchEvents();
  }

  // ------------------------------------------------
  // updateEvent
  // ------------------------------------------------
  function updateEvent(eventData) {
    var propsToUpdate = ['id', 'title', 'url'];
    var extendedPropsToUpdate = ['calendar', 'guests', 'location', 'description'];

    updateEventInCalendar(eventData, propsToUpdate, extendedPropsToUpdate);
  }

  // ------------------------------------------------
  // removeEvent
  // ------------------------------------------------
  function removeEvent(eventId) {
    removeEventInCalendar(eventId);
  }

  // ------------------------------------------------
  // (UI) updateEventInCalendar
  // ------------------------------------------------
  const updateEventInCalendar = (updatedEventData, propsToUpdate, extendedPropsToUpdate) => {
    const existingEvent = calendar.getEventById(updatedEventData.id);

    // --- Set event properties except date related ----- //
    // ? Docs: https://fullcalendar.io/docs/Event-setProp
    // dateRelatedProps => ['start', 'end', 'allDay']
    // eslint-disable-next-line no-plusplus
    for (var index = 0; index < propsToUpdate.length; index++) {
      var propName = propsToUpdate[index];
      existingEvent.setProp(propName, updatedEventData[propName]);
    }

    // --- Set date related props ----- //
    // ? Docs: https://fullcalendar.io/docs/Event-setDates
    existingEvent.setDates(updatedEventData.start, updatedEventData.end, { allDay: updatedEventData.allDay });

    // --- Set event's extendedProps ----- //
    // ? Docs: https://fullcalendar.io/docs/Event-setExtendedProp
    // eslint-disable-next-line no-plusplus
    for (var index = 0; index < extendedPropsToUpdate.length; index++) {
      var propName = extendedPropsToUpdate[index];
      existingEvent.setExtendedProp(propName, updatedEventData.extendedProps[propName]);
    }
  };

  // ------------------------------------------------
  // (UI) removeEventInCalendar
  // ------------------------------------------------
  function removeEventInCalendar(eventId) {
    calendar.getEventById(eventId).remove();
  }


  // publish employee request
  publish.on('click', function () {
    $("#addToRoaster").prop('disabled', true)

    $.ajax({
      url: '/admin/home/event/publish',
      data: {
        'event_id': event_id,
        'employee_ids': ids,
      },
      type: 'GET',
      success: function (data) {
        calendar.refetchEvents();
        toastr['success']('👋 Added Successfully', 'Success!', {
          closeButton: true,
          tapToDismiss: false,
        });
      }
    })
    $("#eventClick").modal("hide")

    ids = []
    totalId = []
    $('#checkAllID').prop('checked', false)
    // $("#addToRoaster").prop('disabled', true)
  });

  sendNotif.on('click', function() {
    // $("#sendNotification").prop('disabled', true)
    $.ajax({
      url: '/admin/home/event/send-notif',
      data: {
        'event_id': event_id,
        'employee_ids': current_ids,
      },
      type: 'GET',
      success: function (data) {
        calendar.refetchEvents();
        toastr['success']('👋 Notif Sent Succesfully', 'Success!', {
          closeButton: true,
          tapToDismiss: false,
        });
      }
    })
    $("#eventClick").modal("hide")

    ids = []
    totalId = []
    current_ids = []
    $('#checkAllID').prop('checked', false)
    $("#addToRoaster").prop('disabled', true)
  })

  // Add new event
  $(addEventBtn).on('click', function () {
    if ($("#addEventForm").valid()) {
      $.ajax({
        data: $('#addEventForm').serialize(),
        url: "/admin/home/upcomingevent/store",
        type: "POST",
        dataType: 'json',
        success: function (data) {
          calendar.refetchEvents();
          if (data.status) {
            toastr.success(data.msg)
          } else {
            toastr.info(data.msg)
          }
        },
        error: function (data) {
          toastr['error']('👋 Not Added', 'Error!', {
            closeButton: true,
            tapToDismiss: false,
          });
        }
      });
      $('#addEventModal').modal('hide')
      resetValues()
    }
  });
  // update event
  $(editEventBtn).on('click', function () {
    if ($("#addEventForm").valid()) {
      $.ajax({
        data: $('#addEventForm').serialize(),
        url: "/admin/home/upcomingevent/update",
        type: "POST",
        dataType: 'json',
        success: function (data) {
          calendar.refetchEvents();
          if (data.status) {
            toastr.success(data.msg)
          } else {
            toastr.info(data.msg)
          }
        },
        error: function (data) {
          toastr['error']('👋 something went wrong', 'Error!', {
            closeButton: true,
            tapToDismiss: false,
          });
        }
      });
      $('#addEventModal').modal('hide')
      resetValues()
    }
  });

  //delete event
  $('#deleteEvent').on('click', function () {
    event_id = $('#event_id').val()
    $.ajax({
      url: '/admin/home/upcomingevent/delete/' + event_id,
      type: 'GET',
      success: function (data) {
        calendar.refetchEvents();
        toastr['success']('👋 delete Successfully', 'Success!', {
          closeButton: true,
          tapToDismiss: false,
        });
      }
    })
    $("#eventClick").modal("hide")
  });


  // Reset sidebar input values
  function resetValues() {
    current_ids = [];
    //form filed reset
    $('#project_name').val('').trigger('change');
    $('#event_date').val('');
    $('#shift_start').val('');
    $('#shift_end').val('');
    $('#rate').val('');
    $('#remarks').val('');
    $('#no_employee_required').val('');
    $('#job_type_name').val('').trigger('change');
    //form filed reset
  }
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

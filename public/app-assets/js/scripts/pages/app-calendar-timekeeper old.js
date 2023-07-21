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
  var calendarEl = document.getElementById('calendar_timekeeper');
  var eventToUpdate;
  var sidebar = $('.event-sidebar');
  var calendarsColor = {
        Business: 'primary',
        Holiday: 'success',
        Personal: 'danger',
        Family: 'warning',
        ETC: 'info'
      };
  var eventForm = $('.event-form');
  var addEventBtn = $('.add-event-btn');
  var copyEventBtn = $('.copy-event-btn');
  var cancelBtn = $('.btn-cancel');
  var updateEventBtn = $('.update-event-btn');
  var toggleSidebarBtn = $('.btn-toggle-sidebar');
  var eventTitle = $('#title');
  var eventLabel = $('#select-label');
  //form id declar start
  var employee_id = $('#employee_id');
  var client_id = $('#client_id');
  var project_id = $('#project_id');  
  var roaster_date = $('#roaster_date');
  var shift_start = $('#shift_start');
  var shift_end = $('#shift_end');
  var duration = $('#duration');
  var ratePerHour = $('#ratePerHour');
  var amount = $('#amount');
  var job_type_id = $('#job_type_id');
  var roaster_status_id = $('#roaster_status_id');
  var roaster_type = $('#roaster_type');
  var remarks = $('#remarks');

  var employeeFilter = $('#employeeFilter');
  var clientFilter = $('#clientFilter');
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
    
  // --------------------------------------------
  // On add new item, clear sidebar-right field fields
  // --------------------------------------------

  shift_start.prop('disabled', true);
  shift_end.prop('disabled', true);

  if (roaster_date.length) {
    var roaster_date_get = roaster_date.flatpickr({
      enableTime: false,
      altFormat: 'Y-m-d',
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      },
      onChange: function(selectedDates, dateStr, instance) {                
        shift_start.prop('disabled', false);   
        shift_start_get.set('minDate', moment(new Date(selectedDates)).format('YYYY-MM-DDTHH:mm:ss'));
        shift_start_get.set('maxDate', moment(new Date(selectedDates)).add(24, "h").format('YYYY-MM-DD')+'T23:59:00');

        shift_end.prop('disabled', false);        
        shift_end_get.set('minDate', moment(new Date(selectedDates)).format('YYYY-MM-DDTHH:mm:ss'));
        shift_end_get.set('maxDate', moment(new Date(selectedDates)).add(24, "h").format('YYYY-MM-DD')+'T23:59:00');    
      }
    });
  }  

  /*roaster_date.on('change' , function(){    
        shift_start.prop('disabled', false);   
        alert("val=",$('#roaster_date').value);     
        alert("date=",moment(new Date($('#roaster_date').value)).format('YYYY-MM-DD'));
        shift_start_get.set('minDate', moment(new Date(roaster_date.val())).format('YYYY-MM-DD'));
        shift_start_get.set('maxDate', moment(new Date(roaster_date.val())).add(24, "h").format('YYYY-MM-DD'));
        shift_end.prop('disabled', false);        
        shift_end_get.set('minDate', moment(new Date(roaster_date.val())).format('YYYY-MM-DD'));
        shift_end_get.set('maxDate', moment(new Date(roaster_date.val())).add(24, "h").format('YYYY-MM-DD'));           
  });*/


  $('.add-event button').on('click', function (e) {
    $('.event-sidebar').addClass('show');
    $('.sidebar-left').removeClass('show');
    $('.app-calendar .body-content-overlay').addClass('show');
  });
  
  $(document).on('change', '#client_id', function() {
    if ($(this).val()=='') 
    {
        $('#project_id').html('<option value="">Select Project</option>');
    }
    else
    {
        get_project(auth_id,$(this).val());
    }
  });

  function get_project(auth_id_get,client_value_get,project_value_get)
  {    
    $.ajax({
        type: 'get',
        url: '/get_project/'+auth_id_get+'/'+client_value_get,                    
        dataType: 'json',      //return data will be json
        success: function(data) {            
            var opt='<option value="">Select Project</option>';
            $.each(data, function(key,value) {
              opt+='<option data-label="'+value[`id`]+'" value="'+value[`id`]+'">'+value["pName"]+'</option>';
            });                        
            $('#project_id').html(opt);
            $("#project_id").val(project_value_get).trigger('change');            
        },
        error:function(){
            console.log("error");
        }
    });
  }


  $(document).on('change', '#employeeFilter', function () {        
    calendar.refetchEvents();
  });
  $(document).on('change', '#clientFilter', function () {    
    calendar.refetchEvents();
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
      altFormat: 'Y-m-dTH:i:S',
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
      altFormat: 'Y-m-dTH:i:S',
      time_24hr: true,
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }


  
  // Event click function
  function eventClick(info) {
    eventToUpdate = info.event;
    if (eventToUpdate.url) {
      info.jsEvent.preventDefault();
      window.open(eventToUpdate.url, '_blank');
    }

    sidebar.modal('show');
    addEventBtn.addClass('d-none');
    cancelBtn.addClass('d-none');
    copyEventBtn.removeClass('d-none');
    updateEventBtn.removeClass('d-none');
    btnDeleteEvent.removeClass('d-none');

    eventTitle.val(eventToUpdate.title);

    employee_id.val(eventToUpdate.extendedProps.employee_id).trigger('change');
    client_id.val(eventToUpdate.extendedProps.client_id).trigger('change');
    ratePerHour.val(eventToUpdate.extendedProps.ratePerHour);
    client_id.change(get_project(auth_id,eventToUpdate.extendedProps.client_id,eventToUpdate.extendedProps.project_id));
    roaster_date_get.setDate(eventToUpdate.extendedProps.roaster_date, true, 'Y-m-d');
    shift_start_get.setDate(eventToUpdate.extendedProps.shift_start, true, 'Y-m-dTH:i:S');
    shift_end_get.setDate(moment(new Date(eventToUpdate.extendedProps.shift_end)).format('YYYY-MM-DDTHH:mm:ss'), true, 'Y-m-dTH:i:S');
    // duration.val(eventToUpdate.extendedProps.duration);
    
    // amount.val(eventToUpdate.extendedProps.amount);
    job_type_id.val(eventToUpdate.extendedProps.job_type_id).trigger('change');
    roaster_status_id.val(eventToUpdate.extendedProps.roaster_status_id).trigger('change');
    roaster_type.val(eventToUpdate.extendedProps.roaster_type).trigger('change');    
    remarks.val(eventToUpdate.extendedProps.remarks);
    
    //  Delete Event
  }

  btnDeleteEvent.on('click', function () {      
      // eventToUpdate.remove();
      if(eventToUpdate.extendedProps.tid!='')
      {
        $.ajax({
            data: {id:eventToUpdate.extendedProps.tid,_token:$("input[name='_token']").val()},
            url: "/admin/home/calender_demo/delete",
            type: "delete",
            dataType: 'json',
            success: function (data) {              
              if(data['alert-type']=="success")
              {
                toastr['success']('👋 Deleted Successfully', 'Success!', {
                  closeButton: true,
                  tapToDismiss: false,              
                }); 
              }
              else
              {
                toastr['error']('👋 Not Deleted', 'Error!', {
                  closeButton: true,
                  tapToDismiss: false,              
                }); 
              }                           
            },
            error: function (data) {
              toastr['error']('👋 Not Deleted', 'Error!', {
                closeButton: true,
                tapToDismiss: false,              
              });                                     
            }
        });
      }
      
      calendar.refetchEvents();
      // removeEvent(eventToUpdate.id);
      sidebar.modal('hide');
      $('.event-sidebar').removeClass('show');
      $('.app-calendar .body-content-overlay').removeClass('show');
  });

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
        url: '/dataget',
        data:{
          'employeeFilter':employeeFilter.val().toString(),
          'clientFilter':clientFilter.val().toString(),
          'projectFilter':projectFilter.val().toString(),
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
    initialView: 'dayGridMonth',
    eventDisplay: 'block',
    displayEventTime: false,
    eventDidMount: function(info) {      
      $(info.el).tooltip({
        title: "<div class='text-left'>"+info.event.extendedProps.description+"</div>",     
        // title: info.event.extendedProps.calendar,                
        html: true,        
        placement: 'top',
        trigger: 'hover',
        container: 'body'
      });
    },    
    nextDayThreshold:'00:00:00',
    events: fetchEvents,
    editable: true,
    dragScroll: true,
    dayMaxEvents: 2,
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
        'bg-light-primary'
      ];
    },
    dateClick: function (info) {
      var date = moment(info.date).format('YYYY-MM-DD');
      resetValues();
      sidebar.modal('show');
      addEventBtn.removeClass('d-none');
      cancelBtn.removeClass('d-none');
      copyEventBtn.addClass('d-none');
      updateEventBtn.addClass('d-none');
      btnDeleteEvent.addClass('d-none');
      roaster_date_get.setDate(date, true, 'Y-m-d');      
    },
    eventClick: function (info) {
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
  if (eventForm.length) {
    eventForm.validate({
      errorPlacement: function(error, element) {
            // Unstyled checkboxes, radios
            if (element.parents().hasClass('form-check')) {
                error.appendTo( element.parents('.form-check').parent() );
            }
            // Input with icons and Select2
            else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo( element.parent() );
            }
            // Input group, styled file input
            else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                error.appendTo( element.parent().parent() );
            }
            // Other elements
            else {
                error.insertAfter(element);
            }
        },
      submitHandler: function (form, event) {
        event.preventDefault();
        if (eventForm.valid()) {
          sidebar.modal('hide');
        }
      },   
      rules: {   
        "employee_id":{
          required:true,
        },
        "client_id":{
          required:true,
        },
        "project_id":{
          required:true,
        },
        "roaster_date":{
          required:true,
        },        
        "shift_start":{
          required:true,          
          shift_start_check:function() { return [$('#roaster_date').val()]; },      
        },
        "shift_end":{
          required:true,          
          shift_end_check:function() { return [$('#roaster_date').val(),$('#shift_start').val()]; },        
        },
        "duration":{
          required:true,
        },
        "ratePerHour":{
          required:true,
        },
        "amount":{
          required:true,
        },
        "job_type_id":{
          required:true,
        },        
        "roaster_status_id":{
          required:true,
        },
        "roaster_type":{
          required:true,
        },
        
      },
      messages: {
        shift_start:{
          required:"Shift Start Required.",          
          shift_start_check:"Shift Start should be after Roaster.",
        },
        shift_end:{
          required:"Shift End Required.",          
          shift_end_check:"Shift End should be after Roaster.",
        },

      }
      
    });
  }
  
  jQuery.validator.addMethod("shift_start_check", function (value, element,param) {
      if (param[0]=="")
      {
        return false;
      }
      else
      {        
        if (new Date(param[0]+' 00:00:00') - new Date(value) > 0 ) {
            return false;
        } else {
            return true;
        }
      }
      
  },);
  jQuery.validator.addMethod("shift_end_check", function (value, element,param) {
      if (param[0]=="" && param[1]=="")
      {
        return false;
      }
      else
      {        
        if (new Date(param[0]+' 00:00:00') - new Date(value) > 0 ||  ((new Date(param[0]+' 00:00:00') - new Date(value)) == 0) ) 
        {          
            return false;
        }
        else if (new Date(param[1]) - new Date(value) > 0 || ((new Date(param[1]) - new Date(value)) == 0 ) )
        {
          return false;
        }
        else
        {          
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

  // Add new event
  $(addEventBtn).on('click', function () {
    if (eventForm.valid()) {
      $.ajax({
          data: $('#myCalendarForm').serialize(),
          url: "/admin/home/calender_demo/store",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            if(data['alert-type']=="success")
            {
              toastr['success']('👋 Added Successfully', 'Success!', {
                closeButton: true,
                tapToDismiss: false,              
              });
            }
            else
            {
              toastr['error']('👋 Not Added', 'Error!', {
                closeButton: true,
                tapToDismiss: false,              
              }); 
            }                           
          },
          error: function (data) {
            toastr['error']('👋 Not Added', 'Error!', {
              closeButton: true,
              tapToDismiss: false,              
            });                                        
          }
      });
      calendar.refetchEvents();    
      sidebar.modal('hide');
      $('.event-sidebar').removeClass('show');
      $('.app-calendar .body-content-overlay').removeClass('show');  
    }
  });

  // copy new event
  
  $(copyEventBtn).on('click', function () {
    if (eventForm.valid()) {
      $.ajax({
          data: $('#myCalendarForm').serialize(),
          url: "/admin/home/calender_demo/store",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            if(data['alert-type']=="success")
            {
              toastr['success']('👋 Added Successfully', 'Success!', {
                closeButton: true,
                tapToDismiss: false,              
              });
            }
            else
            {
              toastr['error']('👋 Not Added', 'Error!', {
                closeButton: true,
                tapToDismiss: false,              
              }); 
            }                           
          },
          error: function (data) {
            toastr['error']('👋 Not Added', 'Error!', {
              closeButton: true,
              tapToDismiss: false,              
            });                                        
          }
      });
      calendar.refetchEvents();    
      sidebar.modal('hide');
      $('.event-sidebar').removeClass('show');
      $('.app-calendar .body-content-overlay').removeClass('show');  
    }
  });

  // Update new event
  updateEventBtn.on('click', function () {
    if (eventForm.valid()) {
      $.ajax({
          data: 'id='+eventToUpdate.extendedProps.tid+'&'+$('#myCalendarForm').serialize(),
          url: "/admin/home/calender_demo/update",
          type: "POST",
          dataType: 'json',
          success: function (data) {            
            if(data['alert-type']=="success")
            {
              toastr['success']('👋 Updated Successfully', 'Success!', {
                closeButton: true,
                tapToDismiss: false,              
              }); 
            }
            else
            {
              toastr['error']('👋 Not Updated', 'Error!', {
                closeButton: true,
                tapToDismiss: false,              
              }); 
            }                         
          },
          error: function (data) {
            toastr['error']('👋 Not Updated', 'Error!', {
              closeButton: true,
              tapToDismiss: false,              
            });                                      
          }
      });
      calendar.refetchEvents();
      sidebar.modal('hide');
      $('.event-sidebar').removeClass('show');
      $('.app-calendar .body-content-overlay').removeClass('show');
    }
  });

  // Reset sidebar input values
  function resetValues() {    
    //form filed reset

    employee_id.val('').trigger('change');
    client_id.val('').trigger('change');
    project_id.val('').trigger('change');
    roaster_date.val('');
    shift_start.val('');
    shift_end.val('');
    duration.val('');
    ratePerHour.val('');
    amount.val('');
    job_type_id.val('').trigger('change');
    roaster_status_id.val('').trigger('change');
    roaster_type.val('').trigger('change');
    remarks.val('');
    //form filed reset
  }

  // When modal hides reset input values
  sidebar.on('hidden.bs.modal', function () {
    resetValues();
  });

  // Hide left sidebar if the right sidebar is open
  $('.btn-toggle-sidebar').on('click', function () {
    btnDeleteEvent.addClass('d-none');
    updateEventBtn.addClass('d-none');
    copyEventBtn.addClass('d-none');
    addEventBtn.removeClass('d-none');
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

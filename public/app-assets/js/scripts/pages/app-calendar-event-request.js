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
  var publish = $('#addToRoaster');
  var sendNotif = $('#sendNotification');
  var addEventBtn = $('#addEventBtn');
  var editEventBtn = $('#editEventBtn');
  var eventLabel = $('#select-label');
  var eventTitle = $('#title');
  var projectFilter = $('#projectFilter');
  //form id declar end



  var eventGuests = $('#event-guests');
  var selectAll = $('.select-all');
  var calEventFilter = $('.calendar-events-filter');
  var filterInput = $('.input-filter');

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
      fetchEvents();
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
    console.log('working');
    resetValues()
    $('.event-modal-title').html('Add Event')
    $('#editEventBtn').prop('hidden', true)
    $('#addEventBtn').prop('hidden', false)

    $('#addEventModal').modal('show')
  });
  //event update modal
  $(document).on("click", ".editEvent", function(){
    $('.event-modal-title').html('Update Event')
    $('#editEventBtn').prop('hidden', false)
    $('#addEventBtn').prop('hidden', true)
    $('#addEventModal').modal('show')
    const info = $(this).data('row');
    eventClick(info);
    $("#eventClick").modal("hide")
  });

  $('#filterStatus').on('change', function () {
    showImployees($('#filterStatus').val())
  });


  //show imployee by status
  function showImployees(status) {
    eventToUpdate = window.info;
    ids = []
    totalId = []
    $('#checkAllID').prop('checked', false)
    event_id = window.info.extendedProps.id;
    let employees = window.info.extendedProps.employees;

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
        // status = 'badge badge-pill badge-light-success mr-1'
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
          <td class="` + status + `">${employee.status == 'Added'? '<button data-id="'+ employee.timekeeper_id +'" class="btn btn-danger btn-sm delete_roster" data-id="'+employee.id+'"><i class="ri-delete-back-2-line"></i></button>': employee.status}</td>
        </tr>
      `;
    }
    if (totalId == 0) {
      $("#checkAllID").prop('disabled', true)
    } else {
      $("#checkAllID").prop('disabled', false)
    }
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
  function eventClick(info, all={}) {
    // edit event value set
    // console.log(info.event.extendedProps.all_employees)
    resetValues()
    $('#event_id').val(info.extendedProps.id);
    $('#project_name').val(info.extendedProps.project_name).trigger('change');
    $('#event_date').val(info.extendedProps.event_date);
    $('#shift_start').val($.time(info.extendedProps.shift_start));
    $('#shift_end').val($.time(info.extendedProps.shift_end));
    $('#rate').val(info.extendedProps.rate);
    $('#remarks').val(info.extendedProps.remarks);
    $('#no_employee_required').val(info.extendedProps.no_employee_required);
    $('#job_type_name').val(info.extendedProps.job_type_name).trigger('change');

    // //event click modal
    // $('#eventName').html(info.extendedProps.project.pName).trigger('change');
    // $('#eventShift').html("Shift-Time: " + $.time(info.extendedProps.shift_start) + " to " + $.time(info.extendedProps.shift_end));
    // $('#eventRemarks').html(info.extendedProps.remarks);

    // //add to roaster
    // window.info = all;
    // $('#filterStatus').val('all')
    // showImployees('all')

    // // $("#eventClick").modal("show")

    if (eventToUpdate.url) {
      info.jsEvent.preventDefault();
      window.open(eventToUpdate.url, '_blank');
    }
    eventTitle.val(eventToUpdate.title);
    //  Delete Event
  }

  function deleteRoster(){
    let id = $(this).data('id');
    console.log($(this));
    $(this).attr('disabled', '');
    $.ajax({
      url: '/admin/home/report/delete/' + id,
      type: 'GET',
      success: function(data) {
        if (data.notification) {
          toastr.success(data.notification)
        }
      },
      complete: function() {
        fetchEvents('current', '/admin/home/event/weekly').then(function() {
          add_employee_button(this, window.event_id);
          console.log(window.event_id);
        }).catch(function(error) {
          // Handle error if fetchEvents fails
          console.error('Error fetching events:', error);
        });
      }
    });
  }
  $(document).on('click', '.delete_roster', deleteRoster);

  const add_employee_button = function(e, event_id = null){
    const id = event_id ? event_id : $(this).data('id');
    window.event_id = id;
    let events = window.events;
    let event = getOnlyArray(id);
    window.info = event[0];
    console.log(window.info);

    //event click modal
    $('#eventName').html(info.extendedProps.project.pName).trigger('change');
    $('#eventShift').html("Shift-Time: " + $.time(info.extendedProps.shift_start) + " to " + $.time(info.extendedProps.shift_end));
    $('#eventRemarks').html(info.extendedProps.remarks);

    //add to roaster
    $('#filterStatus').val('all')
    showImployees('all')

    !event_id ? $("#eventClick").modal("show"):'';
    function getOnlyArray(id) {
      return events.filter((s) => s.id === id)
    }
  }
  $(document).on('click', '.add_employee', add_employee_button);
    


  $('#prev').on('click', ()=>{
    fetchEvents('previous', '/admin/home/event/weekly');
  });

  $('#next').on('click', ()=>{
    fetchEvents('next', '/admin/home/event/weekly');
  })

  // --------------------------------------------------------------------------------------------------
  // AXIOS: fetchEvents
  // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
  // --------------------------------------------------------------------------------------------------
  function fetchEvents(goto = '', address = '/admin/home/event/search') {
    return new Promise(function(resolve, reject) {
      $.ajax({
        url: address,
        data: {
          'goto': goto,
          'projectFilter': projectFilter.val(),
        },
        type: 'GET',
        success: function(results) {
          $('#currentWeek').html(results.date_between);
          const events = results.events;
          window.events = results.events;
          dataEntries(events);
          resolve(); // Resolve the promise when the AJAX request is successful
        },
        error: function(error) {
          console.log(error);
          reject(error); // Reject the promise if there's an error
        }
      });
    });
  }

  fetchEvents();

  function dataEntries(events){
    let html = '';
    const url = $('meta[name="site_url"]').attr('content')
    if(events.length){
      events.forEach(function(event){
        var profile = '';
        var employee_length = 0;
        event.employees.forEach(function(employee){
          if(employee.status == 'Added'){
            var image = '';
            if(employee.image){
              image = `<img src="https://api.eazytask.au/${employee.image}" alt="" class="rounded-circle img-fluid w-100 h-100">`;
            }else{
              image = `<img src="${url}images/app/no-image.png" alt="" class="rounded-circle img-fluid">`;
            }
            profile +=`
              <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" aria-label="${employee.fname} ${employee.mname} ${employee.lname}" data-bs-original-title="${employee.fname} ${employee.mname} ${employee.lname}">
                <div class="avatar-xxs">
                  ${image}
                </div>
              </a>`;
            employee_length++
          }
        });

        var menu = '<span class="text-danger">Expired</span>';
        var add_employee = '';
        const props = event.extendedProps;
        const { employees, ...otherProps } = props;
        var raw_data = {id: event.id, title: event.title, extendedProps: otherProps}

        if(event.extendedProps.latest){
          menu = `
            <div class="dropdown">
              <button class="btn btn-link text-muted p-1 mt-n2 py-0 text-decoration-none fs-15 material-shadow-none" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal icon-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
              </button>

              <div class="dropdown-menu dropdown-menu-end">
                <button class='dropdown-item editEvent' data-row='${JSON.stringify(raw_data)}'>
                  <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                </button>
                <button class="dropdown-item deleteEvent" data-id="${event.id}">
                  <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                </button>
              </div>
            </div>`;
          add_employee = `
            <a href="javascript: void(0);" data-id='${event.id}' class="avatar-group-item material-shadow add_employee" data-bs-toggle="tooltip"  data-bs-trigger="hover" data-bs-placement="top" data-bs-original-title="Add Members">
              <div class="avatar-xxs">
                <div class="avatar-title fs-16 rounded-circle bg-light border-dashed border text-primary">
                    +
                </div>
              </div>
            </a>
          `;
        };

        html += `
        <div class="col-xxl-3 col-lg-3 col-md-4 col-sm-6 project-card ${(moment(event.start).format('dddd').substring(0,3)).toLowerCase()}">
          <div class="card card-height-100">
            <div class="card-body">
              <div class="d-flex flex-column h-100">
                <div class="d-flex">
                  <div class="flex-grow-1">
                    <p class="text-muted mb-4">${event.cname}</p>
                  </div>
                  <div class="flex-shrink-0">
                    <div class="d-flex gap-1 align-items-center">
                      ${menu}
                    </div>
                  </div>
                </div>
                <div class="d-flex mb-2">
                  <div class="flex-grow-1">
                    <h5 class="mb-1 fs-15">${event.title}</h5>
                    <p class="text-muted text-truncate-two-lines mb-3">
                      ${event.project_address}
                    </p>
                  </div>
                </div>
                <div class="mt-auto">

                  <div class="d-flex mb-2">
                    <div class="flex-grow-1">
                      <div>Employees</div>
                    </div>
                    <div class="flex-shrink-0">
                      <div><i class="ri-list-check align-bottom me-1 text-muted"></i> ${employee_length}/${event.extendedProps.no_employee_required}</div>
                    </div>
                  </div>
                  <div class="progress progress-sm animated-progress">
                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="${employee_length}" aria-valuemin="0" aria-valuemax="${event.extendedProps.no_employee_required}" style="width: ${(employee_length/event.extendedProps.no_employee_required)*100}%;"></div><!-- /.progress-bar -->
                  </div><!-- /.progress -->
                  <div class="pt-2">${moment(event.start).format('ll')} (${moment(event.extendedProps.shift_start).format('HH:mm')} to ${moment(event.extendedProps.shift_end).format('HH:mm')})</div>
                </div>
              </div>

            </div>
            <!-- end card body -->
            <div class="card-footer bg-transparent border-top-dashed py-2">
              <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                  <div class="avatar-group">
                    ${profile}
                    ${add_employee}
                  </div>
                </div>
                <div class="flex-shrink-0">
                  <div class="text-muted">
                    <i class="ri-calendar-event-fill me-1 align-bottom"></i> ${moment(event.start).format('dddd')}
                  </div>
                </div>
              </div>
            </div>
            <!-- end card footer -->
          </div>
          <!-- end card -->
        </div>`;
      });
    }else{
      html += '<h4 class="text-center text-muted">Event Not Found!</h4>';
    }
    $('#events').html(html);
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
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
        fetchEvents('current', '/admin/home/event/weekly');
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
        fetchEvents();
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
          fetchEvents('current', '/admin/home/event/weekly');
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
          fetchEvents('current', '/admin/home/event/weekly');
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
  $('.deleteEvent').on('click', function () {
    const event_id = $(this).data("id")
    $.ajax({
      url: '/admin/home/upcomingevent/delete/' + event_id,
      type: 'GET',
      success: function (data) {
        fetchEvents('current', '/admin/home/event/weekly');
        toastr['success']('👋 delete Successfully', 'Success!', {
          closeButton: true,
          tapToDismiss: false,
        });
      }
    })
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
      fetchEvents();
    });
  }

  if (filterInput.length) {
    filterInput.on('change', function () {
      $('.input-filter:checked').length < calEventFilter.find('input').length
        ? selectAll.prop('checked', false)
        : selectAll.prop('checked', true);
      fetchEvents();
    });
  }
});

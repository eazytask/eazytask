@extends('layouts.Admin.master')
@section('admincontent')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-lg-12 col-md-12 p-0">
        <div id="myModalDetail" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Create New Post</h2>
                <hr>
                <form action="{{ url('home/messages') }}" method="POST">
                    @csrf
                    <label for="postTitle">Heading:</label><br>
                    <input type="text" class="form-control" id="postTitle" name="heading" required><br>
                    <label for="postContent">Text:</label><br>
                    <textarea id="postContent" class="form-control" name="text" rows="5" required></textarea><br>
                    <label for="postTitle">Need Confirm:</label><br>
                    <select name="need_confirm" id="need_confirm" class="form-control">
                        <option value="N">No</option>
                        <option value="Y">Yes</option>
                    </select><br>
                    <label for="selectVenue">Selected Venue/Sites:</label>
                    <select data-placeholder="Begin typing a name to filter" multiple class="chosen-select"
                        name="list_venue[]">
                        <option value="all" selected>All Venue</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->pName }}</option>
                        @endforeach
                    </select>
                    <br>
                    <br>
                    <input type="submit" class="btn btn-primary" value="Create Post">
                </form>
            </div>
        </div>

        <div id="myModalEdit" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Edit Post</h2>
                <hr>
                <form action="{{ url('home/messages/update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="replymessage_id" name="message_id">
                    <label for="postTitle">Heading:</label><br>
                    <input type="text" class="form-control" id="replypostTitle" name="heading" required><br>
                    <label for="postContent">Text:</label><br>
                    <textarea id="replypostContent" class="form-control" name="text" rows="5" required></textarea><br>
                    <label for="postTitle">Need Confirm:</label><br>
                    <select name="need_confirm" id="replyneed_confirm" class="form-control">
                        <option value="N">No</option>
                        <option value="Y">Yes</option>
                    </select><br>
                    <label for="selectVenue">Select Venue/Sites:</label>
                    <b id="replyCurrentListVenue"></b>
                    <select data-placeholder="Begin typing a name to filter" multiple class="chosen-select"
                        name="list_venue[]" id="replySelectVenue">
                        <option value="all">All Venue</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->pName }}</option>
                        @endforeach
                    </select>
                    <br>
                    <br>
                    <input type="submit" class="btn btn-primary" value="Update Post">
                </form>
            </div>
        </div>

        <div id="myModalReplyEdit" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Edit Reply</h2>
                <hr>
                <form action="{{ url('home/messages/update-reply') }}" method="POST">
                    @csrf
                    <input type="hidden" id="itemreplymessage_id" name="message_id">
                    <label for="postContent">Text:</label><br>
                    <textarea id="itemreplypostContent" class="form-control" name="text" rows="5" required></textarea><br>
                    <br>
                    <input type="submit" class="btn btn-primary" value="Update Post">
                </form>
            </div>
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content post-modal">
                <span class="close" onclick="closeModal()">&times;</span>
                <div class="post-content">
                    <h2 id="modalTitle"></h2>
                    <p id="modalDescription"></p>
                </div>

                <div class="horizontal-line"></div>
                <br>
                <h5>Comments:</h5>
                <ul id="modalReplies"></ul>
                <div class="reply-form">
                    <h5>Reply:</h5>
                    <form id="replyForm" action="{{ url('home/messages/reply') }}" method="POST">
                        @csrf
                        <input type="hidden" name="message_id" id="reply_message_id">
                        <textarea name="text" id="replyContent" class="form-control" rows="3" required></textarea><br><br>
                        <input type="submit" class="btn btn-primary" value="Submit Reply">

                        <button type="button" style="float: right;" id="confirmationButton" class="btn btn-info">
                            <i data-feather="check-circle"></i> Confirm
                        </button>
                    </form>

                </div>
            </div>
        </div>

        <form id="deleteForm" action="{{ url('home/messages/destroy') }}" method="POST">
            @csrf
            <input type="hidden" name="message_id" id="delete_message_id">
        </form>

        <form id="deleteReplyForm" action="{{ url('home/messages/destroy-reply') }}" method="POST">
            @csrf
            <input type="hidden" name="message_id" id="delete_reply_message_id">
        </form>

        <div class="card p-0">
            <div class="container p-0">
                <div class="card-header text-primary border-top-0 border-left-0 border-right-0">
                    <h3 class="card-title text-primary d-inline">
                        Messages
                    </h3>

                    @if (!auth()->user()->company_roles->contains('role', 3))
                        <span class="float-right">
                            <button class="create-post-btn btn btn-primary" onclick="openModal()">Create New Post</button>
                        </span>
                    @endif

                </div>
                <div class="card-body pb-0">

                </div>
                <div class="card-body pt-0 pb-0">
                    <div class="row row-xs">

                    </div>
                </div>
            </div>

            <div class="row" id="table-hover-animation">
                <div class="col-12">
                    <div class="card">
                        <div class="container">
                            <div id="postList">
                                <!-- This is where the posts will be dynamically added -->
                            </div>

                            <div class="pagination">
                                <!-- Updated pagination links with IDs -->
                                <!-- ... Your existing pagination links ... -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet" />

    <script>
        // Function to handle confirmation button click event
        function handleConfirmation() {
            var button = document.getElementById('confirmationButton');
            var the_message_id = document.getElementById('reply_message_id').value;

            // Check if the button is already confirmed
            if (button.classList.contains('confirmed')) {
                // Send AJAX request to unconfirm
                $.ajax({
                    url: "{{ url('home/messages/unconfirm') }}",
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Include the CSRF token in headers
                    },
                    data: {
                        message_id: the_message_id,
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update button appearance
                            button.classList.remove('confirmed');
                            button.classList.remove('btn-success');
                            button.classList.add('btn-info');
                            button.innerHTML =
                                `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Confirm`;
                            window.location.reload();
                        }
                    },
                    error: function(err) {
                        console.log(err)
                    }
                });
            } else {
                // Send AJAX request to confirm
                $.ajax({
                    url: "{{ url('home/messages/confirm') }}",
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Include the CSRF token in headers
                    },
                    data: {
                        message_id: the_message_id,
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update button appearance
                            button.classList.add('confirmed');
                            button.classList.remove('btn-info');
                            button.classList.add('btn-success');
                            button.textContent = 'Confirmed';
                            window.location.reload();
                        }
                    },
                    error: function(err) {
                        console.log(err)
                    }
                });
            }
        }

        // Function to send AJAX request
        function ajaxRequest(method, url, callback) {
            var xhr = new XMLHttpRequest();
            xhr.open(method, url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    callback(response);
                }
            };
            xhr.send();
        }

        // Attach click event listener to the button
        document.getElementById('confirmationButton').addEventListener('click', handleConfirmation);


        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        })
        // This is just a dummy data for demonstration purposes
        var posts = @json($messages)

        // Function to generate the HTML for each post
        function generatePostHTML(post) {
            return `<div class="post">
                <h3>${post.heading}</h3>
                <p>${post.text}</p>
              </div>`;
        }
        console.log(posts)

        function generatePostRowHTML(post) {
            var initials = post.fullname.match(/\b(\w)/g).join('').toUpperCase();
            var description = post.text.length > 100 ? post.text.substring(0, 100) + '...' : post.text;

            if (post.purposes && post.purposes.length > 0) {
                purpose = post.purposes.join(", ");
            } else {
                purpose = "All Venue";
            }
            var user_id = '{{ auth()->user()->id }}';
            let actionButton = ``;
            if (post.user_id == user_id) {
                actionButton = `<button type="button" class="btn btn-primary btn-sm" onclick="editMessage('${post.need_confirm}', '${post.my_confirm}', '${post.id}', '${post.heading}', '${post.text}', '${encodeURIComponent(JSON.stringify(post.list_venue))}', '${encodeURIComponent(JSON.stringify(post.purposes))}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteMessage('${post.id}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                    </button>
                    <br><br>`;
            }

            return `<div class="post-row">
                <div class="initials">${initials}</div>
                <div class="post-details" onclick="openPostModal('${post.need_confirm}', '${post.my_confirm}', '${post.id}', '${post.heading}', '${post.text}', '${encodeURIComponent(JSON.stringify(post.replies))}')">
                <div class="fullname">${post.fullname}</div>
                <div class="timestamp-purpose">
                    <div class="timestamp">${post.publish_date} to <b>${purpose}</b></div>
                </div>
                ${description}
                </div>
                <div class="replies">
                    ` + actionButton + `
                    Replies: ${post.replies.length}
                </div>
            </div>`;
        }

        // Function to render the posts on the page
        var postsPerPage = 5;

        function renderPosts(page) {

            var startIndex = (page - 1) * postsPerPage;
            var endIndex = startIndex + postsPerPage;

            var postList = document.getElementById("postList");
            postList.innerHTML = "";

            for (var i = startIndex; i < endIndex && i < posts.length; i++) {
                var postRowHTML = generatePostRowHTML(posts[i]);
                postList.innerHTML += postRowHTML;
            }
        }

        // Function to open the post modal
        function openPostModal(need_confirm, my_confirm, id, title, description, encodedReplies) {
            const decodedReplies = decodeURIComponent(encodedReplies);
            const replies = JSON.parse(decodedReplies);
            var modal = document.getElementById("myModal");
            var modalTitle = document.getElementById("modalTitle");
            var modalDescription = document.getElementById("modalDescription");
            var modalReplies = document.getElementById("modalReplies");
            var replyForm = document.getElementById("replyForm");
            var button = document.getElementById('confirmationButton');

            document.getElementById('reply_message_id').value = id;
            modalTitle.textContent = title;
            modalDescription.textContent = description;

            // Clear previous replies
            modalReplies.innerHTML = "";

            // Populate the list of replies
            for (var i = 0; i < replies.length; i++) {
                var replyItemHTML = generateReplyItemHTML(replies[i]);
                modalReplies.innerHTML += replyItemHTML;
            }

            // Show the reply form
            replyForm.style.display = "block";

            modal.style.display = "block";

            if (need_confirm == 'Y') {
                button.style.display = 'block';
                if (my_confirm == 'true') {
                    button.classList.add('confirmed');
                    button.classList.remove('btn-info');
                    button.classList.add('btn-success');
                    button.textContent = 'Confirmed';
                } else {
                    var button = document.getElementById('confirmationButton');
                    button.classList.remove('confirmed');
                    button.classList.remove('btn-success');
                    button.classList.add('btn-info');
                    button.innerHTML =
                        `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Confirm`;
                }
            } else {
                button.style.display = 'none';
            }
        }

        function editMessage(need_confirm, my_confirm, id, title, description, list_venue_json, list_purposes_json) {
            let list_venue = decodeURIComponent(list_venue_json);
            let venues = JSON.parse(list_venue);
            let list_purposes = decodeURIComponent(list_purposes_json);
            let purposes = JSON.parse(list_purposes);
            var modal = document.getElementById("myModalEdit");
            var replypostTitle = document.getElementById("replypostTitle");
            var replypostContent = document.getElementById("replypostContent");
            var replySelectVenue = $("#replySelectVenue");
            var replyneed_confirm = document.getElementById("replyneed_confirm");
            var replymessage_id = document.getElementById('replymessage_id');

            replypostTitle.value = title;
            replypostContent.value = description;
            replyneed_confirm.value = need_confirm;
            replymessage_id.value = id;

            // Clear existing selections
            replySelectVenue.val('').trigger("chosen:updated");

            if (purposes && purposes.length > 0) {
                list_purposes = purposes.join(", ");
            } else {
                list_purposes = "All Venue";
            }

            replyCurrentListVenue.textContent = list_purposes;

            modal.style.display = "block";
        }

        function deleteMessage(id) {
            var delete_message_id = document.getElementById('delete_message_id');

            delete_message_id.value = id;

            $('#deleteForm').submit();
        }

        function editReplyMessage(id, title, description) {
            var modal = document.getElementById("myModalReplyEdit");
            var itemreplypostContent = document.getElementById("itemreplypostContent");
            var itemreplymessage_id = document.getElementById('itemreplymessage_id');

            itemreplypostContent.value = description;
            itemreplymessage_id.value = id;

            modal.style.display = "block";
            modal.style.zIndex = "9999999999";
        }

        function deleteReplyMessage(id) {
            var delete_reply_message_id = document.getElementById('delete_reply_message_id');

            delete_reply_message_id.value = id;

            $('#deleteReplyForm').submit();
        }

        function generateReplyItemHTML(reply) {
            var initials = reply.fullname.match(/\b(\w)/g).join('').toUpperCase();
            var user_id = '{{ auth()->user()->id }}';
            let actionButton = ``;
            if (reply.user_id == user_id) {
                actionButton = `<button type="button" class="btn btn-primary btn-sm" onclick="editReplyMessage('${reply.id}', '${reply.heading}', '${reply.text}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteReplyMessage('${reply.id}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                    </button>
                    <br><br>`;
            }

            return `<div class="post-row">
                <div class="initials">${initials}</div>
                <div class="post-details">
                  <div class="fullname">${reply.fullname}</div>
                  <div class="timestamp-purpose">
                    <div class="timestamp">${reply.publish_date}</div>
                  </div>
                    ${reply.text}
                </div>
                    <div class="replies">
                    ` + actionButton + `
                </div>
              </div>`;
        }

        // Submit form to reply to a post
        var replyForm = document.getElementById("replyForm");
        replyForm.addEventListener("submit", function(event) {
            // event.preventDefault();

            // var replyContent = document.getElementById("replyContent").value;

            // Add the new reply to the post's replies array
            // var postIndex = 0; // Replace this with the actual index of the post
            // posts[postIndex].replies.push({
            //     fullname: "Your Full Name", // Replace with the actual full name
            //     content: replyContent,
            //     timestamp: new Date().toLocaleString() // Replace with the appropriate timestamp format
            // });

            // // Clear the reply form field
            // document.getElementById("replyContent").value = "";

            // // Re-render the posts to update the list
            // renderPosts(1);
        });

        // Initially render the first page
        renderPosts(1);

        function calculatePageCount() {
            return Math.ceil(posts.length / postsPerPage);
        }

        // Update the pagination links based on the number of pages
        function updatePaginationLinks() {
            var pageCount = calculatePageCount();
            var paginationLinks = "";

            for (var i = 1; i <= pageCount; i++) {
                paginationLinks += `<a href="#" id="page${i}" onclick="handlePageClick(${i})">${i}</a>`;
            }

            var paginationContainer = document.getElementsByClassName("pagination")[0];
            paginationContainer.innerHTML = paginationLinks;
        }

        // Initially render the posts and update the pagination links
        renderPosts(1);
        updatePaginationLinks();
        // Modal related functions
        var modal = document.getElementById("myModalDetail");

        var modal_create = document.getElementById("myModal");

        var modal_edit = document.getElementById("myModalEdit");

        var modal_reply_edit = document.getElementById("myModalReplyEdit");

        function openModal() {
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
            modal_create.style.display = "none";
            modal_edit.style.display = "none";
            modal_reply_edit.style.display = "none";
        }

        // Submit form to create a new post
        // var createPostForm = document.getElementById("createPostForm");
        // createPostForm.addEventListener("submit", function(event) {
        //     event.preventDefault();

        //     var postTitle = document.getElementById("postTitle").value;
        //     var postContent = document.getElementById("postContent").value;

        //     // Create a new post object
        //     var newPost = {
        //         title: postTitle,
        //         content: postContent,
        //     };

        //     // Add the new post to the posts array
        //     posts.push(newPost);

        //     // Clear the form fields
        //     document.getElementById("postTitle").value = "";
        //     document.getElementById("postContent").value = "";

        //     // Close the modal
        //     closeModal();

        //     // Re-render the posts to update the list
        //     renderPosts(1);
        //     updatePaginationLinks();
        // });

        function handlePageClick(page) {
            renderPosts(page);

            // Update active class for pagination links
            var paginationLinks = document.getElementsByClassName("pagination")[0].getElementsByTagName("a");
            for (var i = 0; i < paginationLinks.length; i++) {
                paginationLinks[i].classList.remove("active");
            }
            var activeLink = document.getElementById("page" + page);
            activeLink.classList.add("active");
        }

        // Add click event listeners to pagination links
        var paginationLinks = document.getElementsByClassName("pagination")[0].getElementsByTagName("a");
        for (var i = 0; i < paginationLinks.length; i++) {
            paginationLinks[i].addEventListener("click", function(event) {
                event.preventDefault();
                var page = parseInt(this.innerHTML);
                handlePageClick(page);
            });
        }
    </script>
    <script type="text/javascript">
        $('#prev').on('click', function() {
            searchNow('previous')
        })
        $('#next').on('click', function() {
            searchNow('next')
        })

        $('#project').on('change', function() {
            // alert($(this).val())
            if ($(this).val()) {
                $('#download').prop('disabled', false)
            } else {
                $('#download').prop('disabled', true)
            }
            searchNow('current')
        })

        function searchNow(goTo = '', search_date = null) {
            $.ajax({
                url: '/admin/home/schedule/status/search',
                type: 'get',
                dataType: 'json',
                data: {
                    'go_to': goTo,
                    'project': $('#project').val(),
                    'search_date': search_date,
                },
                success: function(data) {
                    // $("#myTable").DataTable();
                    if (data.search_date) {
                        $("#search_date").val(moment(data.search_date).format('DD-MM-YYYY'))
                    } else {
                        $("#search_date").val('')
                    }
                    $('#myTable').DataTable().clear().destroy();
                    $('#tBody').html(data.data);
                    $('#myTable').DataTable({
                        dom: 'Bfrtip',
                        paging: false,
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                        ],
                        autoWidth: false, //step 1
                        columnDefs: [
                            // { width: '140px', targets: 0 }, //step 2, column 1 out of 4
                            {
                                width: '125px',
                                targets: 1
                            }, //step 2, column 2 out of 4
                            {
                                width: '125px',
                                targets: 2
                            }, //step 2, column 3 out of 4
                            {
                                width: '125px',
                                targets: 3
                            }, //step 2, column 3 out of 4
                            {
                                width: '125px',
                                targets: 4
                            }, //step 2, column 3 out of 4
                            {
                                width: '125px',
                                targets: 5
                            }, //step 2, column 3 out of 4
                            {
                                width: '125px',
                                targets: 6
                            }, //step 2, column 3 out of 4
                            {
                                width: '125px',
                                targets: 7
                            }, //step 2, column 3 out of 4
                        ]
                        // "bDestroy": true
                    });
                    feather.replace({
                        width: 14,
                        height: 14
                    });
                    $('#currentWeek').text(data.week_date)
                    $('#total_hours').html('Total Hours: ' + data.hours);
                    $('#total_amount').html('Total Amount: $' + data.amount);

                    if (data.notification) {
                        toastr.success(data.notification)
                    }
                },
                error: function(err) {
                    console.log(err)
                }
            });
        }

        // function timekeeperEditFunc() {
        //     if ($("#timekeeperAddForm").valid()) {
        //         $.ajax({
        //             data: $('#timekeeperAddForm').serialize(),
        //             url: "/admin/home/sign/in/status/change",
        //             type: "POST",
        //             dataType: 'json',
        //             success: function(data) {
        //                 console.log(data)
        //                 $("#addTimeKeeper").modal("hide")
        //                 // $("#roasterClick").modal("hide")
        //                 searchNow('current')
        //                 toastr['success']('👋 Update Successfully', 'Success!', {
        //                     closeButton: true,
        //                     tapToDismiss: false,
        //                 });
        //             },
        //             error: function(data) {
        //                 console.log(data)
        //             }
        //         });
        //     }
        // }
    </script>

    @push('scripts')
        <script>
            $(document).ready(function() {
                searchNow()

                $('#search_date').on('change', function() {
                    searchNow('search_date', $('#search_date').val())
                })

                $(document).on('show.bs.modal', '.modal', function() {
                    const zIndex = 1040 + 10 * $('.modal:visible').length;
                    $(this).css('z-index', zIndex);
                    setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1)
                        .addClass('modal-stack'));
                });

                // var roaster_date, roaster_end, shift_start, shift_end;
                function timeToSeconds(time) {
                    time = time.split(/:/);
                    return time[0] * 3600 + time[1] * 60;
                }
                $.time = function(dateObject) {
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

                function allCalculation() {
                    var start = $("#app_start").val();
                    var end = $("#app_end").val();
                    var rate = $("#app_rate").val();

                    if (start && end) {
                        // calculate hours
                        var diff = (timeToSeconds(end) - timeToSeconds(start)) / 3600
                        if (diff < 0) {
                            diff = 24 - Math.abs(diff)
                        }
                        if (diff) {
                            $("#app_duration").val(diff);
                            if (rate) {
                                $("#app_amount").val(parseFloat(rate) * diff);
                            }
                        }

                    } else {
                        $("#app_duration").val('');
                        $("#app_amount").val('');
                    }
                }

                var isValid = true;
                var modalToTarget = document.getElementById("addTimeKeeper");

                function roasterEndTimeInit() {
                    $("#app_end").change(function() {
                        allCalculation()
                    });

                }
                roasterEndTimeInit()

                function roasterStartTimeInit() {
                    $("#app_start").change(function() {
                        if ($(this).val()) {
                            $("#app_end").removeAttr("disabled")
                        } else {
                            $("#app_end").prop('disabled', true);
                        }

                        allCalculation()
                    });

                }
                roasterStartTimeInit()

                const initDatePicker = () => {
                    $("#roaster_date").change(function() {
                        if ($(this).val()) {
                            $("#app_start").removeAttr("disabled")
                        } else {
                            $(".picker__button--clear").removeAttr("disabled")

                            $(".picker__button--clear")[1].click()
                            $(".picker__button--clear")[2].click()

                            $("#app_start").prop('disabled', true)

                            $("#app_end").prop('disabled', true);
                            allCalculation()
                        }
                    });
                }

                initDatePicker();
                const initAllDatePicker = () => {
                    initDatePicker();
                    roasterStartTimeInit();
                    roasterEndTimeInit();
                }
                $(document).on("click", ".editBtn", function() {
                    resetValue()
                    var rowData = $(this).data("row");

                    $("#timepeeper_id").val(rowData.id);
                    $("#employee_id").val(rowData.employee_id).trigger('change');
                    $("#project-select").val(rowData.project_id).trigger('change');
                    $("#roaster_date").val(moment(rowData.roaster_date).format('DD-MM-YYYY'))
                    $("#shift_start").val($.time(rowData.shift_start))
                    $("#shift_end").val($.time(rowData.shift_end))
                    if (rowData.sing_in) {
                        $("#sign_in").val($.time(rowData.sing_in))
                    } else {
                        $("#sign_in").val('unspecified')
                    }
                    if (rowData.sing_out) {
                        $("#sign_out").val($.time(rowData.sing_out))
                    } else {
                        $("#sign_out").val('unspecified')
                    }

                    if (rowData.is_approved == 1) {
                        $('.timekeer-btn').hide();
                    } else {
                        $('.timekeer-btn').show();
                    }

                    $("#app_start").val($.time(rowData.Approved_start_datetime))
                    $("#app_end").val($.time(rowData.Approved_end_datetime))

                    $("#app_rate").val(rowData.app_rate)
                    $("#app_duration").val(rowData.app_duration)
                    $("#app_amount").val(rowData.app_amount)
                    $("#job").val(rowData.job_type_id).trigger('change');
                    // $("#job").val(rowData.job_type_id)
                    // $("#roster").val(rowData.roaster_status_id)

                    $("#remarks").val(rowData.remarks)

                    initAllDatePicker();
                    allCalculation()
                    $("#addTimeKeeper").modal("show")
                })

                $(document).on("input", ".reactive", function() {
                    allCalculation()
                })

                function resetValue() {
                    $("#timepeeper_id").val();
                    $('#timepeeper_id').attr('value', '');
                    $("#employee_id").val('');
                    // $("#client-select").val('').trigger('change');

                    $("#roaster_date").val('')
                    $("#shift_start").val('')
                    $("#shift_end").val('')
                    $("#sign_in").val('')
                    $("#sign_out").val('')
                    $("#app_start").val('')
                    $("#app_end").val('')

                    $("#app_rate").val('')
                    $("#app_duration").val('')
                    $("#app_amount").val('')
                    $("#job").val('').trigger('change');
                    // $("#job").val('')
                    // $("#roster").val('')

                    $("#remarks").val('')
                    $("#project-select").val('');
                }


                timekeeperEditFunc = function() {
                    if ($("#timekeeperAddForm").valid()) {
                        $.ajax({
                            data: $('#timekeeperAddForm').serialize(),
                            url: "/admin/home/shift/approve",
                            type: "POST",
                            dataType: 'json',
                            success: function(data) {
                                $("#addTimeKeeper").modal("hide")
                                // $("#roasterClick").modal("hide")
                                searchNow('current')
                                toastr['success']('👋 Successfully Approved', 'Success!', {
                                    closeButton: true,
                                    tapToDismiss: false,
                                });
                            },
                            error: function(data) {
                                console.log(data)
                            }
                        });
                    }
                }
                approveAllFunc = function() {
                    $.ajax({
                        url: "/admin/home/shift/approve/week",
                        type: "get",
                        dataType: 'json',
                        data: {
                            'project': $('#project').val(),
                        },
                        success: function(data) {
                            $("#addTimeKeeper").modal("hide")
                            // $("#roasterClick").modal("hide")
                            searchNow('current')
                            toastr['success']('👋 All Approved Successfully', 'Success!', {
                                closeButton: true,
                                tapToDismiss: false,
                            });
                        },
                        error: function(data) {
                            console.log(data)
                        }
                    });
                }
            });

            $(window).on('load', function() {
                $(".approve").prop('hidden', false)
            });
        </script>
        <style>
            .dt-buttons {
                display: none !important;
            }

            #myTable {
                width: 1000px !important;
            }

            .font-small-2 {
                font-size: 0.7rem !important;
            }

            /* Styles for the modal */
            .modal {
                display: none;
                position: fixed;
                z-index: 999999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.4);
            }

            .modal-content {
                background-color: #fefefe;
                margin: 10% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                animation: modalOpenAnimation 0.3s ease-out;
            }

            @keyframes modalOpenAnimation {
                from {
                    opacity: 0;
                    transform: translateX(100%);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
                position: absolute;
                top: 10px;
                right: 10px;
            }

            .close:hover,
            .close:focus {
                color: #333;
                text-decoration: none;
            }

            /* end */

            /* Other styles */
            #postList {
                height: auto;
                overflow-y: scroll;
                border: 1px solid #ccc;
                padding: 10px;
            }

            .post {
                margin-bottom: 10px;
                padding: 10px;
                background-color: #f9f9f9;
                border: 1px solid #ddd;
            }

            .pagination {
                margin-top: 10px;
                text-align: center;
            }

            .pagination a {
                display: inline-block;
                padding: 5px 10px;
                margin-right: 5px;
                background-color: #f4f4f4;
                border: 1px solid #ccc;
                text-decoration: none;
                color: #333;
            }

            .pagination a.active {
                background-color: #ccc;
            }

            .create-post-btn {
                margin-bottom: 10px;
            }

            .post-row {
                cursor: pointer;
                padding: 10px;
                border: 1px solid #ddd;
                background-color: #f9f9f9;
                margin-bottom: 10px;
            }

            .post-row:hover {
                background-color: #f1f1f1;
            }

            .post-row .initials {
                display: inline-block;
                width: 40px;
                height: 40px;
                background-color: #ccc;
                border-radius: 50%;
                line-height: 40px;
                text-align: center;
                font-weight: bold;
                margin-right: 10px;
            }

            .post-row .fullname {
                font-weight: bold;
            }

            .post-row .timestamp {
                color: #888;
                font-size: 12px;
                margin-bottom: 5px;
            }

            .post-row .purpose {
                font-weight: bold;
            }

            .post-row .replies {
                color: #888;
                font-size: 12px;
            }

            .post-row .description {
                margin-top: 10px;
            }

            .post-modal .post-content {
                margin-bottom: 20px;
            }

            .post-modal .reply-form {
                margin-top: 20px;
            }

            .reply-item .initials {
                display: inline-block;
                width: 40px;
                height: 40px;
                background-color: #ccc;
                border-radius: 50%;
                line-height: 40px;
                text-align: center;
                font-weight: bold;
                margin-right: 10px;
            }

            .post-row {
                display: flex;
                align-items: center;
                cursor: pointer;
                padding: 10px;
                border: 1px solid #ddd;
                background-color: #f9f9f9;
                margin-bottom: 10px;
            }

            .post-row:hover {
                background-color: #f1f1f1;
            }

            .initials {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 40px;
                height: 40px;
                background-color: #ccc;
                border-radius: 50%;
                line-height: 40px;
                text-align: center;
                font-weight: bold;
                margin-right: 10px;
            }

            .post-details {
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .fullname {
                font-weight: bold;
            }

            .timestamp-purpose {
                display: flex;
                justify-content: space-between;
                align-items: center;
                color: #888;
                font-size: 12px;
                margin-top: 5px;
            }

            .horizontal-line {
                border-top: 1px solid #000;
                width: 100%;
            }

            .chosen-container {
                display: block;
                width: 100% !important;
            }
        </style>
    @endpush
@endsection

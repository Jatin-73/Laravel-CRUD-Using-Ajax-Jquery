<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel + AJAX</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <!-- Add Student Data Model -->
    <div class="modal fade" id="studentaddmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close_model">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--Strat Model Body  -->
                <div class="modal-body">

                    <!--Strat Student Add form  -->
                    <form id="addform">
                        @csrf
                        <span id="form_result"></span>
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" placeholder="Enter First Name">
                        </div>

                        <div class="form-group">
                            <label>last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" placeholder="Enter Last Name">
                        </div>

                        <div class="form-group">
                            <label>Course</label>
                            <input type="text" class="form-control" name="course" id="course" placeholder="Enter Course">
                        </div>

                        <div class="form-group">
                            <label>Section</label>
                            <input type="text" class="form-control" name="section" id="section" placeholder="Enter Section ">
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" name="action" id="action" value="Add" />
                            <input type="hidden" name="hidden_id" id="hidden_id" />
                            <button type="close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary act" id="action_button" >Save Student Details</button>
                        </div>
                    </form>
                    <!--End Student Add form -->

                </div>
                <!--End Model Body  -->
            </div>
        </div>
    </div>
    <!-- End Add Student Data Model -->

    <div class="container-fluid">
        <div class="jumbotron">
            <div class="row">
                <h1>Laravel CRUD + AJAX + JQuery using Bootstrap Model</h1>
                <button type="button" class="btn btn-primary" data-toggle="modal" id="save_add" data-target="#studentaddmodel" style="margin-bottom: 20px;">
                    Add Student
                </button>

                <table class="table table-dark" id="fetch_table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Course</th>
                            <th scope="col">Section</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $stu)
                        <tr class="students{{$stu->id}}">
                            <td>{{ $stu->id }}</td>
                            <td>{{ $stu->fname }}</td>
                            <td>{{ $stu->lname }}</td>
                            <td>{{ $stu->course }}</td>
                            <td>{{ $stu->section }}</td>
                            <td>
                                <a href="javascript:void(0)" class="edit-modal btn btn-info edit_student" id="{{ $stu->id }}">Edit</a>
                                <a href="javascript:void(0)" class="delete-modal btn btn-danger delete-student" id="delete-student" 
                                data-id="{{ $stu->id }}">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $students->links() }}
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery-3.5.1.js') }} "></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#save_add").click(function() {
                $('#form_result').html('');
                $('form').attr("id","addform");
                $('.modal-title').text('Add New Student');
                $('.act').attr("id","action_button");
                $('#action_button').html('Save Student');
                $('#action').val('Save Student');
                $('#addform').trigger("reset");
            });

            $('body').on('click', '.edit_student', function () {
                var id = $(this).attr('id');
                $('#form_result').html('');
                $('form').attr("id","update_student");
                $.ajax({
                    url: "/student/edit/" + id,
                    dataType: "json",
                    success: function(data) {
                        $('#fname').val(data.fname);
                        $('#lname').val(data.lname);
                        $('#course').val(data.course);
                        $('#section').val(data.section);
                        $('#hidden_id').val(id);
                        $('.modal-title').text('Edit Student');
                        $('.act').attr("id","act_btn");
                        $('#act_btn').html('Edit Student');
                        $('#action').val('Edit Student');
                        $('#studentaddmodel').modal('show');
                    }
                });
            });

            $('#addform').on('submit', function(event) {
                event.preventDefault();
                var action_url = '';

                if ($('#action').val() == 'Save Student') {
                    action_url = "{{ route('student.add') }}";
                }
                $.ajax({
                    type: "POST",
                    url: action_url,
                    dataType: "json",
                    data: $("#addform").serialize(),
                    success: function(response) {
                        var html = '';
                        if (response.errors) {
                            html = '<div class="alert alert-danger">';
                            for (var count = 0; count < response.errors.length; count++) {
                                html += '<li>' + response.errors[count] + '</li>';
                            }
                            html += '</div>';
                        } else {
                            msg = "Data Added Successfully.";
                            html = '<div class="alert alert-success">' + msg + '</div>';
                            $('#addform').trigger("reset");
                            var student = '<tr class="students' + response.id + '"><td>' + response.id + '</td><td>' + response.fname + 
                            '</td><td>' + response.lname + '</td><td>' + response.course + '</td><td>' + response.section + '</td>';

                            student += '<td><a href="javascript:void(0)" class="edit-modal btn btn-info edit_student" id="' + response.id + '">Edit</a>&nbsp;<a href="javascript:void(0)" class="delete-modal btn btn-danger delete-student" id="delete-student" data-id="' + response.id + '">Delete</a></td></tr>';

                            $('#fetch_table').prepend(student);
                        }
                        $('#form_result').html(html);
                    }
                });
            });

            $('body').on('click', '#act_btn', function (event) {
                event.preventDefault();
                var action_url = '';
                if ($('#action').val() == 'Edit Student') {
                    action_url = "{{ route('student.update') }}";
                }
                $.ajax({
                    type: "PUT",
                    url: action_url,
                    dataType: "json",
                    data: $("#update_student").serialize(),
                    success: function(response){
                        var html = '';
                        if (response)
                        {
                            msg = "Data Updated Successfully.";
                            html = '<div class="alert alert-success">' + msg + '</div>';
                            $('#update_student').trigger("reset");
                            $('.students' + response.id).replaceWith("<tr class='students" + response.id + "'>" +
                                "<td>" + response.id + "</td>" +
                                "<td>" + response.fname + "</td>" +
                                "<td>" + response.lname + "</td>" +
                                "<td>" + response.course + "</td>" +
                                "<td>" + response.section + "</td>" +
                                "<td> <a href='javascript:void(0)' class='edit-modal btn btn-info edit_student' id='" + response.id + "'>Edit</a>&nbsp<a href='javascript:void(0)' class='btn btn-danger delete-student' id='delete-student' data-id='" + response.id + "'>Delete</a></td>" +
                                "</tr>"); 
                        }
                        $('#form_result').html(html);
                    }
                });                
            });

            $('body').on('click', '.delete-student', function(){
                var student_id = $(this).data("id");

                if(confirm("Are You sure want to delete this student!")) {
                    $.ajax({
                        type: "DELETE",
                        url: "/studentdelete/" + student_id,
                        success: function (data) {
                            $('.students' + student_id).remove();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }   
            });
        });
    </script>
</body>
</html>
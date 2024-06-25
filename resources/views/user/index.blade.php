<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>DreamCast Project</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
 
 <style>
   .container{
    padding: 0.5%;
   } 
</style>
</head>
<body>

<div class="container">
    <h2 style="margin-top: 12px;" class="alert alert-primary">Users</h2><br>
    <div class="row">
        <div class="col-12">
          <a href="javascript:void(0)" class="btn btn-primary mb-2" id="create-new-user">Add User</a> 
          
          <table class="table table-bordered" id="laravel_crud">
           <thead>
              <tr>
                 <th>Name</th>
                 <th>Email</th>
                 <th>Phone</th>
                 <th>Description</th>
                 <th>Role</th>
                 <th>Profile Image</th> 
                 <td colspan="2">Action</td>
              </tr>
           </thead>
           
           <tbody id="posts-crud">
              @forelse($users as $user)
           
              <tr id="post_id_{{ $user->id }}">
                 <td>{{ $user->name ?? 'No Data' }}</td>
                 <td>{{ $user->email ?? 'No Data'}}</td>
                 <td>{{ $user->phone ?? 'No Data'}}</td>
                 <td>{{ $user->description ?? 'No Data'}}</td>
                 <td>{{ $user->Role->name}}</td>

                 <td> <img style = "height: 53px;" src="{{asset('file/'.$user->profile_image) }}"> </img> </td>
                 <td>
                  <a href="javascript:void(0)" id="delete-post" data-id="{{ $user->id }}" class="btn btn-danger delete-post">Delete</a></td>
              </tr>
              @empty
              <tr id="">
              <td></td>
              <td></td>
              <td></td>
                 <td >No Data Found</td>
                
              </tr>
              @endforelse
           </tbody>
          </table>
          
       </div> 
    </div>
</div>
<div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="postCrudModal"></h4>
    </div>


    <div class="modal-body">
        <form id="postForm" name="postForm" class="form-horizontal" enctype="multipart/form-data">
           <input type="hidden" name="post_id" id="post_id">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="">
                    <input type ="text" class="form-control" id="name" name="name" value="" >
                </div>
            </div>
              <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="">
                    <input type="email" class="form-control" id="email" name="email" value="" required>
                </div>
            </div>
 
            <div class="form-group">
                <label class="col-sm-2 control-label">Phone</label>
                <div class="">
                    <input class="form-control" id="phone" name="phone" value="" >
                </div>
            </div>

<div class="form-group">
         <div class="row">
         <div class="col-6">
         <label class="col-sm-6 control-label">Select Role</label>
         <select id="role_id" name="role_id" class="form-control">
        <option value="" selected>Select</option>
        @forelse ($role as $roles)
        <option value="{{$roles->id}}" >{{$roles->name ?? 'no data'}}</option>
        @empty
        <option value="admin">Admin</option>    
        @endforelse
      </select>          
                  </div>
                   <div class="col-6">
                  <label class="col-sm-6 control-label">Image</label>
      <input type="file" class="form-control-file" id="file" name="file">        
                  </div>
             
             </div>
             </div>

            <div class="form-group">
           <div class="form-group">
                <label class="col-sm-2 control-label">Description</label>
                <div class="">
                    <textarea class="form-control" id="description" name="description" value="" ></textarea>
                </div>
            </div>

            </div>
            <div class="col-sm-offset-2 col-sm-10">
             <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save
             </button>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        
    </div>
</div>
</div>
</div>
</body>
</html>
<script>
  $(document).ready(function () {
       $.validator.addMethod('indianPhone', function(value, element) {
        return this.optional(element) || /^[6-9]\d{9}$/.test(value);
    }, 'Please enter a valid Indian phone number.');



    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#create-new-user').click(function () {
        $('#btn-save').val("create-post");
        $('#postForm').trigger("reset");
        $('#postCrudModal').html("Add New User");
        $('#ajax-crud-modal').modal('show');
    });
 

    $('body').on('click', '.delete-post', function () {
        var post_id = $(this).data("id");
        confirm("Are You sure want to delete !");
 
        $.ajax({
            type: "DELETE",
            url: "api/users"+'/'+post_id,
            success: function (data) {
                $("#post_id_" + post_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });   
  });
 
if ($("#postForm").length > 0) {
    $("#postForm").validate({            

        submitHandler: function(form) {
            var formData = new FormData(form);   
            var actionType = $('#btn-save').val();
            $('#btn-save').html('Sending..');

            $.ajax({
                data: formData,
                url: "api/users",
                type: "POST",
                dataType: 'json',
                contentType: false, 
                processData: false, 

                success: function (data) {
                    var post = '<tr id="post_id_' + data.id + '">' + '<td>' + data.name + '</td>' + '<td>' + data.email + '</td>' + '<td>' + data.phone + '</td>' + '<td>' + data.description + '</td>' + '<td>' + data.role+ '</td><td><img style = "height: 53px;" src="' + '{{ asset('file') }}/' + data.profile_image + '" alt="Profile Image"></td>';
                    post += '<td><a href="javascript:void(0)" id="delete-post" data-id="' + data.id + '" class="btn btn-danger delete-post">Delete</a></td></tr>';

                    if (actionType == "create-post") {
                        $('#posts-crud').prepend(post);
                    } else {
                        $("#post_id_" + data.id).replaceWith(post);
                    }

                    $('#postForm').trigger("reset");
                    $('#ajax-crud-modal').modal('hide');
                    $('#btn-save').html('Save Changes');
                },
error: function (data) {
    var errors = data.responseJSON.errors;
    $('.text-danger').remove();
    $.each(errors, function (key, value) {
        $('#' + key).after('<div class="text-danger">' + value[0] + '</div>');
    });
    
    if ('name' in data.responseJSON) {
        $('#name').val(data.responseJSON.name);
    }
    if ('email' in data.responseJSON) {
        $('#email').val(data.responseJSON.email);
    }
    if ('phone' in data.responseJSON) {
        $('#phone').val(data.responseJSON.phone);
    }
    if ('role_id' in data.responseJSON) {
        $('#role_id').val(data.responseJSON.role_id);
    }
    if ('description' in data.responseJSON) {
        $('#description').val(data.responseJSON.description);
    }
    if ('file' in data.responseJSON) {
        $('#file').val(data.responseJSON.file);
    }
    
    $('#btn-save').html('Save Changes');
}


            });
        }
    });
}

   
  
</script>
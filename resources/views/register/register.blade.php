<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register User</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
</head>
<body>
    <div class="container master-register"><br>
        <div class="col-md-6 col-md-offset-3">
            <h2 class="text-center">FORM REGISTER USER</h3>
            <hr>
            @if(session('message'))
            <div class="alert alert-success">
                {{session('message')}}
            </div>
            @endif
            <h3 id="message"></h3>
            <form action="#" id="registerForm" method="post">
            @csrf
                <div class="form-group">
                    <label><i class="fa fa-user"></i> ID User</label>
                    <input type="text" name="id_user" id="id_user" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label><i class="fa fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required="">
                </div>
                <div class="form-group">
                    <label><i class="fa fa-user"></i> Username</label>
                    <input type="text" name="name"  id="name" class="form-control" placeholder="Username" required="">
                </div>
                <div class="form-group">
                    <label><i class="fa fa-key"></i> Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="">
                </div>
                <div class="form-group">
                   <label><i class="fa fa-address-book"></i> Role</label>
                   <select name="role" id="role" class="form-control">
                        <option value="AD">Admin</option>
                        <option value="ST">Staff</option>
                        <option value="GS">Guest</option>
                   </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-user"></i> Register</button>
                <hr>
                <p class="text-center">Sudah punya akun silahkan <a href="{{ route('login') }}">Login Disini!</a></p>
            </form>
        </div>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Set CSRF token in AJAX setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // ### Callbak user_id starter
    var role = "AD";
    $.ajax({
            url: '{{ route("generate_user_id") }}',
            type: 'GET',
            data: { role: role },
            success: function(response) {
                // Tampilkan user_id di input
                $('#id_user').val(response.user_id);
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
    });

    // ### Mengambil Nilai Roles Callback
    $('#role').change(function() {
        var role = $(this).val();
        $.ajax({
            url: '{{ route("generate_user_id") }}',
            type: 'GET',
            data: { role: role },
            success: function(response) {
                // Tampilkan user_id di input
                $('#id_user').val(response.user_id);
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        });
    });

    // ###Submit Form
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route('actionregister') }}', // Update with the actual route name
            type: 'POST',
            data: {
                name: $('#name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                role: $('#role').val()
            },
            success: function(response) {
                $('#message').html('<p>' + response.pesan + '</p>');
                if (response.pesan === 'Register Berhasil. Akun Anda sudah Aktif.') {
                    $('#registerForm')[0].reset();
                    var role = "AD";
                    $.ajax({
                            url: '{{ route("generate_user_id") }}',
                            type: 'GET',
                            data: { role: role },
                            success: function(response) {
                                // Tampilkan user_id di input
                                $('#id_user').val(response.user_id);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error: ' + error);
                            }
                    });
                }
            },
            error: function(response) {
                console.error(response.responseJSON.pesan);
                $('#message').html('<p>' + response.responseJSON.pesan + '</p>');
            }
        });
    });
});
</script>

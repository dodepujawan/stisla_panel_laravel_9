<div class="container master-edit-register"><br>
    <div class="col-md-6 col-md-offset-3">
        <h2 class="text-center">FORM EDIT USER</h2>
        <hr>
        @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif
        <h3 id="message"></h3>
        <form action="#" id="editListRegisterForm" method="post">
            @csrf
            <input type="text" name="id" id="id" class="form-control" value="{{ $user->id }}" required="" readonly>
            <div class="form-group">
                <label><i class="fa fa-envelope"></i> Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required="">
            </div>
            <div class="form-group">
                <label><i class="fa fa-user"></i> Username</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required="">
            </div>
            <div class="form-group">
                <label><i class="fa fa-key"></i> Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>
            <div class="form-group">
                <label><i class="fa fa-address-book"></i> Role</label>
                <input type="text" name="roles" id="roles" class="form-control" value="{{ $user->roles }}" readonly>
            </div>
            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-user"></i> Update</button>
            <hr>
            <p class="text-center">Kembali ke <a href="#">Dashboard</a></p>
        </form>
    </div>
</div>

{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
$(document).ready(function(){

    $(document).on('submit', '#editListRegisterForm', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('update_list_register') }}', // Route to handle form submission
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.pesan === 'Update Berhasil.') {
                    // Tampilkan notifikasi SweetAlert2
                    Swal.fire({
                        title: 'Success!',
                        text: 'Update Berhasil.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                      reloadContent();
                    });
                }
            },
            error: function(response) {
                $('#message').html('<p>' + response.responseJSON.pesan + '</p>');
            }
        });
    });

    function reloadContent() {
            $.ajax({
                    url: '{{ route('listregister') }}',
                    success: function(response) {
                        $('.master-page').html(response);
                    },
                    error: function() {
                        $('.master-page').html('<p>Error loading form.</p>');
                    }
                });
    };

});
</script>


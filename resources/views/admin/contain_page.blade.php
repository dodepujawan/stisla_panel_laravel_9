@extends('admin.main')
@section('content')
<div class="main-master">
    <div class="master-page">
        <h1>Main Page</h1>
        <h2>testo</h2>
        <div>wakanda forever</div>
    </div>
</div>
@endsection

@section('footer')
{{-- ======================= Menapmpilkan Halaman SPA ========================== --}}
<script>
$(document).ready(function() {
    // ========================= Edit Register ======================================
    $(document).on('click', '.dropdown-item.edit-register', function(e) {
        e.preventDefault();
        loadEditRegisterForm();
    });

    function loadEditRegisterForm() {
        $.ajax({
            url: '{{ route('editregister') }}', // Route to load the form
            type: 'GET',
            success: function(response) {
                $('.master-page').html(response);
            },
            error: function() {
                $('.master-page').html('<p>Error loading form.</p>');
            }
        });
    }
    // ========================= End Of Edit Register ======================================
    // ======================== List Register ============================================
    $(document).on('click', '.dropdown-item.list-register', function(e) {
        e.preventDefault();
        loadListRegisterForm();
    });

    function loadListRegisterForm(){
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
    // ========================= End Of List Register ======================================
});
</script>
{{-- ===================== End Of Menapmpilkan Halaman SPA ======================== --}}
@endsection

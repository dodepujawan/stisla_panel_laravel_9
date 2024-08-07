  <div class="container mt-5">
    <h2>User Table</h2>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="date" id="startDate" class="form-control" placeholder="Start Date">
        </div>
        <div class="col-md-3">
            <input type="date" id="endDate" class="form-control" placeholder="End Date">
        </div>
        <div class="col-md-3">
            <input type="text" id="searchBox" class="form-control" placeholder="Search">
        </div>
        <div class="col-md-3">
            <button id="filterBtn" class="btn btn-primary">Filter</button>
        </div>
    </div>
    <table id="userTable" class="display table table-bordered mb-2">
        <thead>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Roles</th>
                <th>Joined At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan diisi oleh DataTables -->
        </tbody>
    </table>
    {{-- <div class="row mt-3">
        <div class="col-md-6">
            <button id="prevPage" class="btn btn-secondary">Previous</button>
        </div>
        <div class="col-md-6 text-right">
            <button id="nextPage" class="btn btn-secondary">Next</button>
        </div>
    </div> --}}
</div>
<script>
$(document).ready(function() {
    let table = $('#userTable').DataTable({
        ajax: {
            url: '{{ route("filter_register") }}',
            data: function(d) {
                d.startDate = $('#startDate').val();
                d.endDate = $('#endDate').val();
                d.searchText = $('#searchBox').val();
            }
        },
        columns:[
            { data: 'email' },
            { data: 'name' },
            { data: 'roles' },
            { data: 'created_at' },
            {
                data: null,
                render: function (data, type, row) {
                    return '<button class="btn btn-primary btn-sm editBtn">Edit</button> ' +
                            '<button class="btn btn-danger btn-sm deleteBtn">Delete</button>';
                }
            }
        ],
        searching: false,
        paging: true,
        info: false
    });
        $('#filterBtn').on('click', function() {
            table.ajax.reload();
        });

        // $('#prevPage').on('click', function() {
        //     table.page('previous').draw('page');
        // });

        // $('#nextPage').on('click', function() {
        //     table.page('next').draw('page');
        // });

        $('#userTable tbody').on('click', '.editBtn', function () {
            let data = table.row($(this).parents('tr')).data();
            // Implementasikan logika edit Anda di sini
            console.log('Edit:', data);
        });

        $('#userTable tbody').on('click', '.deleteBtn', function () {
            let data = table.row($(this).parents('tr')).data();
            // Implementasikan logika hapus Anda di sini
            console.log('Delete:', data);
        });
});
</script>

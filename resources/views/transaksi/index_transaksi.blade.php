<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<div class="container master_transaksi">
    <div class="row">
        <div class="col-8">
            <form action="">
                <div class="form-group mb-3">
                    <select name="select_barang" id="select_barang" class="form-control">
                        <option></option>
                        <!-- Options for select dropdown -->
                    </select>
                    <button type="button" id="clear_select" class="btn btn-secondary mt-2"><i class="fa fa-eraser" aria-hidden="true"></i></button>
                </div>
                <div class="form-group mb-3">
                    <input type="number" id="jumlah_trans" class="form-control" placeholder="Jumlah barang">
                </div>
                <div class="form-group mb-3">
                    <input type="number" id="diskon_barang" class="form-control" placeholder="Diskon barang">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="col-4">
            <h3>Harga Barang :</h3>
            <h3 id="harga_barang">-</h3>
            <br>
            <h3>Stok Barang :</h3>
            <h3 id="stok_barang">-</h3>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
// ================================= Select Barang ===========================================
    $('#select_barang').select2({
        ajax: {
            url: '{{ route('get_barangs') }}',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term // Kirim parameter pencarian ke server
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(barang) {
                        return {
                            id: barang.id,
                            text: barang.kd_barang + ' - ' + barang.nama_barang,
                            harga: barang.harga,
                            stok: barang.stok
                        };
                    })
                };
            },
            cache: true
        },
        placeholder: 'Pilih Barang',
        minimumInputLength: 1
    });

    // ### Event listener saat item dipilih
    $('#select_barang').on('select2:select', function(e) {
        var data = e.params.data; // Data yang dipilih
        $('#harga_barang').text(data.harga);
        $('#stok_barang').text(data.stok);
    });

    // ### Clear Selected Barangs
    $('#clear_select').on('click', function() {
        $('#select_barang').val(null).trigger('change'); // Kosongkan pilihan
        $('#harga_barang').text('-').trigger('change');
        $('#stok_barang').text('-').trigger('change');
    });
// ================================= Select Barang ===========================================
});
</script>

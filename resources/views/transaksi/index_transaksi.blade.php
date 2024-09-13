{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.product-info {
  display: flex;
  gap: 20px; /* Adds space between the items */
}
.info-item {
  flex: 1; /* Makes each item take up equal space */
}
h5 {
  margin: 0; /* Removes default margin from h5 */
}

/* table scroll */
.table-container {
    max-height: 300px; /* Sesuaikan tinggi maksimum sesuai kebutuhan */
    overflow-y: auto;  /* Tambahkan scroll vertikal jika konten melebihi tinggi maksimum */
    width: 100%;       /* Pastikan lebar kontainer sesuai dengan tabel */
    border: 1px solid #ddd; /* Opsional: tambahkan border untuk kontainer tabel */
}

.table-container table {
    width: 100%; /* Pastikan tabel mengambil lebar penuh dari kontainer */
    border-collapse: collapse; /* Menghindari jarak antara border sel */
}

.table-container th, .table-container td {
    padding: 8px; /* Opsional: tambahkan padding untuk sel tabel */
    text-align: left; /* Opsional: sesuaikan perataan teks */
}
/* end of table scroll */
</style>
<div class="container master_transaksi">
    <div class="product-info">
        <input type="hidden" value="" id="kd_barang" readonly>
        <div class="info-item">
            <h5>Nama Barang: <span id="nama_barang">-</span></h5>
          </div>
        <div class="info-item">
          <h5>Harga Barang: <span id="harga_barang">-</span></h5>
        </div>
        <div class="info-item">
          <h5>Stok Barang: <span id="stok_barang">-</span></h5>
        </div>
    </div>
    <form action="" class="row col-12">
        <div class="form-group mb-3 mt-3 col-6 d-flex align-items-center">
            <button type="button" id="clear_select" class="btn btn-secondary me-2 mr-2"><i class="fa fa-eraser" aria-hidden="true"></i></button>
            <select name="select_barang" id="select_barang" class="form-control">
                <option></option>
                <!-- Options untuk select dropdown -->
            </select>
        </div>

        <div class="form-group mb-3 mt-3 col-3">
            <input type="number" id="jumlah_trans" class="form-control" placeholder="Jumlah barang">
        </div>
        <div class="form-group mb-3 mt-3 col-3">
            <input type="number" id="diskon_barang" class="form-control" placeholder="Diskon barang">
        </div>
        <button type="submit" class="btn btn-primary">Input</button>
    </form>
    <div class="mt-3 table-container table-responsive">
        <table id="transaksi_table" class="display table table-bordered mb-2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>KD Barang</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Diskon</th>
                    <th>Total</th>
                    <th>del</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right"><strong>Grand Total:</strong></td>
                    <td id="grand_total">0</td>
                </tr>
            </tfoot>
            <tbody>
                <!-- Data akan diisi oleh DataTables -->
            </tbody>
        </table>
    </div>
    <button type="submit" class="btn btn-primary mt-2" id="save_table">Submit</button>
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
// ================================= Select Barang ===========================================
    $('#select_barang').select2({
        // tags: true,
        ajax: {
            url: '{{ route('get_barangs') }}',
            dataType: 'json',
            delay: 250,
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
                            text: barang.kd_barang + ' / ' + barang.nama_barang + ' / ' + barang.kemasan,
                            kd_barang: barang.kd_barang,
                            nama: barang.nama_barang,
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
        $('#kd_barang').val(data.kd_barang);
        $('#nama_barang').text(data.nama);
        $('#harga_barang').text(data.harga);
        $('#stok_barang').text(data.stok);
    });

    // ### Clear Selected Barangs
    $('#clear_select').on('click', function() {
        $('#select_barang').val(null).trigger('change'); // Kosongkan pilihan
        $('#kd_barang').val("").trigger('change');
        $('#nama_barang').text('-').trigger('change');
        $('#harga_barang').text('-').trigger('change');
        $('#stok_barang').text('-').trigger('change');
    });
// ================================= End Of Select Barang ===========================================
// ================================= Input Barang To Table ===========================================
    let counter = 1;
    let grandTotal = 0;
    $('form').on('submit', function(event) {
        event.preventDefault();

        let kdBarang = $('#kd_barang').val();
        let namaBarang = $('#nama_barang').text();
        let hargaBarang = parseFloat($('#harga_barang').text()) || 0;
        let jumlahTrans = parseFloat($('#jumlah_trans').val()) || 0;
        let diskonBarang = parseFloat($('#diskon_barang').val()) || 0;
        let total = (hargaBarang - diskonBarang) * jumlahTrans;

        let newRow = `
            <tr>
                <td>${counter++}</td>
                <td>${kdBarang}</td>
                <td>${namaBarang}</td>
                <td>${hargaBarang}</td>
                <td class="editable" contenteditable="true">${jumlahTrans}</td>
                <td>${diskonBarang}</td>
                <td>${total}</td>
                <td><button type="button" class="btn btn-danger btn-sm delete-row"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
            </tr>
        `;
        $('#transaksi_table tbody').append(newRow);

        grandTotal += total;
        $('#grand_total').text(grandTotal);

        this.reset();
        $('#select_barang').val(null).trigger('change');
        $('#nama_barang').text('-');
        $('#harga_barang').text('-');
        $('#stok_barang').text('-');

        $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
      });

        setTimeout(function() {
            $('#select_barang').select2('open');
            document.querySelector('.select2-search__field').focus();
        }, 0);

});

    // ### Detele Table
    $('#transaksi_table').on('click', '.delete-row', function() {
        let row = $(this).closest('tr');
        let total = parseFloat(row.find('td:eq(6)').text()) || 0;

        row.remove();

        grandTotal -= total;
        $('#grand_total').text(grandTotal);
    });

    // ### Editing Table
    $('#transaksi_table').on('blur', '.editable', function() {
        let newJumlah = parseFloat($(this).text()) || 0;
        let row = $(this).closest('tr');
        let hargaBarang = parseFloat(row.find('td:eq(3)').text()) || 0;
        let diskonBarang = parseFloat(row.find('td:eq(5)').text()) || 0;
        let oldTotal = parseFloat(row.find('td:eq(6)').text()) || 0;

        // Hitung total baru dan update baris
        let total = (hargaBarang - diskonBarang) * newJumlah;
        row.find('td:eq(6)').text(total);

        // Update grand total
        grandTotal = grandTotal - oldTotal + total;
        $('#grand_total').text(grandTotal);
    });
// =============================== End Of Input Barang To Table =========================================
});
</script>

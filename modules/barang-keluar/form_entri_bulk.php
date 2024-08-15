<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  // alihkan ke halaman error 404
  header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi file
else { ?>
  <!-- menampilkan pesan kesalahan -->
  <div id="pesan"></div>

  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
        <!-- judul halaman -->
        <h4 class="page-title text-white"><i class="fas fa-sign-out-alt mr-2"></i> Barang Keluar</h4>
        <!-- breadcrumbs -->
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a href="?module=barang_keluar" class="text-white">Barang Keluar</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Entri Bulk</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="page-inner mt--5">
    <div class="card">
      <div class="card-header">
        <!-- judul form -->
        <div class="card-title">Entri Bulk Data Barang Keluar</div>
      </div>
      <!-- form entri data -->
      <form action="modules/barang-keluar/proses_entri_bulk.php" method="post" class="needs-validation">
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              <div class="form-group">
                <?php
                  $random_number = random_int(1000000, 9999999);
                  $nota_transaksi = "NT-" . $random_number;
                ?>
                <label>Nota Transaksi <span class="text-danger">*</span></label>
                <!-- tampilkan "id_transaksi" -->
                <input type="text" name="nota_transaksi" class="form-control" value="<?php echo $nota_transaksi; ?>" readonly>
              </div>
            </div>

            <div class="col-md-5 ml-auto">
              <div class="form-group">
                <label>Tanggal <span class="text-danger">*</span></label>
                <input type="text" name="tanggal" class="form-control date-picker" autocomplete="off" value="<?php echo date("d-m-Y"); ?>" required>
                <div class="invalid-feedback">Tanggal tidak boleh kosong.</div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
          <div class="col-12 ">
          <div class="form-group" style="display: flex; flex-direction: column;">
            <label style="font-size: 20px; font-weight: bold;">Total</label>
            <input
                type="text"
                id="total_transaksi"
                name="total_transaksi"
                value="Rp. 0"
                readonly
                style="height: 80px; font-size: 50px; text-align: end; padding-right: 10px;"
            />

          </div>
          </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-3">
              <div class="form-group">
                <label>Barang <span class="text-danger">*</span></label>
                <select id="data_barang" class="form-control chosen-select" autocomplete="off" required>
                  <option selected disabled value="">-- Pilih --</option>
                  <?php
                    // sql statement untuk menampilkan data dari tabel "tbl_barang"
                    $query_barang = mysqli_query($mysqli, "SELECT id_barang, nama_barang FROM tbl_barang ORDER BY id_barang ASC")
                                                          or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                    // ambil data hasil query
                    while ($data_barang = mysqli_fetch_assoc($query_barang)) {
                      // tampilkan data
                      echo "<option value='$data_barang[id_barang]'>$data_barang[id_barang] - $data_barang[nama_barang]</option>";
                    }
                  ?>
                </select>
                <div class="invalid-feedback">Barang tidak boleh kosong.</div>
              </div>
              <div class="form-group">
                <label>Stok <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" id="data_stok" class="form-control" readonly>
                  <div id="data_satuan" class="input-group-append"></div>
                </div>
              </div>
              <div class="form-group">
                <label>Jumlah Keluar <span class="text-danger">*</span></label>
                <input type="text" id="jumlah" class="form-control" autocomplete="off" onKeyPress="return goodchars(event,'0123456789.',this)">
                <div class="invalid-feedback">Jumlah keluar tidak boleh kosong.</div>
              </div>
              <div class="form-group">
                <label>Harga <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="hidden" id="data_harga" name="harga" class="form-control" readonly>
                  <input type="text" id="data_harga_preview" class="form-control" readonly>
                </div>
              </div>
              <div class="form-group">
                  <button type="button" id="addBarangKeluar" class="btn btn-primary" style="width: 100%;">Tambah Barang</button>
              </div>
            </div>
            <div class="col-12 col-md-9">
              <div class="form-group">
                <label>
                  List Barang
                </label>
              </div>
              <div style="min-height: 245px;">
                <table class="display table table-bordered table-striped table-hover">
                  <thead>
                      <tr>
                          <th style="width: 30px;">No</th>
                          <th>Barang</th>
                          <th style="width: 150px;" class="text-center">Jumlah</th>
                          <th style="width: 150px;" class="text-center">Harga</th>
                          <th style="width: 80px;" class="text-center">Aksi</th>
                      </tr>
                  </thead>
                  <tbody class="mt-2" id="listBarang">
                  </tbody>
                </table>
              </div>
              <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3"></textarea>
              </div>
            </div>

          </div>
          <!-- <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3"></textarea>
              </div>
            </div>
          </div> -->

          <div class="card-action" style="justify-content: end; align-items: end; display: flex;">
            <!-- tombol kembali ke halaman data barang keluar -->
            <a href="?module=barang_keluar" class="btn btn-default btn-round pl-4 pr-4">Batal</a>
            <!-- tombol simpan data -->
            <input type="submit" name="simpan" value="Simpan" class="btn btn-secondary btn-round pl-4 pr-4 ml-2">
          </div>
      </form>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
      var data_barang_keluar = [];

      $('#addBarangKeluar').click(function(){
        var no = $('#listBarang tr').length + 1;
        var barang = $('#data_barang option:selected').text();
        var idBarang = $('#data_barang option:selected').val();
        var jumlah = $('#jumlah').val();
        var harga_preview = $('#data_harga_preview').val();
        var harga = $('#data_harga').val();

        console.log("data barang keluar : ", data_barang_keluar, data_barang_keluar.find(function(data){return data.barang == barang;}))

        if(data_barang_keluar.find(function(data){return data.barang == barang;})){
          alert('Barang sudah ada');
          return;
        }
        data_barang_keluar.push({ barang: barang, jumlah: jumlah, harga: harga, idBarang: idBarang });

        var html = '<tr>';
        html += '<td>' + no + '</td>';
        html += '<td>' + barang + '</td>';
        html += '<td class="text-center">' + jumlah + '</td>';
        html += '<td class="text-center">' + harga_preview + '</td>';
        html += '<td><input type="button" class="btn btn-danger removeBarangKeluar" value="X"></td>';
        html += '<input type="hidden" name="barang_keluar[]" value="'+ barang +'">';
        html += '<input type="hidden" name="jumlah[]" value="'+ jumlah +'">';
        html += '<input type="hidden" name="id_barang[]" value="'+ idBarang +'">';
        html += '</tr>';

        $('#listBarang').append(html);

        calcTotal();
        
      });

      function calcTotal(){
        var total = 0;
        data_barang_keluar.forEach(function(data){
          total += data.jumlah * data.harga;
        });

        var totalPreview = new Intl.NumberFormat(
          'id-ID',
          { style: 'currency', currency: 'IDR', 
            minimumFractionDigits: 0
           }
        ).format(total);

        $('#total_transaksi').val(totalPreview);
      }


      $(document).on('click', '.removeBarangKeluar', function() {
        var barang = $(this).closest('tr').find('td:eq(1)').text();
        data_barang_keluar = data_barang_keluar.filter(function(data){return data.barang != barang;});
          $(this).closest('tr').remove();
        calcTotal();

      });

      // Menampilkan data barang dari select box ke textfield
      $('#data_barang').change(function() {
        // mengambil value dari "id_barang"
        var id_barang = $('#data_barang').val();

        $.ajax({
          type: "GET",                                  // mengirim data dengan method GET 
          url: "modules/barang-keluar/get_barang.php",  // proses get data berdasarkan "id_barang"
          data: {id_barang: id_barang},                 // data yang dikirim
          dataType: "JSON",                             // tipe data JSON
          success: function(result) {                   // ketika proses get data selesai
            // tampilkan data
            $('#data_stok').val(result.stok);
            $('#data_satuan').html('<span class="input-group-text">' + result.nama_satuan + '</span>');
            // set focus
            $('#jumlah').focus();
            $('#data_harga').val(result.harga)
            var hargaPreview = new Intl.NumberFormat(
              'id-ID',
              { style: 'currency', currency: 'IDR', 
                minimumFractionDigits: 0
               }
            ).format(result.harga);
            
            $('#data_harga_preview').val(hargaPreview);
          }
        });
      });

      // menghitung sisa stok
      $('#jumlah').keyup(function() {
        // mengambil data dari form entri
        var stok = $('#data_stok').val();
        var jumlah = $('#jumlah').val();

        // mengecek input data
        // jika data barang belum diisi
        if (stok == "") {
          // tampilkan pesan info
          $('#pesan').html('<div class="alert alert-notify alert-info alert-dismissible fade show" role="alert"><span data-notify="icon" class="fas fa-info"></span><span data-notify="title" class="text-info">Info!</span> <span data-notify="message">Silahkan isi data barang terlebih dahulu.</span><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
          // reset input "jumlah"
          $('#jumlah').val('');
          // sisa stok kosong
          var sisa_stok = "";
        }
        // jika "jumlah" belum diisi
        else if (jumlah == "") {
          // sisa stok kosong
          var sisa_stok = "";
        }
        // jika "jumlah" lebih dari "stok"
        else if (eval(jumlah) > eval(stok)) {
          // tampilkan pesan peringatan
          $('#pesan').html('<div class="alert alert-notify alert-warning alert-dismissible fade show" role="alert"><span data-notify="icon" class="fas fa-exclamation"></span><span data-notify="title" class="text-warning">Peringatan!</span> <span data-notify="message">Stok tidak memenuhi, kurangi jumlah keluar.</span><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
          // reset input "jumlah"
          $('#jumlah').val('');
          // sisa stok kosong
          var sisa_stok = "";
        }
        // jika "jumlah" sudah diisi
        else {
          // hitung sisa stok
          var sisa_stok = eval(stok) - eval(jumlah);
        }

        // tampilkan sisa stok
        $('#sisa').val(sisa_stok);
      });
    });
  </script>
<?php } ?>
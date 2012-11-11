<?php
/* Komponen yg digunakan untuk tabel yaitu:
 * "Judul Kolom" 
 *   => ini adalah judul kolom yang akan muncul pada baris teratas sebuah tabel
 *   => boleh menggunakan petik dua (") maupun petik satu (')
 * 
 * '$item["nama_kolom_dlm_database"]'
 *   => ini adalah referensi nama kolom yg ada dalam tabel pada database
 *   => penggunaannya petik paling luar harus petik satu (')
 *   => misal, pada tabel user ada kolom nama, maka penggunaannya
 *   ===> '$item["nama"]' <===
 * 
 *   => untuk beberapa kebutuhan kita bisa memanfaatkan atau membuat fungsi sendiri
 *      beberapa contoh penggunaan fungsi:
 *      - untuk mencetak angka dalam format ribuan, bisa digunakan: angka()
 *        contoh: 'angka($item["harga"])'
 *      - untuk mencetak tautan/link (biasanya untuk foto atau file), bisa digunakan: link_href()
 *        contoh: 'link_href($item["file"])'
 *      - untuk menampilkan gambar (biasanya untuk foto), bisa digunakan: gambar()
 *        contoh: 'gambar($item["file"],FILES_URL,200)'
 *        keterangan: 
 *        - hasil uploadan biasanya disimpan di folder files (dalam contoh diwakili oleh FILES_URL)
 *        - untuk ukuran width, bisa diatur dengan memasukkan parameter ketiga (dalam contoh diwakili oleh 200)
 */
?>
<style>
  table.grid {
    background: #333;
    border: 1px solid black;
  }

  table.grid tr.baris_judul {
    background: #4d8199;
  }
  table.grid tr.baris_genap {
    background: #cf7c29;
  }
  table.grid tr.baris_ganjil {
    background: #ccae29;
  }

  table.grid tr td, table.grid tr th {
    border: 1px solid black;
  }

  table.grid tr:hover {
    background: #1c94c4;
  }
</style>
<h1><?php echo $judul ?></h1>
<?php
tabel("nama_tabel",$data,array(
    "Judul Kolom"=>'$item["nama_kolom"]',
),$aksi,$no_page,$jml_data_per_page,$total_data,$method);
?>
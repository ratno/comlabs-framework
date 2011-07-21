<h1><?php echo $judul ?></h1>
<?php
tabel("nama_tabel",$data,array(
    "Judul Kolom 1"=>'$item["nama_kolom1_di_tabel"]',
    "Judul Kolom 2"=>'angka($item["nama_kolom2_di_tabel"])', // jika mau di format dg pemisah ribuan gunakan fungsi angka()
    "Judul Kolom 3"=>'link_href($item["nama_kolom3_di_tabel"])', // jika mau menampilkan link dari data yg disimpan dikolom (biasanya file upload)
    "Judul Kolom 4"=>'gambar($item["nama_kolom4_di_tabel"],FILES_URL)', // jika mau menampilkan gambar dari data yg disimpan dikolom (biasanya file upload berupa gambar)
    
),$aksi);
?>
<?php echo $link_tambah ?>
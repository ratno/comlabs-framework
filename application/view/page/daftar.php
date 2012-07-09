<h1><?php echo $judul ?></h1>
<?php
tabel("page",$data,array(
    "Kode"=>'$item["kode"]',
    "Nama"=>'$item["nama"]',
    "Akses"=>'$item["akses"]',
),$aksi,$no_page,$jml_data_per_page,$total_data,$method);
?>
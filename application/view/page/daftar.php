<div class="page-header">
  <h1><?php echo $judul ?></h1>
</div>
<?php
tabel("page",$data,array(
    "Kode"=>'$item["kode"]',
    "Nama"=>'$item["nama"]',
    "Akses"=>'$item["akses"]',
),$aksi);
?>
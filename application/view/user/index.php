<h1><?php echo $judul ?></h1>
<?php
tabel("user",$data,array(
    "Nama"=>'$item["nama"]',
    "Email"=>'$item["email"]',
    "Handphone"=>'angka($item["hp"],0)',
    "Username"=>'$item["username"]',
    "Role"=>'$item["role"]'
),$aksi);
?>
<?php echo $link_tambah ?>
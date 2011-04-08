<h1><?php echo $judul ?></h1>
<?php
tabel("user",$data,array(
    "Nama"=>'$item["nama"]',
    "Email"=>'$item["email"]',
    "Handphone"=>'angka($item["hp"],0)',
    "Username"=>'$item["username"]',
    "Role"=>'$item["role"]'
));
?>
<a href="<?php echo url("user","tambah") ?>">Tambah Data</a>
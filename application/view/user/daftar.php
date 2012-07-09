<h1><?php echo $judul ?></h1>
<?php
tabel("user",$data,array(
    "Nama"=>'$item["nama"]',
    "Email"=>'$item["email"]',
    "Handphone"=>'angka($item["hp"],0)',
    "Username"=>'$item["username"]',
    "Role"=>'$item["role"]',
    "Tanggal Diangkat"=>'tanggal($item["tgl_diangkat"])'
),$aksi,$no_page,$jml_data_per_page,$total_data,$method);
?>
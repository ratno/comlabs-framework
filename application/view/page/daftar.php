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
tabel("page",$data,array(
    "Kode"=>'$item["kode"]',
    "Nama"=>'$item["nama"]',
    "Akses"=>'$item["akses"]',
),$aksi,$no_page,$jml_data_per_page,$total_data,$method);
?>
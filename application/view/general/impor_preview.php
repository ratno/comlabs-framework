<h1><?php echo $judul ?></h1>
<?php echo $tabel ?>
<form <?php form_properties($controller,$aksi,"") ?>>
  <?php input("insert", $data, "hidden"); ?>
  <?php submit("Simpan Data"); ?>
</form>
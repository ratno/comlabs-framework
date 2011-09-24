<h1><?php echo $judul ?></h1>
<form <?php form_properties("nama_tabel", $aksi, $data['id'], true) ?>>
  <table border="0">


    <tr>
      <td>Nama Isian Kolom</td>
      <td><?php input("kolom_di_dalam_tabel", $data) ?></td>
    </tr>


    <tr>
      <td>Nama Isian Kolom Untuk Upload</td>
      <td><?php input("kolom_di_dalam_tabel", $data, "file") ?></td>
    </tr>


    <tr>
      <td>Nama Isian Kolom Untuk Pilihan Yang Mengambil data dari tabel</td>
      <td>
        <?php pilihan("kolom_di_dalam_tabel", $opsi_db, $data) ?>
      </td>	
    </tr>
    <tr>
      <td>Nama Isian Kolom Untuk Pilihan</td>
      <td>
        <?php pilihan("kolom_di_dalam_tabel", $opsi_manual, $data) ?>
      </td>	
    </tr>


    <tr>
      <td colspan="2"><?php submit(); ?></td>
    </tr>
  </table>
</form>
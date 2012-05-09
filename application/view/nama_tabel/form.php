<?php
/* komponen form yg dapat digunakan:
 * 1. textbox: 
 *    <?php input("nama_kolom", $data) ?>
 * 
 * 2. file upload: 
 *    <?php input("nama_kolom", $data, "file") ?>
 * 
 * 3. selectbox (pilihan):
 *    - opsi manual dengan array:
 *      <?php $opsi_manual = array("Opsi 1"=>"Opsi 1", "Opsi 2"=> "Opsi 2"); ?>
 *      <?php pilihan("nama_kolom", $opsi_manual, $data) ?>
 * 
 *    - opsi dari database :
 *      <?php $opsi_db = opsi($this->model_XXX->ambil_data(),"nama_kolom_tabel_XXX"); ?> 
 *      <?php pilihan("nama_kolom", $opsi_db, $data) ?>
 * 
 * 4. textarea:
 *    <?php textarea("nama_kolom", $data) ?>
 */
?>

<h1><?php echo $judul ?></h1>
<form <?php form_properties("nama_tabel", $aksi, $data['id'], true) ?>>
  <table border="0">

    <tr>
      <td>Nama Inputan/Isian Form</td>
      <td><?php input("nama_kolom", $data) ?></td>
    </tr>

    <tr>
      <td colspan="2"><?php submit(); ?></td>
    </tr>
  </table>
</form>
<h1><?php echo $judul ?></h1>
<form <?php form_properties($controller,$aksi,"",true) ?>>
  <?php input('controller',$controller, 'hidden'); ?>
  <?php input('tabel',$tabel, 'hidden'); ?>
	<table border="0">
    
		<tr>
			<td>File XLS</td>
      <td><?php input("file_csv", "","file") ?></td>
		</tr>
    
		<tr>
			<td>No Sheet</td>
      <td><?php input("sheet_num",1) ?></td>
		</tr>
		<tr>
			<td>No Baris Judul Kolom</td>
      <td><?php input("header_row", 1) ?></td>
		</tr>
		<tr>
			<td>No Baris Data Pertama</td>
      <td><?php input("first_row", 2) ?></td>
		</tr>
		<tr>
			<td>No Baris Data Terakhir</td>
      <td><?php input("last_row") ?></td>
		</tr>
		<tr>
			<td>Kolom-kolom database (dipisahkan dengan koma)</td>
      <td><?php input("columns") ?></td>
		</tr>
    
    
		<tr>
      <td colspan="2"><?php submit("Impor"); ?></td>
		</tr>
    
	</table>
</form>
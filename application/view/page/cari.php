<h1><?php echo $judul ?></h1>
<form <?php form_properties("page",$aksi,$data['id'],true) ?>>
	<table border="0">
    
		<tr>
			<td>Nama Isian Kolom</td>
      <td><?php input("kolom_di_dalam_tabel", $data) ?></td>
		</tr>
    
    <tr>
      <td colspan="2"><?php submit('Cari'); ?></td>
		</tr>
	</table>
</form>
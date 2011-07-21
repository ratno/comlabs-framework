<h1><?php echo $judul ?></h1>
<form <?php form_properties("nama_tabel",$aksi,$data['id'],true) ?>>
	<table border="0">
    
    
		<tr>
			<td>Nama Isian Kolom</td>
      <td><?php input("kolom_di_dalam_tabel", $data) ?></td>
		</tr>
		
		<tr>
			<td>Nama Isian Kolom Untuk Pilihan</td>
			<td>
        <?php pilihan("kolom_di_dalam_tabel", 
                array("pilihan satu (ini yg disimpan di tabel)"=>"Pilihan Satu (ini yg muncul di halaman web)",
                      "pilihan dua (ini yg disimpan di tabel)"=>"Pilihan Dua (ini yg muncul di halaman web)",
                      "pilihan tiga (ini yg disimpan di tabel)"=>"Pilihan Tiga (ini yg muncul di halaman web)"
                     ), $data) ?>
			</td>	
		</tr>
    
    
		<tr>
      <td colspan="2"><?php submit('Cari'); ?></td>
		</tr>
	</table>
</form>
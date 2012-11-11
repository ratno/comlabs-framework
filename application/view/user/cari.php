<h1><?php echo $judul ?></h1>
<form <?php form_properties("user",$aksi,$data[model_user::pk()],true,$form_method) ?>>
	<table border="0">
		<tr>
			<td>Nama</td>
      <td><?php input("nama", $data) ?></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><?php input("email", $data) ?></td>
		</tr>
		<tr>
			<td>Handphone</td>
			<td><?php input("hp", $data) ?></td>
		</tr>
		<tr>
			<td>Username</td>
			<td><?php input("username", $data) ?></td>
		</tr>
		<tr>
			<td>Role</td>
			<td>
        <?php pilihan("role", array("admin"=>"Admin","user"=>"User"), $data) ?>
			</td>	
		</tr>
		<tr>
      <td colspan="2"><?php submit('Cari'); ?></td>
		</tr>
	</table>
</form>
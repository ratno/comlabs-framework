<div class="page-header">
  <h1><?php echo $judul ?></h1>
</div>
<form <?php form_properties("user",$aksi,$data[model_user::pk()],true) ?>>
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
			<td>Password</td>
			<td><?php input("password", $data, "password") ?></td>
		</tr>
		<tr>
			<td>Role</td>
			<td>
        <?php pilihan("role", array("admin"=>"Admin","user"=>"User"), $data) ?>
			</td>	
		</tr>
    <tr>
			<td>Tanggal Diangkat</td>
			<td><?php input("tgl_diangkat", $data, "tgl") ?></td>
		</tr>
		<tr>
      <td colspan="2"><?php submit(); ?></td>
		</tr>
	</table>
</form>
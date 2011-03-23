<h1><?php echo $judul ?></h1>
<form action="<?php echo url("user",$aksi,array("id"=>$data['id'])) ?>" method="POST">
	<table border="0">
		<tr>
			<td>Nama</td>
			<td><input type="text" name="nama" value="<?php echo $data['nama'] ?>" /></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input type="text" name="email" value="<?php echo $data['email'] ?>" /></td>
		</tr>
		<tr>
			<td>Handphone</td>
			<td><input type="text" name="hp" value="<?php echo $data['hp'] ?>" /></td>
		</tr>
		<tr>
			<td>Username</td>
			<td><input type="text" name="username" value="<?php echo $data['username'] ?>" /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="text" name="password" value="<?php echo $data['password'] ?>" /></td>
		</tr>
		<tr>
			<td>Role</td>
			<td>
				<select name="role">
					<option value="admin" <?php echo selected($data['role'],"admin") ?>>admin</option>
					<option value="user" <?php echo selected($data['role'],"user") ?>>user</option>
				</select>
			</td>	
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Kirim" /></td>
		</tr>
	</table>
</form>
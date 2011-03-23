<h1><?php echo $judul ?></h1>
<table border="1" cellpadding="5" cellspacing=0>
	<tr>
		<th>Nama</th>
		<th>Email</th>
		<th>Handphone</th>
		<th>Username</th>
		<th>Role</th>
		<th>Aksi</th>
	</tr>
<?php foreach($data as $item): ?>
	<tr>
		<td><?php echo $item['nama'] ?></td>
		<td><?php echo $item['email'] ?></td>
		<td><?php echo $item['hp'] ?></td>
		<td><?php echo $item['username'] ?></td>
		<td><?php echo $item['role'] ?></td>
		<td>
			[<a href="<?php echo url("user","ubah",array("id"=>$item['id'])) ?>">ubah</a>]
			[<a href="<?php echo url("user","hapus",array("id"=>$item['id'])) ?>">hapus</a>]
		</td>
	</tr>
<?php endforeach; ?>
</table>
<a href="<?php echo url("user","tambah") ?>">Tambah Data</a>
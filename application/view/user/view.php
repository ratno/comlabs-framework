<h1><?php echo $judul ?></h1>
<table border="0" cellpadding="10" cellspacing="0">
  <tr>
    <td>Nama</td>
    <td><?php echo $data["nama"] ?></td>
  </tr>
  <tr>
    <td>Email</td>
    <td><?php echo $data["email"] ?></td>
  </tr>
  <tr>
    <td>Handphone</td>
    <td><?php echo $data["hp"] ?></td>
  </tr>
  <tr>
    <td>Username</td>
    <td><?php echo $data["username"] ?></td>
  </tr>
  <tr>
    <td>Password</td>
    <td><?php echo $data["password"] ?></td>
  </tr>
  <tr>
    <td>Role</td>
    <td><?php echo $data["role"] ?></td>	
  </tr>
  <tr>
    <td>Tanggal Diangkat</td>
    <td><?php echo tanggal($data["tgl_diangkat"]) ?></td>
  </tr>
</table>
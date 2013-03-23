<div class="page-header">
  <h1><?php echo $judul ?></h1>
</div>
<form action="<?php echo url("index", "login") ?>" method="POST">
  <table border="0" cellpadding=5>
    <tr>
      <td>Username</td>
      <td><?php input("username") ?></td>
    </tr>
    <tr>
      <td>Password</td>
      <td><?php input("password","","password") ?></td>
    </tr>
    <tr>
      <td colspan="2">
        <?php submit("login") ?>
      </td>
    </tr>
  </table>
</form>

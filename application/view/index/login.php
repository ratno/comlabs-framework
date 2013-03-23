<div class="page-header">
  <h1><?php echo $judul ?></h1>
</div>
<form action="<?php echo url("index", "login") ?>" method="POST">
  <table border="0" cellpadding=5>
    <tr>
      <td>Username</td>
      <td><input type="text" name="username" /></td>
    </tr>
    <tr>
      <td>Password</td>
      <td><input type="password" name="password" /></td>
    </tr>
    <tr>
      <td colspan="2">
        <input type="submit" value="login" />
      </td>
    </tr>
  </table>
</form>

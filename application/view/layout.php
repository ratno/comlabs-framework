<?php
$arr_menu = array(
    "public" => array(
        "index/index" => "Home",
        "index/login" => "Login"),
    "admin" => array(
        "index/index" => "Home",
        "index/control_panel" => "Control Panel",
        "user/index" => "Daftar User",
        "user/cari" => "Pencarian User",
        "user/laporan" => "Laporan User",
        "index/logout" => "Logout"),
    "user" => array(
        "index/index" => "Home",
        "index/control_panel" => "Control Panel",
        "user/index" => "Daftar User",
        "user/cari" => "Pencarian User",
        "user/laporan" => "Laporan User",
        "index/logout" => "Logout")
);

if ($user = $_SESSION['data_user']) {
  $menu = $arr_menu[$user['role']];
} else {
  $menu = $arr_menu['public'];
}
?>
<html>
  <head>
    <title><?php echo $judul ?></title>
    <style>
      a.menu {
        display: block; height: 20px; border: 1px solid grey; padding: 10px; background-color: cornflowerblue; float: left;
        text-align: center; text-decoration: none; margin-right: 1px;
      }
      
      a.menu:hover {
        background-color: dodgerblue;
      }
      
      .clear {
        clear: both;
      }
    </style>
  </head>
  <body>
    <?php foreach ($menu as $menu_link => $menu_name) : ?>
      <a class="menu" href="<?php echo BASEURL . SUBDIR . "/$menu_link"; ?>">
        <?php echo $menu_name ?>
      </a>
    <?php endforeach; ?>
    <div class="clear"></div>
    <?php echo $main_content ?>
  </body>
</html>
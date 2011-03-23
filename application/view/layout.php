<?php
$arr_menu = array(
	"public"=>array("index/index"=>"Home","index/login"=>"Login"),
	"admin"=>array("index/index"=>"Home", "index/control_panel"=>"Control Panel", "user/index"=>"Daftar User", "index/logout"=>"Logout"),
	"user"=>array("index/index"=>"Home", "index/control_panel"=>"Control Panel", "index/logout"=>"Logout")
);

if($user = $_SESSION['data_user']){
	$menu = $arr_menu[$user['role']];
} else {
	$menu = $arr_menu['public'];
}
?>
<html>
    <head>
        <title><?php echo $judul ?></title>
    </head>
    <body>
		<?php foreach($menu as $menu_link=>$menu_name) : ?>
			<a href="<?php echo BASEURL.SUBDIR."/$menu_link"; ?>">
				<?php echo $menu_name ?>
			</a>
		<?php endforeach;?>
		<?php echo $main_content ?>
    </body>
</html>
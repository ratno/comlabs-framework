<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $judul ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <?php echo $css ?>
    <?php echo $html5shiv ?>
    <?php echo $jquery ?>
  </head>

  <body>

    <div id="wrap">
      <?php menu($menu); ?>
      <div class="container">
      <?php alert(); ?>
      <?php echo $main_content ?>
      </div>
      <div id="push"></div>
    </div>

    <div id="footer">
      <div class="container">
        <p class="muted credit"><?php echo $appfooter ?></p>
      </div>
    </div>

    <?php echo $js ?>
    <?php echo $script ?>
  </body>
</html>
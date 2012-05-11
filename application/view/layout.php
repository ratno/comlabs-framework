<html>
  <head>
    <title><?php echo $judul ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo CSS_URL ?>/menu.css" />
    <?php echo $css ?>
  </head>
  <body>
    <?php menu($menu); ?>
    <div class="clear"></div>
    <?php echo $main_content ?>
    <?php echo $js ?>
    <?php echo $script ?>
    <script type="text/javascript">
      $(function() {
        /**
         * the menu
         */
        var $menu = $('#ldd_menu');
				
        /**
         * for each list element,
         * we show the submenu when hovering and
         * expand the span element (title) to 510px
         */
        $menu.children('li').each(function(){
          var $this = $(this);
          var $span = $this.children('span');
          if($span.length == 0) $span = $this.children('a');
          $span.data('width',$span.width());

          $this.bind('mouseenter',function(){
            $menu.find('.ldd_submenu').stop(true,true).hide();
            $span.stop().animate({'width':'120px'},300,function(){
              $this.find('.ldd_submenu').slideDown(300);
            });
          }).bind('mouseleave',function(){
            $this.find('.ldd_submenu').stop(true,true).hide();
            $span.stop().animate({'width':$span.data('width')+'px'},300);
          });
        });
      });
    </script>
  </body>
</html>
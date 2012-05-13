$(function() {
  var $menu = $('#ldd_menu');
  $menu.children('li').each(function(){
    var $this = $(this);
    var $span = $this.children('span');
    if($span.length == 0) $span = $this.children('a');
    $span.data('width',$span.width());

    $this.bind('mouseenter',function(){
      $menu.find('.ldd_submenu').stop(true,true).hide();
      $span.stop().animate({
        'width':'120px'
      },300,function(){
        $this.find('.ldd_submenu').slideDown(300);
      });
    }).bind('mouseleave',function(){
      $this.find('.ldd_submenu').stop(true,true).hide();
      $span.stop().animate({
        'width':$span.data('width')+'px'
        },300);
    });
  });
});
(function ($) {

    Drupal.behaviors.SwipeBox = {
      attach: function(context, settings) {
        $('.swipebox', context).swipebox({
          hideBarsDelay: 0
        });
      }
    };

})(jQuery);

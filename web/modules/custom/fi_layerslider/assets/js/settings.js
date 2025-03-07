(function ($, Drupal) {
  "use strict";
  Drupal.behaviors.fi_layerslider_settings = {
    attach: function () {
      $('form#fi-layerslider-settings').once('fi-submit').each(function () {
        $(this).submit(function () {
          var settings = {};
          $('.setting-option').each(function(){
            settings[$(this).attr('name')] = $(this).val();
          });
          $('input[name=settings]').val(JSON.stringify(settings));
        });
      });
    }
  };
})(jQuery, Drupal);
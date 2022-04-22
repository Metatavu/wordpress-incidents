/* global acf */

(function($) {
  'use strict';

  if (window.acf) {
    acf.add_action('ready', function($element) {
      var incidentsContainer = $('.acf-taxonomy-field[data-taxonomy="incident_areas"]');
      var listHolder = incidentsContainer.find(".categorychecklist-holder");
      var selectAllContainer = $('<div>')
        .css({
          'padding-top': '5px',
          'padding-left': '10px'
        }).prependTo(listHolder);
      
      $('<a>')
        .text('Valitse kaikki / poista valinnat')
        .attr('href', '#')
        .appendTo(selectAllContainer)
        .click(function () {
          var notCheckedCount = $('.acf-taxonomy-field[data-taxonomy="incident_areas"] input[type="checkbox"]:not(:checked)').length;
          if (notCheckedCount === 0) {
            $('.acf-taxonomy-field[data-taxonomy="incident_areas"] input[type="checkbox"]').prop('checked', false);
          } else {
            $('.acf-taxonomy-field[data-taxonomy="incident_areas"] input[type="checkbox"]').prop('checked', true);
          }
        });
    });
  }
  
})(jQuery);
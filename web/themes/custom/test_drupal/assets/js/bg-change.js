/**
 * @file
 * Placeholder file for custom sub-theme behaviors.
 *
 */
(function ($, Drupal) {

  /**
   * Change teaser events background color.
   */
  Drupal.behaviors.background_change = {
    selectors: {
      container: '.bg-to-change',
      button: '[data-btn-bg]',
    },

    attach: function (context, settings) {
      const behavior = Drupal.behaviors.background_change;
      const $containers = $(behavior.selectors.container);

      $containers.each(function() {
        behavior.attachChangeBgColorBehavior($(this));
      });
    },

    /**
     * Attach Change Background Color behavior to an element.
     *
     * @param $element
     *   The jQuery element.
     */
    attachChangeBgColorBehavior: function($element) {
      const behavior = Drupal.behaviors.background_change;
      const $button = $element.find(behavior.selectors.button);
      const colors = ['#8382814f', '#ed60604f', '#96e1a34f', '#e1db964f'];

      $button.on('click', function (e) {
        $element.css('background', colors[Math.floor(Math.random() * colors.length)]);
      })
    },
  }

})(jQuery, Drupal);

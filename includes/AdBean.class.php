<?php
/**
 * @file
 * Ad Bean.
 */

/**
 * Ad Bean.
 *
 * Placeholder class.  The fields are applied to the bean via features.
 */
class AdBean extends BeanPlugin {

  /**
   * Implements the view method for this class
   */
  public function view($bean, $content, $view_mode = 'default', $langcode = NULL) {

    // Block the render of the original bean display.
    $content['bean'][$bean->delta]['#access'] = FALSE;

    $field_ad_expiration = field_get_items('bean', $bean, 'field_ad_expiration');
    $expiration_timestamp = $field_ad_expiration[0]['value'];
    $expired = (time() > $expiration_timestamp);

    // Build render array.
    $build = array();

    $array = $content['bean'][$bean->delta];
    if (!empty($array['field_ad_image']['#items'])) {
      $build = array(
        '#theme' => 'image',
        '#path' => $array['field_ad_image']['#items'][0]['uri'],
      );
      if (isset($array['field_ad_url']['#items'][0]['value'])) {
        $url = $array['field_ad_url']['#items'][0]['value'];
        $attributes = array(
          'href' => $url,
          'class' => array('ad-bean'),
        );
        $build['#prefix'] = '<a ' . drupal_attributes($attributes) . '>';
        $build['#suffix'] = '</a>';
      }

      // Add "Expired" flag.
      if ($expired) {
        $build['#suffix'] = '<span class="expired">Expired</span>' . $build['#suffix'];
      }

      // Add our own render array.
      if (!$expired || user_access('view expired ads')) {
        $content['bean'][$bean->delta] = $build;
        $content['bean'][$bean->delta]['#entity'] = $bean;
        drupal_add_css(drupal_get_path('module', 'newspaper_base') . '/css/ad_bean.css');
      }
    }

    return $content;
  }

}

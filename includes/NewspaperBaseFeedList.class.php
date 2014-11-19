<?php
/**
 * @file
 * NewspaperBase Feed List Bean
 */

/**
 * Feed List bean.
 */
class NewspaperBaseFeedList extends BeanPlugin {

  /**
   * Implements the values method for this class
   */
  public function values() {
    $values = parent::values();
    $values['url'] = '';
    $values['num'] = 5;
    return $values;
  }

  /**
   * Implements the form method for this class
   */
  public function form($bean, $form, &$form_state) {
    $form['url'] = array(
      '#title' => t('Feed URL'),
      '#type' => 'textfield',
      '#default_value' => !empty($bean->url) ? $bean->url : '',
    );
    $form['num'] = array(
      '#title' => t('Maximum number of entries to show'),
      '#type' => 'select',
      '#options' => array(
        5 => '5',
        10 => '10',
      ),
      '#default_value' => !empty($bean->num) ? $bean->num : 5,
    );
    return $form;
  }

  /**
   * Implements the view method for this class
   */
  public function view($bean, $content, $view_mode = 'default', $langcode = NULL) {
    $xml_items = $this->getXmlItems($bean);
    if (empty($xml_items)) {
      return array();
    }
    foreach ($content['bean'] as $entity_id => $array) {
      $items = array();
      foreach ($xml_items as $i => $item) {
        $items[] = (array) $item;
      }
      $content['bean'][$entity_id]['rss_links'] = array(
        '#theme' => 'feed_list',
        '#items' => $items,
      );
    }

    return $content;
  }

  /**
   * Impelements BeanPlugin::submit().
   */
  public function submit(Bean $bean) {
    // Clear the cached items.
    $cache_slug = 'newspaper_base_feed_list-' . $bean->delta;
    cache_clear_all($cache_slug, 'cache');
  }

  /**
   * Returns the xml_items for the given bean.
   */
  protected function getXmlItems($bean) {
    $cache_slug = 'newspaper_base_feed_list-' . $bean->delta;
    $cached = cache_get($cache_slug, 'cache');
    if ($cached === FALSE) {
      $xml_items = array();
      try {
        $xml = @simplexml_load_file($bean->url);
        if ($xml) {
          $xml_items = $xml->xpath('//item');
          $xml_items = array_slice($xml_items, 0, $bean->num);
          // Cast SimpleXML elements as arrays.
          foreach ($xml_items as $i => $item) {
            $xml_items[$i] = (array) $item;
          }
        }
      }
      catch (Exception $e) {}
      // Cache for 12 hours.
      cache_set($cache_slug, $xml_items, 'cache', time() + 60 * 60 * 12);
    }
    else {
      // Unpack cached items.
      $xml_items = $cached->data;
    }
    return $xml_items;
  }

}

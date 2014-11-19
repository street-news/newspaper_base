<?php
/**
 * @file
 * NewspaperBase Featured Article Bean
 */

/**
 * Links bean.
 *
 * Placeholder class.  The link field is applied to the bean via features.
 */
class NewspaperBaseFeaturedArticle extends BeanPlugin {

  /**
   * Implements the values method for this class
   */
  public function values() {
    $values = parent::values();
    $values['nid'] = '';
    $values['display_type'] = 'callout';
    return $values;
  }

  /**
   * Implements the form method for this class
   */
  public function form($bean, $form, &$form_state) {
    $nid_default = '';
    if (!empty($bean->nid)) {
      $result = db_select('node', 'n')
                  ->fields('n', array('title', 'nid'))
                  ->condition('nid', $bean->nid, '=')
                  ->execute()
                  ->fetchAssoc();
      $title = $result['title'];
      $nid = $bean->nid;
      $nid_default = "$title [nid:$nid]";
    }
    $form['nid'] = array(
      '#title' => t('Article'),
      '#type' => 'textfield',
      '#autocomplete_path' => 'newspaper_base/article/autocomplete',
      '#default_value' => $nid_default,
    );
    $form['display_type'] = array(
      '#title' => t('Display Type'),
      '#type' => 'select',
      '#options' => $this->getDisplayTypes(),
      '#default_value' => !empty($bean->display_type) ? $bean->display_type : 'callout',
    );
    return $form;
  }

  /**
   * Impelements BeanPlugin::submit().
   */
  public function submit(Bean $bean) {
    if (!is_int($bean->data['nid'])) {
      // Pull the nid out of the nid string "Node Title [nid:32]".
      $nid_string = $bean->data['nid'];
      preg_match('/\[nid:\D*(\d+)\]/', $nid_string, $matches);
      $bean->data['nid'] = (int) $matches[1];
    }
  }

  /**
   * Implements the view method for this class
   */
  public function view($bean, $content, $view_mode = 'default', $langcode = NULL) {
    $node = node_load($bean->nid);
    if ($node) {
      $build = node_view($node, 'teaser');
      $build['#featured_mode'] = $bean->display_type;
      $content['bean'][$bean->delta]['node'] = array(
        'node' => $build,
      );
    }

    return $content;
  }

  /**
   * Returns the defined display types.
   */
  public function getDisplayTypes() {
    return array(
      '' => '- None - ',
      'callout' => 'Callout',
    );
  }

}

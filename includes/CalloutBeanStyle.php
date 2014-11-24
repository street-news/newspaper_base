<?php

/**
 * @file
 * Callout bean style for featured bean.
 */

class CalloutBeanStyle extends BeanStyle {
  /**
   * Implements parent::view().
   */
  public function prepareView($build, $bean) {
    parent::prepareView($build, $bean);

    $build['field_featured_content'] = $this->items;

    return $build;
  }

  /**
   * Implements parent::prepareItems().
   */
  protected function prepareItems($build, $type) {
    foreach ($build['#featured_content'] as $content) {
      $this->items[] = node_view($content['entity'], 'teaser');
    }
  }
}

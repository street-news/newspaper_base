<?php

/**
 * @file
 * Callout bean style for featured bean.
 */

class SecondaryArticleBeanStyle extends BeanStyle {
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
      $node_build = node_view($content['entity'], 'teaser');
      $node_build['#featured_mode'] = 'secondary_article';
      $this->items[] = $node_build;
    }
  }
}

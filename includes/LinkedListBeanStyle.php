<?php

/**
 * @file
 * Linked list style for solr bean.
 */

class LinkedListBeanStyle extends ListBeanStyle {
  /**
   * Implements parent::prepareFeaturedItems().
   */
  protected function prepareFeaturedItems($build) {
    foreach ($build['#featured_content'] as $content) {
      $this->items[] = $this->prepareLinkTheme($content['entity']->title, 'node/' . $content['entity']->nid);
    }
  }

  /**
   * Implements parent::prepareSolrItems().
   */
  protected function prepareSolrItems($build) {
    if (!empty($build['search']['search_results']['#results'])) {
      foreach ($build['search']['search_results']['#results'] as $result) {
        $this->items[] = $this->prepareLinkTheme('node/' . $result['fields']['entity_id'], $result['title']);
      }
    }
  }

  /**
   * Build link theme array.
   */
  protected function prepareLinkTheme($path, $title) {
    return array(
      '#theme' => 'link',
      '#text' => $title,
      '#path' => $path,
      '#options' => array(
        'html' => FALSE,
        'attributes' => array(),
      ),
    );
  }
}

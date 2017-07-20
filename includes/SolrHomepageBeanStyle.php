<?php

/**
 * @file
 * Bean style for homepage solr results.
 *
 * Shows first 5 results as teasers, and the rest grouped by month.
 */

class SolrHomepageBeanStyle extends BeanStyle {
  /**
   * Solr search page reference.
   *
   * @param array
   */
  protected $search_page;

  /**
   * Implements parent::prepareView().
   */
  public function prepareView($build, $bean) {
    parent::prepareView($build, $bean);

    $anchor = drupal_html_id($bean->delta);
    $build['search']['anchor'] = array(
      '#markup' => "<div class='solr-bean-anchor' id='$anchor'></div>",
      '#weight' => -1,
    );

    if (empty($build['search']['search_results']['#results'])) {
      return array();
    }

    $results = $build['search']['search_results']['#results'];

    // Store reference to solr search page.
    $this->search_page = $build['search']['search_results']['#search_page'];

    if ($results){
      foreach ($results as $result) {
        $node = node_load($result['node']->entity_id);
        $results_build[] = node_view($node, 'teaser');
      }
    }

    $build['search']['search_results'] = $results_build;

    if (!empty($bean->settings['pager'])) {
      $build['search']['pager'] = array(
        '#theme' => 'pager',
        '#element' => $bean->settings['pager_element'],
        '#parameters' => array('bean_element' => $anchor),
        '#weight' => 100,
      );
    }

    return $build;
  }

  /**
   * Determine if teaser items should be shown.
   *
   * Returns true if site has been configured to show teasers and viewing the
   * first page of results.
   *
   * @return bool
   *   TRUE if viewing first page of results.
   */
  protected function showTeasers() {
    $query = apachesolr_current_query($this->search_page['env_id']);

    return variable_get('solr_homepage_show_teasers', TRUE)
      && isset($query->page)
      && $query->page === 0;
  }
}

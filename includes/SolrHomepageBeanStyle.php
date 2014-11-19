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

    if (empty($build['search']['search_results']['#results'])) {
      return array();
    }

    $results = $build['search']['search_results']['#results'];

    // Store reference to solr search page.
    $this->search_page = $build['search']['search_results']['#search_page'];

    $num_teaser = 0;

    // Handle items here instead of prepareItems() in order to handle
    // differences between initial results and grouped results.
    if ($this->showTeasers()) {
      $num_teaser = variable_get('solr_homepage_teaser_count', 5);
      $results_teaser = array_slice($results, 0, $num_teaser);
      foreach ($results_teaser as $result) {
        $node = node_load($result['node']->entity_id);
        $results_build[] = node_view($node, 'teaser');
      }
    }

    // Build grouped by month items.
    $results_by_month = array_slice($results, $num_teaser);
    if ($results_by_month) {
      $curr_month = format_date($results_by_month[0]['node']->created, 'custom', 'F');
      $results_build[$curr_month] = array(
        '#prefix' => '<h3 class="month-title">' . $curr_month . ' <strong>Archives</strong></h3>',
      );
      foreach ($results_by_month as $result) {
        $node = node_load($result['node']->entity_id);
        $month = format_date($node->created, 'custom', 'F');
        if ($month !== $curr_month) {
          $curr_month = $month;
          $results_build[$curr_month] = array(
            '#prefix' => '<h3 class="month-title">' . $curr_month . ' <strong>Archives</strong></h3>',
          );
        }
        $results_build[$curr_month][] = node_view($node, 'title_list');
      }
    }

    $build['search']['search_results'] = $results_build;

    if (!empty($bean->settings['pager'])) {
      $build['search']['pager'] = array(
        '#theme' => 'pager',
        '#element' => $bean->settings['pager_element'],
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

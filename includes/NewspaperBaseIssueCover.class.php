<?php
/**
 * @file
 * Issue cover bean
 */

/**
 * Issue Cover
 *
 * Provides a carousel like issue listing all issues on the site.
 */
class NewspaperBaseIssueCover extends BeanPlugin {
  /**
   * Implements parent::values().
   */
  public function values() {
    $values = parent::values();
    $values['tid'] = '';
    return $values;
  }

  /**
   * Implements parent::form().
   */
  public function form($bean, $form, &$form_state) {
    $vocabulary = taxonomy_vocabulary_machine_name_load('issue');
    $terms = taxonomy_get_tree($vocabulary->vid);
    $options = array();
    foreach ($terms as $term) {
      $options[$term->tid] = $term->name;
    }
    $form['tid'] = array(
      '#title' => t('Issue'),
      '#type' => 'select',
      '#options' => array('' => t('Current Issue')) + $options,
      '#default_value' => !empty($bean->tid) ? $bean->tid : '',
      '#description' => 'Select the issue to show. Leave blank to show the latest current issue.',
    );
    return $form;
  }

  /**
   * Implements parent::view().
   */
  public function view($bean, $content, $view_mode = 'default', $langcode = NULL) {
    // Load up current (lowest weighted) term if not explicitly set.
    if (!$bean->tid) {
      $vocabulary = taxonomy_vocabulary_machine_name_load('issue');
      $terms = taxonomy_get_tree($vocabulary->vid);
      $term = array_shift($terms);
      if ($term) {
        $term = taxonomy_term_load($term->tid);
      }
    }
    else {
      $term = taxonomy_term_load($bean->tid);
    }

    if ($term) {
      $items = array();
      $nav = array();

      // Load up previous term for navigation.
      // @TODO remove previous functionality if no future need (homepage redesign 2017)
      //$previous_tid = newspaper_base_get_sibling_tid($term, 'previous');
      $previous_tid = NULL;
      if ($previous_tid) {

        $previous_term = taxonomy_term_load($previous_tid);
        $items[] = array(
          'data-tid' => $previous_term->tid,
          'class' => array('prev'),
          'data' => theme('newspaper_base_issue_cover', array('term' => $previous_term, 'style')),
        );
      }

      $items[] = array(
        'data-tid' => $term->tid,
        'class' => array('current'),
        'data' => theme('newspaper_base_issue_cover', array('term' => $term, 'style' => 'button')),
      );

      // Load up next term for navigation.
      // @TODO remove next functionality if no future need (homepage redesign 2017)
      //$next_tid = newspaper_base_get_sibling_tid($term, 'next');
      $next_tid = NULL;
      if ($next_tid) {

        $next_term = taxonomy_term_load($next_tid);
        $items[] = array(
          'data-tid' => $next_term->tid,
          'class' => array('next'),
          'data' => theme('newspaper_base_issue_cover', array('term' => $next_term)),
        );
      }

      // If there is more than one issue, show navigation.
      // @TODO remove navigation functionality if no future need (homepage redesign 2017)
      if (count($items) > 1) {
        $nav[] = array(
          '#theme' => 'link',
          '#text' => t('<span>&larr;</span>'),
          '#path' => '',
          '#options' => array(
            'html' => TRUE,
            'attributes' => array(
              'class' => array('previous'),
              'title' => t('Previous issue'),
            ),
          ),
        );

        $nav[] = array(
          '#theme' => 'link',
          '#text' => t('<span>&rarr;</span>'),
          '#path' => '',
          '#options' => array(
            'html' => TRUE,
            'attributes' => array(
              'class' => array('next'),
              'title' => t('Next issue'),
            ),
          ),
        );
      }

      if ($nav) {
        $content['bean'][$bean->delta]['nav'] = array(
          '#weight' => 10,
          '#prefix' => '<div class="navigation">',
          '#suffix' => '</div>',
          'links' => $nav,
        );
      }

      // @TODO remove navigation and additional cover functionality if no future need (homepage redesign 2017)
      // $content['bean'][$bean->delta]['sentinel'] = array(
      //   '#theme' => 'item_list',
      //   '#items' => array_slice($items, -1, 1, TRUE),
      //   '#attributes' => array(
      //     'class' => array('latest-issue'),
      //   ),
      // );

      // $content['bean'][$bean->delta]['covers'] = array(
      //   '#theme' => 'item_list',
      //   '#items' => $items,
      //   '#attributes' => array(
      //     'class' => array('issues'),
      //   ),
      //   '#attached' => array(
      //     'js' => array(
      //       drupal_get_path('module', 'newspaper_base') . '/js/drupal.issue-cover.js' => array(),
      //     ),
      //   ),
      // );
      // kpr($items);

      $content['cover'] = array(
        '#markup' => $items[0]['data'],
        '#attributes' => array(
          'class' => array('issues'),
        ),
      );
    }

    return $content;
  }

}

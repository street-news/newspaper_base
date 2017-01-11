<?php
/**
 * @file
 * newspaper_base.context.inc
 */

/**
 * Implements hook_context_default_contexts().
 */
function newspaper_base_context_default_contexts() {
  $export = array();

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'archive';
  $context->description = 'Provides sidebar filters for the archive page.';
  $context->tag = '';
  $context->conditions = array(
    'frontend' => array(
      'values' => array(
        1 => 1,
      ),
    ),
    'path' => array(
      'values' => array(
        'archive*' => 'archive*',
      ),
    ),
  );
  $context->reactions = array(
    'block' => array(
      'blocks' => array(
        'apachesolr_search-sort' => array(
          'module' => 'apachesolr_search',
          'delta' => 'sort',
          'region' => 'content',
          'weight' => '',
        ),
        'facetapi-ZqbUzDvO7XzYptjGl0qM9JYi3DKAzqJw' => array(
          'module' => 'facetapi',
          'delta' => 'ZqbUzDvO7XzYptjGl0qM9JYi3DKAzqJw',
          'region' => 'sidebar_second',
          'weight' => '',
        ),
        'facetapi-n18E4x0qRRaIP7nV6I0SDYiCJsq77HCB' => array(
          'module' => 'facetapi',
          'delta' => 'n18E4x0qRRaIP7nV6I0SDYiCJsq77HCB',
          'region' => 'sidebar_second',
          'weight' => '',
        ),
        'facetapi-1TVdxEwSQspvQXafDwW1FLP1hGVPVkB0' => array(
          'module' => 'facetapi',
          'delta' => '1TVdxEwSQspvQXafDwW1FLP1hGVPVkB0',
          'region' => 'sidebar_second',
          'weight' => '',
        ),
        'facetapi-8o8kdtP8CKjahDIu1Wy5LGxnDHg3ZYnT' => array(
          'module' => 'facetapi',
          'delta' => '8o8kdtP8CKjahDIu1Wy5LGxnDHg3ZYnT',
          'region' => 'sidebar_second',
          'weight' => '',
        ),
        'facetapi-i5eUJ9YMn0qIHH1koTimiOlHHR1ZjYmj' => array(
          'module' => 'facetapi',
          'delta' => 'i5eUJ9YMn0qIHH1koTimiOlHHR1ZjYmj',
          'region' => 'sidebar_second',
          'weight' => '',
        ),
      ),
    ),
  );
  $context->condition_mode = 1;

  // Translatables
  // Included for use with string extractors like potx.
  t('Provides sidebar filters for the archive page.');
  $export['archive'] = $context;

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'global';
  $context->description = 'Provides site-wide blocks such as the header, footer, and main content.';
  $context->tag = '';
  $context->conditions = array(
    'frontend' => array(
      'values' => array(
        1 => 1,
      ),
    ),
  );
  $context->reactions = array(
    'block' => array(
      'blocks' => array(
        'ombucleanup-site_logo' => array(
          'module' => 'ombucleanup',
          'delta' => 'site_logo',
          'region' => 'header',
          'weight' => '-10',
        ),
        'ombucleanup-page_title' => array(
          'module' => 'ombucleanup',
          'delta' => 'page_title',
          'region' => 'content',
          'weight' => '-31',
        ),
        'system-main' => array(
          'module' => 'system',
          'delta' => 'main',
          'region' => 'content',
          'weight' => '-25',
        ),
      ),
    ),
  );
  $context->condition_mode = 0;

  // Translatables
  // Included for use with string extractors like potx.
  t('Provides site-wide blocks such as the header, footer, and main content.');
  $export['global'] = $context;

  return $export;
}

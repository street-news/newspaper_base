<?php
/**
 * @file
 * Template for a feed list
 *
 * Variables:
 *   $items array, an array of item data
 *     Each item has the keys:
 *       - title
 *       - description
 *       - link (url)
 *       - pubDate
 */
?>
<ul>
  <?php foreach ($items as $i => $item): ?>
    <li>
        <?php print l($item['title'], $item['link']); ?><br />
    </li>
  <?php endforeach; ?>
</ul>

<h2><?php print t('Browse by Issue') ?></h2>

<?php if (!empty($issues)): ?>
<ul class="row">
  <?php foreach ($issues as $issue): ?>
  <li class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
    <?php print render($issue) ?>
  </li>
  <?php endforeach ?>
</ul>
<?php endif ?>

<?php print render($pager) ?>

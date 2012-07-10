<?php if($view_mode != 'taxonomy_term_image'): ?>
<div id="taxonomy-term-<?php print $term->tid; ?>" class="<?php print $classes; ?>">

  <?php if (!$page): ?>
    <h2><a href="<?php print $term_url; ?>"><?php print $term_name; ?></a></h2>
  <?php endif; ?>

  <div class="content">
    <?php print render($content); ?>
  </div>

</div>
<?php else: ?>
  <?php if(!empty($content['field_term_image'])): ?>
    <h2>
      <a href="<?php print $term_url; ?>">
      <?php print render($content['field_term_image'])?></a>
    </h2>
  <?php endif; ?>
<?php endif; ?>
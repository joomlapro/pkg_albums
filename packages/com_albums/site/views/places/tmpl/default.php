<?php
/**
 * @package     Albums
 * @subpackage  com_albums
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Load Stylesheet.
JHtml::stylesheet('com_albums/frontend.css', false, true, false);

// Add JavaScript Frameworks.
JHtml::_('jquery.framework');

// Load the tooltip bootstrap script.
JHtml::_('bootstrap.tooltip');
?>
<div class="albums place-list<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<?php foreach ($this->items as $item): ?>
		<div class="row-fluid">
			<div class="span5">
				<div class="image">
					<a href="<?php echo $item->link; ?>" class="thumbnail">
						<?php if ($item->banner): ?>
							<?php echo JHtml::_('image', JURI::root() . $item->banner, $item->name, array('title' => $item->name, 'class' => 'hasTooltip'), true); ?>
						<?php else: ?>
							<?php echo JHtml::_('image', 'com_albums/300x120.gif', $item->name, null, true); ?>
						<?php endif ?>
					</a>
				</div>
			</div>
			<div class="span7">
				<h2>
					<a href="<?php echo $item->link; ?>"><?php echo $this->escape($item->name); ?></a>
				</h2>
				<p class="category">
					<span class="label"><?php echo $this->escape($item->category_title); ?></span>
				</p>
				<a href="#" class="btn btn-success"><i class="icon-flag"></i> <?php echo JText::_('COM_ALBUMS_SEE_EVENTS'); ?></a>
			</div>
		</div>
		<hr class="soften">
	<?php endforeach; ?>

	<?php if ($this->params->get('show_pagination', 1)): ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)): ?>
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
</div>

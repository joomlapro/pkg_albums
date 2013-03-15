<?php
/**
 * @package     Albums
 * @subpackage  com_albums
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Load the backend helper.
require_once JPATH_ADMINISTRATOR . '/components/com_albums/helpers/albums.php';

// Load Stylesheet.
JHtml::stylesheet('com_albums/frontend.css', false, true, false);
?>
<div class="albums album-list<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<?php $groups = array_chunk($this->items, 3); ?>
	<?php foreach ($groups as $group): ?>
		<div class="row-fluid">
			<?php foreach ($group as $item): ?>
				<div class="span4">
					<div class="album">
						<a href="<?php echo $item->link; ?>">
							<div class="image">
								<?php
								$image = AlbumsHelper::getFirstPicture($item->id, 3);

								echo JHtml::_('image', $image, $item->title, array('title' => 'Hello'), true);
								?>
								<div class="overlay">
									<i class="icon-picture"></i>
								</div>
							</div>
							<div class="body">
								<h3><?php echo $this->escape($item->title); ?></h3>
								<p><?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC1')); ?></p>
							</div>
						</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
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

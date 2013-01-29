<?php
/**
 * @package     Albums
 * @subpackage  com_albums
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Get the current user object.
$user = JFactory::getUser();
?>
<div class="row-fluid">
	<div class="span2">
		<div class="sidebar-nav">
			<ul class="nav nav-list">
				<li class="nav-header"><?php echo JText::_('COM_ALBUMS_HEADER_SUBMENU'); ?></li>
				<li class="active"><a href="<?php echo $this->baseurl; ?>/index.php?option=com_albums"><?php echo JText::_('COM_ALBUMS_LINK_DASHBOARD'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_albums&amp;view=albums"><?php echo JText::_('COM_ALBUMS_LINK_ALBUMS'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_albums&amp;view=places"><?php echo JText::_('COM_ALBUMS_LINK_PLACES'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_categories&amp;extension=com_albums"><?php echo JText::_('COM_ALBUMS_LINK_CATEGORIES'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_categories&amp;extension=com_albums.places"><?php echo JText::_('COM_ALBUMS_LINK_CATEGORIES_PLACES'); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="span6">
		<div class="well well-small">
			<div class="module-title nav-header"><?php echo JText::_('COM_ALBUMS_HEADER_ALBUMS'); ?></div>
			<div class="row-striped">
				<?php if (isset($this->albums)): ?>
					<?php foreach ($this->albums as $i => $item): ?>
						<div class="row-fluid">
							<div class="span9">
								<?php if ($item->checked_out): ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time); ?>
								<?php endif; ?>
								<strong class="row-title">
									<?php if (true): ?>
										<a href="<?php echo JRoute::_('index.php?option=com_albums&task=album.edit&id=' . (int) $item->id); ?>"><?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?></a>
									<?php else : ?>
										<?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
									<?php endif; ?>
								</strong>
							</div>
							<div class="span3">
								<span class="small"><i class="icon-calendar"></i> <?php echo JHtml::_('date', $item->created, 'Y-m-d'); ?></span>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="row-fluid">
						<div class="span12">
							<div class="alert"><?php echo JText::_('COM_ALBUMS_NO_MATCHING_RESULTS'); ?></div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="well well-small">
			<div class="module-title nav-header"><?php echo JText::_('COM_ALBUMS_HEADER_PLACES'); ?></div>
			<div class="row-striped">
				<?php if (isset($this->places)): ?>
					<?php foreach ($this->places as $i => $item): ?>
						<div class="row-fluid">
							<div class="span9">
								<?php if ($item->checked_out): ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time); ?>
								<?php endif; ?>
								<strong class="row-title">
									<?php if (true): ?>
										<a href="<?php echo JRoute::_('index.php?option=com_albums&task=place.edit&id=' . (int) $item->id); ?>"><?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?></a>
									<?php else : ?>
										<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>
									<?php endif; ?>
								</strong>
							</div>
							<div class="span3">
								<span class="small"><i class="icon-calendar"></i> <?php echo JHtml::_('date', $item->created, 'Y-m-d'); ?></span>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="row-fluid">
						<div class="span12">
							<div class="alert"><?php echo JText::_('COM_ALBUMS_NO_MATCHING_RESULTS'); ?></div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="well well-small">
			<div class="module-title nav-header"><?php echo JText::_('COM_ALBUMS_HEADER_QUICKICONS'); ?></div>
			<div class="row-striped">
				<?php if (!empty($this->buttons)): ?>
					<?php echo $this->buttons; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

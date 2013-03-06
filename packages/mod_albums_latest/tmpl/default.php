<?php
/**
 * @package     Albums_Latest
 * @subpackage  mod_albums_latest
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Load the tooltip bootstrap script.
JHtml::_('bootstrap.tooltip');
?>
<div class="row-striped">
	<?php if (count($list)): ?>
		<?php foreach ($list as $i => $item): ?>
			<div class="row-fluid">
				<div class="span9">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, '', false); ?>
					<?php if ($item->checked_out): ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time); ?>
					<?php endif; ?>
					<strong class="row-title">
						<?php if ($item->link):?>
							<a href="<?php echo $item->link; ?>"><?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?></a>
						<?php else:
							echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8');
						endif; ?>
					</strong>
					<small class="small" class="hasTooltip" title="<?php echo JText::_('MOD_ALBUMS_LATEST_CREATED_BY'); ?>">
						<?php echo $item->author_name; ?>
					</small>
				</div>
				<div class="span3">
					<span class="small"><i class="icon-calendar"></i> <?php echo JHtml::_('date', $item->created, 'Y-m-d'); ?></span>
				</div>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="row-fluid">
			<div class="span12">
				<div class="alert"><?php echo JText::_('MOD_ALBUMS_LATEST_NO_MATCHING_RESULTS'); ?></div>
			</div>
		</div>
	<?php endif; ?>
</div>

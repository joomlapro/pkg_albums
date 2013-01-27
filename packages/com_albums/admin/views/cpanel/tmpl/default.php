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
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_albums&amp;view=partners"><?php echo JText::_('COM_ALBUMS_LINK_PARTNERS'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_albums&amp;view=schedules"><?php echo JText::_('COM_ALBUMS_LINK_SCHEDULES'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_categories&amp;extension=com_albums"><?php echo JText::_('COM_ALBUMS_LINK_CATEGORIES'); ?></a></li>
				<li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_categories&amp;extension=com_albums.partners"><?php echo JText::_('COM_ALBUMS_LINK_CATEGORIES_PARTNERS'); ?></a></li>
			</ul>
		</div>
	</div>
	<div class="span6">
		<?php
		foreach ($this->modules as $module)
		{
			$output = JModuleHelper::renderModule($module, array('style' => 'well'));
			$params = new JRegistry;
			$params->loadString($module->params);
			echo $output;
		}
		?>
	</div>
	<div class="span4">
		<?php
		foreach ($this->iconmodules as $iconmodule)
		{
			$output = JModuleHelper::renderModule($iconmodule, array('style' => 'well'));
			$params = new JRegistry;
			$params->loadString($iconmodule->params);
			echo $output;
		}
		?>
	</div>
</div>

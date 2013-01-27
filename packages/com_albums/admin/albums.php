<?php
/**
 * @package     Albums
 * @subpackage  com_albums
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_albums'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Execute the task.
$controller = JControllerLegacy::getInstance('Albums');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

<?php
/**
 * @package     Albums
 * @subpackage  com_albums
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Albums helper.
 *
 * @package     Albums
 * @subpackage  com_albums
 * @since       3.0
 */
class AlbumsHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function addSubmenu($vName = 'cpanel')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_ALBUMS_SUBMENU_CPANEL'),
			'index.php?option=com_albums&view=cpanel',
			$vName == 'cpanel'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_ALBUMS_SUBMENU_ALBUMS'),
			'index.php?option=com_albums&view=albums',
			$vName == 'albums'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_ALBUMS_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_albums',
			$vName == 'categories'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_ALBUMS_SUBMENU_CATEGORIES_PLACES'),
			'index.php?option=com_categories&extension=com_albums.places',
			$vName == 'categories.places'
		);

		if ($vName == 'categories')
		{
			JToolbarHelper::title(
				JText::sprintf('COM_CATEGORIES_CATEGORIES_TITLE', JText::_('com_albums')),
				'albums-categories');
		}
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   int  $categoryId  The category ID.
	 *
	 * @return  JObject  A JObject containing the allowed actions.
	 *
	 * @since   3.0
	 */
	public static function getActions($categoryId = 0)
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		if (empty($categoryId))
		{
			$assetName = 'com_albums';
			$level = 'component';
		}
		else
		{
			$assetName = 'com_albums.category.' . (int) $categoryId;
			$level = 'category';
		}

		$actions = JAccess::getActions('com_albums', $level);

		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}
}

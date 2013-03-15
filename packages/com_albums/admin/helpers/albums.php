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
 * @since       3.1
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
	 * @since   3.1
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
			JText::_('COM_ALBUMS_SUBMENU_PLACES'),
			'index.php?option=com_albums&view=places',
			$vName == 'places'
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
	 * @since   3.1
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

	/**
	 * Method to get the first picture of album.
	 *
	 * @param   integer  $id    The id of the album.
	 * @param   integer  $size  The size of image. (1: full, 2: medium, 3: thumbnail)
	 *
	 * @return  array
	 *
	 * @since   3.1
	 */
	public static function getFirstPicture($id, $size = 1)
	{
		// Predefined sizes.
		$sizes = array(
			1 => 'url',
			2 => 'medium_url',
			3 => 'thumbnail_url'
		);

		if (!array_key_exists($id, $sizes))
		{
			return false;
		}

		// Initialiase variables.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select($sizes[$size]);
		$query->from($db->quoteName('#__albums_images'));
		$query->where($db->quoteName('album_id') . ' = ' . $db->quote((int) $id));
		$query->order($db->quoteName('ordering') . ' ASC LIMIT 0,1');

		// Set the query and load the result.
		$db->setQuery($query);
		$result = $db->loadResult();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
			return null;
		}

		return $result;
	}

	/**
	 * Method to get the place data.
	 *
	 * @param   integer  $id  The id of place.
	 *
	 * @return  array
	 *
	 * @since   3.1
	 */
	public static function getPlace($id)
	{
		// Initialiase variables.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('a.*');
		$query->from($db->quoteName('#__albums_places') . ' AS a');
		$query->where($db->quoteName('a.id') . ' = ' . $db->quote($id));

		// Join over the categories.
		$query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Set the query and load the result.
		$db->setQuery($query);
		$result = $db->loadObject();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
			return null;
		}

		return $result;
	}
}

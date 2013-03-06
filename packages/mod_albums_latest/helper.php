<?php
/**
 * @package     Albums_Latest
 * @subpackage  mod_albums_latest
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include dependancies.
JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_albums/models', 'AlbumsModel');

/**
 * Helper for mod_albums_latest.
 *
 * @package     Albums_Latest
 * @subpackage  mod_albums_latest
 * @since       3.1
 */
abstract class ModAlbumsLatestHelper
{
	/**
	 * Get a list of albums.
	 *
	 * @param   JObject  $params  The module parameters.
	 *
	 * @return  mixed  An array of albums, or false on error.
	 *
	 * @since   3.1
	 */
	public static function getList($params)
	{
		// Get the current user object.
		$user = JFactory::getUser();

		// Get an instance of the generic albums model.
		$model = JModelLegacy::getInstance('Albums', 'AlbumsModel', array('ignore_request' => true));

		// Set List SELECT.
		$model->setState('list.select', 'a.id, a.title, a.checked_out, a.checked_out_time, ' .
				' a.access, a.created, a.created_by, a.created_by_alias, a.featured, a.state');

		// Set Ordering filter.
		switch ($params->get('ordering'))
		{
			case 'm_dsc':
				$model->setState('list.ordering', 'modified DESC, created');
				$model->setState('list.direction', 'DESC');
				break;

			case 'c_dsc':
			default:
				$model->setState('list.ordering', 'created');
				$model->setState('list.direction', 'DESC');
				break;
		}

		// Set Category Filter.
		$categoryId = $params->get('catid');

		if (is_numeric($categoryId))
		{
			$model->setState('filter.category_id', $categoryId);
		}

		// Set User Filter.
		$userId = $user->get('id');

		switch ($params->get('user_id'))
		{
			case 'by_me':
				$model->setState('filter.author_id', $userId);
				break;

			case 'not_me':
				$model->setState('filter.author_id', $userId);
				$model->setState('filter.author_id.include', false);
				break;
		}

		// Set the Start and Limit.
		$model->setState('list.start', 0);
		$model->setState('list.limit', $params->get('count', 5));

		$items = $model->getItems();

		if ($error = $model->getError())
		{
			JError::raiseError(500, $error);
			return false;
		}

		// Set the links.
		foreach ($items as &$item)
		{
			if ($user->authorise('core.edit', 'com_albums.album.' . $item->id))
			{
				$item->link = JRoute::_('index.php?option=com_albums&task=album.edit&id=' . $item->id);
			}
			else
			{
				$item->link = '';
			}
		}

		return $items;
	}

	/**
	 * Get the alternate title for the module.
	 *
	 * @param   JObject  $params  The module parameters.
	 *
	 * @return  string  The alternate title for the module.
	 *
	 * @since   3.1
	 */
	public static function getTitle($params)
	{
		// Initialiase variables.
		$who   = $params->get('user_id');
		$catid = (int) $params->get('catid');
		$type  = $params->get('ordering') == 'c_dsc' ? '_CREATED' : '_MODIFIED';

		if ($catid)
		{
			$category = JCategories::getInstance('Albums')->get($catid);

			if ($category)
			{
				$title = $category->title;
			}
			else
			{
				$title = JText::_('MOD_ALBUMSLATEST_UNEXISTING');
			}
		}
		else
		{
			$title = '';
		}

		return JText::plural('MOD_ALBUMSLATEST_TITLE' . $type . ($catid ? "_CATEGORY" : '') . ($who != '0' ? "_$who" : ''), (int) $params->get('count'), $title);
	}
}

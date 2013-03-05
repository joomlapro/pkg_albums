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
 * Albums Component Route Helper
 *
 * @static
 * @package     Albums
 * @subpackage  com_albums
 * @since       3.1
 */
abstract class AlbumsHelperRoute
{
	protected static $lookup;

	/**
	 * Method to get the menu items for the component.
	 *
	 * @return  array  An array of menu items.
	 *
	 * @since   3.1
	 */
	public static function &getItems()
	{
		static $items;

		// Get the menu items for this component.
		if (!isset($items))
		{
			// Include the site app in case we are loading this from the admin.
			require_once JPATH_SITE . '/includes/application.php';

			$app   = JFactory::getApplication();
			$menu  = $app->getMenu();
			$com   = JComponentHelper::getComponent('com_albums');
			$items = $menu->getItems('component_id', $com->id);

			// If no items found, set to empty array.
			if (!$items)
			{
				$items = array();
			}
		}

		return $items;
	}

	/**
	 * Method to get a route configuration for the album view.
	 *
	 * @param   int  $id     The route of the album.
	 * @param   int  $catid  The id of the category.
	 *
	 * @return  string
	 *
	 * @since   3.1
	 */
	public static function getAlbumRoute($id, $catid)
	{
		// Initialiase variables.
		$needles = array(
			'album' => array((int) $id)
		);

		// Create the link
		$link = 'index.php?option=com_albums&view=album&id=' . $id;

		if ($catid > 1)
		{
			$categories = JCategories::getInstance('Albums');
			$category = $categories->get($catid);

			if ($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid=' . $catid;
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid=' . $item;
		}
		elseif ($item = self::_findItem(array('albums' => array(0))))
		{
			$link .= '&Itemid=' . $item;
		}
		elseif ($item = self::_findItem())
		{
			$link .= '&Itemid=' . $item;
		}

		return $link;
	}

	/**
	 * Method to get a route configuration for the album form view.
	 *
	 * @param   int     $id      The id of the album form.
	 * @param   string  $return  The return page variable.
	 *
	 * @return  string
	 *
	 * @since   3.1
	 */
	public static function getAlbumFormRoute($id, $return = null)
	{
		// Create the link.
		if ($id)
		{
			$link = 'index.php?option=com_albums&task=albumform.edit&a_id=' . $id;
		}
		else
		{
			$link = 'index.php?option=com_albums&task=albumform.add&a_id=0';
		}

		if ($return)
		{
			$link .= '&return=' . $return;
		}

		return $link;
	}

	/**
	 * Method to get a route configuration for the place view.
	 *
	 * @param   int  $id     The route of the place.
	 * @param   int  $catid  The id of the category.
	 *
	 * @return  string
	 *
	 * @since   3.1
	 */
	public static function getPlaceRoute($id, $catid)
	{
		// Initialiase variables.
		$needles = array(
			'place' => array((int) $id)
		);

		// Create the link
		$link = 'index.php?option=com_albums&view=place&id=' . $id;

		if ($catid > 1)
		{
			$categories = JCategories::getInstance('Albums.places');
			$category = $categories->get($catid);

			if ($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid=' . $catid;
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid=' . $item;
		}
		elseif ($item = self::_findItem(array('places' => array(0))))
		{
			$link .= '&Itemid=' . $item;
		}
		elseif ($item = self::_findItem())
		{
			$link .= '&Itemid=' . $item;
		}

		return $link;
	}

	/**
	 * Method to get a route configuration for the place form view.
	 *
	 * @param   int     $id      The id of the place form.
	 * @param   string  $return  The return page variable.
	 *
	 * @return  string
	 *
	 * @since   3.1
	 */
	public static function getPlaceFormRoute($id, $return = null)
	{
		// Create the link.
		if ($id)
		{
			$link = 'index.php?option=com_albums&task=placeform.edit&a_id=' . $id;
		}
		else
		{
			$link = 'index.php?option=com_albums&task=placeform.add&a_id=0';
		}

		if ($return)
		{
			$link .= '&return=' . $return;
		}

		return $link;
	}

	/**
	 * Method to get a route configuration for the category view.
	 *
	 * @param   int  $catid  The id of the category.
	 *
	 * @return  string
	 *
	 * @since   3.1
	 */
	public static function getCategoryRoute($catid)
	{
		if ($catid instanceof JCategoryNode)
		{
			$id = $catid->id;
			$category = $catid;
		}
		else
		{
			$id = (int) $catid;
			$category = JCategories::getInstance('Albums')->get($id);
		}

		if ($id < 1)
		{
			$link = '';
		}
		else
		{
			$needles = array(
				'category' => array($id)
			);

			if ($item = self::_findItem($needles))
			{
				$link = 'index.php?Itemid=' . $item;
			}
			else
			{
				// Create the link
				$link = 'index.php?option=com_albums&view=category&id=' . $id;

				if ($category)
				{
					$catids = array_reverse($category->getPath());
					$needles = array(
						'category' => $catids,
						'categories' => $catids
					);

					if ($item = self::_findItem($needles))
					{
						$link .= '&Itemid=' . $item;
					}
					elseif ($item = self::_findItem())
					{
						$link .= '&Itemid=' . $item;
					}
				}
			}
		}

		return $link;
	}

	/**
	 * Method to find the item.
	 *
	 * @param   array  $needles  The needles to find.
	 *
	 * @return  null
	 *
	 * @since   3.1
	 */
	protected static function _findItem($needles = null)
	{
		// Initialiase variables.
		$app   = JFactory::getApplication();
		$menus = $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component = JComponentHelper::getComponent('com_albums');
			$items     = $menus->getItems('component_id', $component->id);

			if ($items)
			{
				foreach ($items as $item)
				{
					if (isset($item->query) && isset($item->query['view']))
					{
						$view = $item->query['view'];

						if (!isset(self::$lookup[$view]))
						{
							self::$lookup[$view] = array();
						}

						if (isset($item->query['id']))
						{
							self::$lookup[$view][$item->query['id']] = $item->id;
						}
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$view][(int) $id]))
						{
							return self::$lookup[$view][(int) $id];
						}
					}
				}
			}
		}
		else
		{
			$active = $menus->getActive();

			if ($active)
			{
				return $active->id;
			}
		}

		return null;
	}
}

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
 * This models supports retrieving lists of places.
 *
 * @package     Albums
 * @subpackage  com_albums
 * @since       3.1
 */
class AlbumsModelPlaces extends JModelList
{
	/**
	 * Category items data
	 *
	 * @var     array
	 */
	protected $_item = null;

	protected $_children = null;

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   3.1
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_title',
				'state', 'a.state',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'ordering', 'a.ordering',
				'featured', 'a.featured',
				'language', 'a.language',
				'hits', 'a.hits',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Get the configuration options.
		$app    = JFactory::getApplication();
		$input  = $app->input;
		$params = JComponentHelper::getParams('com_albums');
		$user   = JFactory::getUser();

		// Optional filter text.
		$search = $input->getString('filter-search');
		$this->setState('list.filter', $search);

		// List state information.
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'uint');
		$this->setState('list.limit', $limit);

		$limitstart = $input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $limitstart);

		// Load the ordering.
		$orderCol = $input->get('filter_order', 'a.ordering');

		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'a.ordering';
		}

		$this->setState('list.ordering', $orderCol);

		// Load the direction.
		$listOrder = $input->get('filter_order_Dir', 'ASC');

		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}

		$this->setState('list.direction', $listOrder);

		// Load the category.
		$id = $app->input->get('id', 0, 'int');
		$this->setState('category.id', $id);

		// Load the parameters.
		$this->setState('params', $params);

		// Process show_noauth parameter.
		if (!$params->get('show_noauth'))
		{
			$this->setState('filter.access', true);
		}
		else
		{
			$this->setState('filter.access', false);
		}

		if ((!$user->authorise('core.edit.state', 'com_albums')) && (!$user->authorise('core.edit', 'com_albums')))
		{
			// Limit to published for people who can't edit or edit.state.
			$this->setState('filter.state', 1);

			// Filter by start and end dates.
			$this->setState('filter.publish_date', true);
		}

		// Load the language.
		$this->setState('filter.language', $app->getLanguageFilter());

		// Load the layout.
		$this->setState('layout', $input->get('layout'));
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   3.1
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.state');
		$id .= ':' . $this->getState('filter.language');

		return parent::getStoreId($id);
	}

	/**
	 * Method to get a list of items.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 *
	 * @since   3.1
	 */
	public function getItems()
	{
		// Invoke the parent getItems method to get the main list.
		$items = parent::getItems();

		// Convert the params field into an object, saving original in _params.
		for ($i = 0, $n = count($items); $i < $n; $i++)
		{
			$item = &$items[$i];

			if (!isset($this->_params))
			{
				$params = new JRegistry;
				$params->loadString($item->params);
				$item->params = $params;
			}
		}

		return $items;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query.
	 *
	 * @since   3.1
	 */
	protected function getListQuery()
	{
		// Initialiase variables.
		$user   = JFactory::getUser();
		$groups = implode(', ', $user->getAuthorisedViewLevels());

		// Create a new query object.
		$db     = $this->getDbo();
		$query  = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.catid, a.name, a.alias, a.banner, a.checked_out, a.checked_out_time'
				. ', a.hits'
				. ', a.state, a.ordering, a.language, a.access, a.params, a.created, a.created_by_alias'
			)
		);
		$query->from($db->quoteName('#__albums_places') . ' AS a');

		// Join over the users for the author and modified_by names.
		$query->select("CASE WHEN a.created_by_alias > ' ' THEN a.created_by_alias ELSE ua.name END AS author");
		$query->select("ua.email AS author_email");

		$query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');
		$query->join('LEFT', '#__users AS uam ON uam.id = a.modified_by');

		// Join over the categories.
		$query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access IN (' . $groups . ')');
			$query->where('c.access IN (' . $groups . ')');
		}

		// Filter by category.
		if ($categoryId = $this->getState('category.id'))
		{
			$query->where('a.catid = ' . (int) $categoryId);
		}

		// Join to check for category published state in parent categories up the tree.
		$query->select('c.published as cat_published, CASE WHEN badcats.id is null THEN c.published ELSE 0 END AS parents_published');
		$subquery = 'SELECT cat.id as id FROM #__categories AS cat JOIN #__categories AS parent ';
		$subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
		$subquery .= 'WHERE parent.extension = ' . $db->quote('com_albums');

		// Find any up-path categories that are not published.
		// If all categories are published, badcats.id will be null, and we just use the place state.
		$subquery .= ' AND parent.published != 1 GROUP BY cat.id ';

		// Select state to unpublished if up-path category is unpublished.
		$publishedWhere = 'CASE WHEN badcats.id is null THEN a.state ELSE 0 END';
		$query->join('LEFT OUTER', '(' . $subquery . ') AS badcats ON badcats.id = c.id');

		// Filter by state.
		$state = $this->getState('filter.state');

		if (is_numeric($state))
		{
			$query->where('a.state = ' . (int) $state);
		}

		// Filter by search in name.
		$search = $this->getState('list.filter');

		if (!empty($search))
		{
			$search = $db->Quote('%' . $db->escape($search, true) . '%');
			$query->where('(a.name LIKE ' . $search . ')');
		}

		// Filter by language.
		if ($this->getState('filter.language'))
		{
			$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ', ' . $db->quote('*') . ')');
		}

		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.ordering');
		$query->order($db->escape($orderCol) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}

	/**
	 * Method to get category data for the current category
	 *
	 * @return  object
	 *
	 * @since   3.1
	 */
	public function getCategory()
	{
		if (!is_object($this->_item))
		{
			// Initialiase variables.
			$app    = JFactory::getApplication();
			$menu   = $app->getMenu();
			$active = $menu->getActive();
			$params = new JRegistry;

			if ($active)
			{
				$params->loadString($active->params);
			}

			$options = array();
			$options['countItems'] = $params->get('show_cat_items', 1) || $params->get('show_empty_categories', 0);
			$categories = JCategories::getInstance('Albums.places', $options);
			$this->_item = $categories->get($this->getState('category.id', 'root'));

			if (is_object($this->_item))
			{
				$this->_children = $this->_item->getChildren();
				$this->_parent = false;

				if ($this->_item->getParent())
				{
					$this->_parent = $this->_item->getParent();
				}

				$this->_rightsibling = $this->_item->getSibling();
				$this->_leftsibling = $this->_item->getSibling(false);
			}
			else
			{
				$this->_children = false;
				$this->_parent = false;
			}
		}

		return $this->_item;
	}

	/**
	 * Get the child categories.
	 *
	 * @return  mixed  An array of categories or false if an error occurs.
	 *
	 * @since   3.1
	 */
	public function &getChildren()
	{
		if (!is_object($this->_item))
		{
			$this->getCategory();
		}

		return $this->_children;
	}
}

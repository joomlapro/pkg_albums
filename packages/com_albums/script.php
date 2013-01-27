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
 * Script file of Albums Component.
 *
 * @package     Albums
 * @subpackage  com_albums
 * @since       3.0
 */
class com_albumsInstallerScript
{
	/**
	 * Constructor.
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script.
	 *
	 * @return  void
	 */
	public function __constructor(JAdapterInstance $adapter)
	{

	}

	/**
	 * Called before any type of action.
	 *
	 * @param   string            $route    Which action is happening (install|uninstall|discover_install).
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.0
	 */
	public function preflight($route, JAdapterInstance $adapter)
	{

	}

	/**
	 * Called after any type of action.
	 *
	 * @param   string            $route    Which action is happening (install|uninstall|discover_install).
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.0
	 */
	public function postflight($route, JAdapterInstance $adapter)
	{
		$this->_addCategory();
	}

	/**
	 * Called on installation.
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.0
	 */
	public function install(JAdapterInstance $adapter)
	{

	}

	/**
	 * Called on update.
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.0
	 */
	public function update(JAdapterInstance $adapter)
	{

	}

	/**
	 * Called on uninstallation.
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.0
	 */
	public function uninstall(JAdapterInstance $adapter)
	{

	}

	/**
	 * Method to add a default category "uncategorised".
	 *
	 * @return  integer  Id of the created category.
	 *
	 * @since   3.0
	 */
	public function _addCategory()
	{
		// Create categories for our component.
		$basePath = JPATH_ADMINISTRATOR . '/components/com_categories';

		require_once $basePath . '/models/category.php';

		$config   = array('table_path' => $basePath . '/tables');
		$catmodel = new CategoriesModelCategory($config);

		$catData  = array(
			'id'          => 0,
			'parent_id'   => 0,
			'level'       => 1,
			'path'        => 'uncategorised',
			'extension'   => 'com_albums',
			'title'       => 'Uncategorised',
			'alias'       => 'uncategorised',
			'description' => '',
			'published'   => 1,
			'params'      => '{"target":"","image":""}',
			'metadata'    => '{"page_title":"","author":"","robots":""}',
			'language'    => '*'
		);

		$catmodel->save($catData);
		$id = $catmodel->getItem()->id;

		$db = JFactory::getDBO();

		// Updating all albums without category to have this new one.
		$query = $db->getQuery(true);
		$query->update('#__albums');
		$query->set('catid = ' . (int) $id);
		$query->where('catid = 0');
		$db->setQuery($query);
		$db->query();

		// Updating all places without category to have this new one.
		// $query->update('#__albums_places');
		// $query->setQuery($query);
		// $db->query();

		return;
	}
}

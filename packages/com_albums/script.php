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
		// Include dependancies.
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_categories/models', 'CategoriesModel');
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_categories/tables');

		// Get an instance of the generic category model.
		$model = JModelLegacy::getInstance('Category', 'CategoriesModel', array('ignore_request' => true));

		// Attempt to save the category.
		$data  = array(
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

		// Save the data.
		$model->save($data);

		// Initialiase variables.
		$id    = $model->getItem()->id;
		$db    = JFactory::getDBO();

		// Updating all albums without category to have this new one.
		$query = $db->getQuery(true);
		$query->update($db->quoteName('#__albums'));
		$query->set($db->quoteName('catid') . ' = ' . $db->quote((int) $id));
		$query->where($db->quoteName('catid') . ' = ' . $db->quote(0));
		$db->setQuery($query);
		$db->query();

		$data2 = (array) $data;
		$data2['extension'] = 'com_albums.places';

		// Save the data.
		$model->save($data2);

		// Initialiase variables.
		$id    = $model->getItem()->id;

		// Updating all albums without category to have this new one.
		$query->clear();
		$query->update($db->quoteName('#__albums_places'));
		$query->set($db->quoteName('catid') . ' = ' . $db->quote((int) $id));
		$query->where($db->quoteName('catid') . ' = ' . $db->quote(0));
		$db->setQuery($query);
		$db->query();

		return;
	}
}

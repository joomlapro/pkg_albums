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
 * HTML View class for the Cpanel component
 *
 * @package     Albums
 * @subpackage  com_albums
 * @since       3.0
 */
class AlbumsViewCpanel extends JViewLegacy
{
	protected $albums = null;

	protected $places = null;

	protected $buttons = null;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   3.0
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$input = JFactory::getApplication()->input;

		/*
		 * Set the template - this will display cpanel.php
		 * from the selected admin template.
		 */
		$input->set('tmpl', 'cpanel');

		// Get an instance of the generic albums model.
		$model = JModelLegacy::getInstance('Albums', 'AlbumsModel', array('ignore_request' => true));
		$model->setState('list.select', 'a.*');
		$model->setState('list.limit', 5);
		$model->setState('list.ordering', 'a.id');
		$model->setState('list.direction', 'desc');

		$this->albums = $model->getItems();

		// Get an instance of the generic places model.
		$model = JModelLegacy::getInstance('Places', 'AlbumsModel', array('ignore_request' => true));
		$model->setState('list.select', 'a.*');
		$model->setState('list.limit', 5);
		$model->setState('list.ordering', 'a.id');
		$model->setState('list.direction', 'desc');

		$this->places = $model->getItems();

		// Display button list.
		$this->buttons = AlbumsHelper::getButtons();

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	protected function addToolbar()
	{
		// Include dependancies.
		require_once JPATH_COMPONENT . '/helpers/albums.php';

		// Initialise variables.
		$canDo = AlbumsHelper::getActions();

		// Set toolbar items for the page.
		JToolbarHelper::title(JText::_('COM_ALBUMS_MANAGER_CPANEL'), 'cpanel.png');

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_albums');
		}

		JToolBarHelper::help('cpanel', $com = true);
	}
}

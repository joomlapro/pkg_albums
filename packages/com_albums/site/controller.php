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
 * Albums Component Controller
 *
 * @package     Albums
 * @subpackage  com_albums
 * @since       3.1
 */
class AlbumsController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 * @since   3.1
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// Initialise variables.
		$cachable = true;
		$user = JFactory::getUser();

		// Set the default view name and format from the Request.
		// Note we are using a_id to avoid collisions with the router and the return page.
		$id    = $this->input->getInt('a_id');
		$vName = $this->input->get('view', 'categories');
		$this->input->set('view', $vName);

		if ($user->get('id') || ($this->input->getMethod() == 'POST' && $vName = 'categories'))
		{
			$cachable = false;
		}

		$safeurlparams = array(
			'id'               => 'INT',
			'limit'            => 'UINT',
			'limitstart'       => 'UINT',
			'filter_order'     => 'CMD',
			'filter_order_Dir' => 'CMD',
			'lang'             => 'CMD'
		);

		// Check for edit form.
		if (($vName == 'albumform' && !$this->checkEditId('com_albums.edit.album', $id))
			|| ($vName == 'placeform' && !$this->checkEditId('com_albums.edit.place', $id)))
		{
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}

		return parent::display($cachable, $safeurlparams);
	}
}

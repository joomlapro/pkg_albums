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
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 * @since   3.1
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// Load the backend helper.
		require_once JPATH_ADMINISTRATOR . '/components/com_albums/helpers/upload.php';

		// Initialiase variables.
		$app   = JFactory::getApplication();
		$input = $app->input;

		// Use the correct json mime-type.
		header('Content-Type: application/json');

		$upload = new UploadHelper;

		$app->close();
	}
}

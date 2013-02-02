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
 * Albums Component Category Tree
 *
 * @static
 * @package     Albums
 * @subpackage  com_albums
 * @since       3.0
 */
class AlbumsCategories extends JCategories
{
	/**
	 * Class constructor
	 *
	 * @param   array  $options  Array of options
	 *
	 * @since   3.0
	 */
	public function __construct($options = array())
	{
		$options['table']        = '#__albums';
		$options['extension']    = 'com_albums';
		$options['statefield']   = 'state';
		// $options['countItems']   = 1;
		// $options['allLanguages'] = true;

		parent::__construct($options);
	}
}

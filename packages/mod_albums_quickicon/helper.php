<?php
/**
 * @package     Albums_Quickicon
 * @subpackage  mod_albums_quickicon
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Helper for mod_albums_quickicon.
 *
 * @package     Albums_Quickicon
 * @subpackage  mod_albums_quickicon
 * @since       3.1
 */
abstract class ModAlbumsQuickIconHelper
{
	/**
	 * Stack to hold buttons.
	 *
	 * @since   3.1
	 */
	protected static $buttons = array();

	/**
	 * Helper method to return button list.
	 *
	 * This method returns the array by reference so it can be
	 * used to add custom buttons or remove default ones.
	 *
	 * @param   JRegistry  $params  The module parameters.
	 *
	 * @return  array  An array of buttons.
	 *
	 * @since   3.1
	 */
	public static function &getButtons($params)
	{
		// Initialiase variables.
		$key = (string) $params;

		if (!isset(self::$buttons[$key]))
		{
			$context = $params->get('context', 'mod_albums_quickicon');

			if ($context == 'mod_albums_quickicon')
			{
				// Load mod_albums_quickicon language file in case this method is called before rendering the module.
				JFactory::getLanguage()->load('mod_albums_quickicon');

				self::$buttons[$key] = array(
					array(
						'link' => JRoute::_('index.php?option=com_albums&task=album.add'),
						'image' => 'file-add',
						'text' => JText::_('MOD_ALBUMS_QUICKICON_ADD_NEW_ALBUM'),
						'access' => array('core.manage', 'com_content', 'core.create', 'com_content', )
					),
					array(
						'link' => JRoute::_('index.php?option=com_albums&task=place.add'),
						'image' => 'file-add',
						'text' => JText::_('COM_ALBUMS_QUICKICON_ADD_NEW_PLACE'),
						'access' => array('core.manage', 'com_albums')
					),
					array(
						'link' => JRoute::_('index.php?option=com_categories&view=category&layout=edit&extension=com_albums'),
						'image' => 'folder',
						'text' => JText::_('MOD_ALBUMS_QUICKICON_ADD_NEW_ALBUM_CATEGORY'),
						'access' => array('core.manage', 'com_content')
					),
					array(
						'link' => JRoute::_('index.php?option=com_categories&view=category&layout=edit&extension=com_albums.places'),
						'image' => 'folder',
						'text' => JText::_('MOD_ALBUMS_QUICKICON_ADD_NEW_PLACE_CATEGORY'),
						'access' => array('core.manage', 'com_content')
					),
				);
			}
			else
			{
				self::$buttons[$key] = array();
			}
		}

		return self::$buttons[$key];
	}

	/**
	 * Get the alternate title for the module.
	 *
	 * @param   JRegistry  $params  The module parameters.
	 * @param   object     $module  The module.
	 *
	 * @return  string  The alternate title for the module.
	 */
	public static function getTitle($params, $module)
	{
		// Initialiase variables.
		$key = $params->get('context', 'mod_albums_quickicon') . '_title';

		if (JFactory::getLanguage()->hasKey($key))
		{
			return JText::_($key);
		}
		else
		{
			return $module->title;
		}
	}
}

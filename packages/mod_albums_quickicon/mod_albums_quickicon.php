<?php
/**
 * @package     Albums_Quickicon
 * @subpackage  mod_albums_quickicon
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the albums quickicon functions only once.
require_once __DIR__ . '/helper.php';

// Get the albums quickicon.
$buttons = ModAlbumsQuickIconHelper::getButtons($params);

// Initialise variables.
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

// Render the module.
require JModuleHelper::getLayoutPath('mod_albums_quickicon', $params->get('layout', 'default'));

<?php
/**
 * @package     Albums_Latest
 * @subpackage  mod_albums_latest
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the mod_albums_latest functions only once.
require_once __DIR__ . '/helper.php';

// Get module data.
$list = ModAlbumsLatestHelper::getList($params);

// Initialise variables.
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

// Render the module.
require JModuleHelper::getLayoutPath('mod_albums_latest', $params->get('layout', 'default'));

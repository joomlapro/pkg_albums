<?php
/**
 * @package     Albums_Quickicon
 * @subpackage  mod_albums_quickicon
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$html = JHtml::_('icons.buttons', $buttons);
?>
<?php if (!empty($html)): ?>
	<div class="row-striped">
		<?php echo $html; ?>
	</div>
<?php endif;

<?php
/**
 * @package     Albums
 * @subpackage  com_albums
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
?>
<div class="tab-pane" id="address">
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('address_zipcode'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('address_zipcode'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('address_street'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('address_street'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('address_district'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('address_district'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('address_city'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('address_city'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('address_state'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('address_state'); ?></div>
	</div>
</div>

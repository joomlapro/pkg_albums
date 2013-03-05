<?php
/**
 * @package     Albums
 * @subpackage  com_albums
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the tooltip behavior script.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Add JavaScript Frameworks.
JHtml::_('jquery.framework');

// Load JavaScript.
JHtml::script('com_albums/jquery.meio.mask.min.js', false, true);
JHtml::script('com_albums/jquery.custom.js', false, true);
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task == 'place.cancel' || document.formvalidator.isValid(document.id('place-form'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('place-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_albums&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="place-form" class="form-validate">
	<div class="row-fluid">
		<!-- Begin Places -->
		<div class="span10 form-horizontal">
			<fieldset>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#details" data-toggle="tab"><?php echo empty($this->item->id) ? JText::_('COM_ALBUMS_NEW_PLACE') : JText::sprintf('COM_ALBUMS_EDIT_PLACE', $this->item->id); ?></a></li>
					<?php $fieldSets = $this->form->getFieldsets('address');
					foreach ($fieldSets as $name => $fieldSet): ?>
						<li><a href="#address-<?php echo $name; ?>" data-toggle="tab"><?php echo JText::_($fieldSet->label); ?></a></li>
					<?php endforeach; ?>
					<li><a href="#publishing" data-toggle="tab"><?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></a></li>
					<?php $fieldSets = $this->form->getFieldsets('params');
					foreach ($fieldSets as $name => $fieldSet): ?>
						<li><a href="#params-<?php echo $name; ?>" data-toggle="tab"><?php echo JText::_($fieldSet->label); ?></a></li>
					<?php endforeach; ?>
					<?php $fieldSets = $this->form->getFieldsets('metadata');
					foreach ($fieldSets as $name => $fieldSet): ?>
						<li><a href="#metadata-<?php echo $name; ?>" data-toggle="tab"><?php echo JText::_($fieldSet->label); ?></a></li>
					<?php endforeach; ?>
					<?php if ($this->canDo->get('core.admin')): ?>
						<li><a href="#permissions" data-toggle="tab"><?php echo JText::_('COM_ALBUMS_FIELDSET_RULES');?></a></li>
					<?php endif; ?>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('banner'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('banner'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('phone'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('phone'); ?></div>
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('email'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('email'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('website'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('website'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('ordering'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('ordering'); ?></div>
								</div>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
						</div>
					</div>
					<?php echo $this->loadTemplate('address'); ?>
					<div class="tab-pane" id="publishing">
						<div class="row-fluid">
							<div class="span6">
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
								</div>
								<?php if ($this->item->id): ?>
									<div class="control-group">
										<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
										<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
									</div>
								<?php endif; ?>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('created_by_alias'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('created_by_alias'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
								</div>
							</div>
							<div class="span6">
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('version'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('version'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('modified_by'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('modified_by'); ?></div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('modified'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('modified'); ?></div>
								</div>
								<?php if ($this->item->hits): ?>
									<div class="control-group">
										<div class="control-label"><?php echo $this->form->getLabel('hits'); ?></div>
										<div class="controls"><?php echo $this->form->getInput('hits'); ?></div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php echo $this->loadTemplate('params'); ?>
					<?php echo $this->loadTemplate('metadata'); ?>
					<?php if ($this->canDo->get('core.admin')): ?>
						<div class="tab-pane" id="permissions">
							<fieldset>
								<?php echo $this->form->getInput('rules'); ?>
							</fieldset>
						</div>
					<?php endif; ?>
				</div>
			</fieldset>
			<div>
				<input type="hidden" name="task" value="" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
		<!-- End Places -->
		<!-- Begin Sidebar -->
		<div class="span2">
			<h4><?php echo JText::_('JDETAILS'); ?></h4>
			<hr />
			<fieldset class="form-vertical">
				<div class="control-group">
					<div class="controls"><?php echo $this->form->getValue('name'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('access'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('featured'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('featured'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('language'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('language'); ?></div>
				</div>
			</fieldset>
		</div>
		<!-- End Sidebar -->
	</div>
</form>

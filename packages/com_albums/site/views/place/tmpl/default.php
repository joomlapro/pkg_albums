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

// Load the backend helper.
require_once JPATH_ADMINISTRATOR . '/components/com_albums/helpers/mask.php';

// Create shortcuts to some parameters.
$params  = $this->item->params;
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();

// Add JavaScript Frameworks.
JHtml::_('jquery.framework');

// Load the tooltip behavior script.
JHtml::_('behavior.caption');

// Load JavaScript.
JHtml::script('http://maps.google.com/maps/api/js?sensor=true', false, true);
JHtml::script('com_albums/gmaps.js', false, true);

if ($this->item->address && $this->item->address['street'])
{
	// Load the parameters.
	$params = JComponentHelper::getParams('com_albums');

	// Get address coordinates.
	$latitude  = isset($this->item->address['latitude']) ? $this->item->address['latitude'] : $params->get('latitude', 0);
	$longitude = isset($this->item->address['longitude']) ? $this->item->address['longitude'] : $params->get('longitude', 0);
	$zoom      = isset($this->item->address['zoom']) ? $this->item->address['zoom'] : $params->get('zoom', 0);
}
?>
<?php if ($this->item->address && $this->item->address['street']): ?>
	<script type="text/javascript">
		jQuery.noConflict();

		(function ($) {
			$(function () {
				var map;
				var html = '';

				html += '<div class="map">';
				html += '<h4><?php echo $this->item->name; ?></h4>';
				html += '<address>';
				html += '<strong><?php echo $this->escape($this->item->address["street"]); ?></strong><br>';
				html += '<?php if ($this->item->address["district"]): ?><?php echo $this->escape($this->item->address["district"]); ?><br><?php endif ?>';
				html += '<?php echo $this->escape($this->item->address["city"]); ?>, <?php echo $this->escape($this->item->address["state"]); ?> ';
				html += '<?php echo $this->escape($this->item->address["zipcode"]); ?><br>';
				html += '</address>';
				html += '</div>';

				map = new GMaps({
					div: '#map',
					lat: '<?php echo $latitude; ?>',
					lng: '<?php echo $longitude; ?>'
				});

				map.addMarker({
					lat: '<?php echo $latitude; ?>',
					lng: '<?php echo $longitude; ?>',
					title: '<?php echo $this->item->name; ?>',
					infoWindow: {
						content: html
					}
				});
			});
		})(jQuery);
	</script>
	<style type="text/css">
		#map {
			width: 100%;
			height: 300px;
			margin: 20px 0;
		}
		/* Fixes for Twitter Bootstrap */
		#map label {
			display: inline-block;
		}
		#map img {
			max-width: inherit;
		}
		/* infoWindow */
		.map h4 {
			margin-top: 0;
		}
		.map address {
			margin-bottom: 0;
		}
	</style>
<?php endif; ?>
<div class="albums place-item<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<?php if (!$this->print): ?>
		<?php if ($canEdit || $params->get('show_print_icon', 1) || $params->get('show_email_icon', 1)): ?>
			<div class="btn-group pull-right">
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog"></i> <span class="caret"></span></a>
				<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
				<ul class="dropdown-menu actions">
					<?php if ($params->get('show_print_icon', 1)): ?>
						<li class="print-icon"><?php echo JHtml::_('placeicon.print_popup', $this->item, $params); ?></li>
					<?php endif; ?>
					<?php if ($params->get('show_email_icon', 1)): ?>
						<li class="email-icon"><?php echo JHtml::_('placeicon.email', $this->item, $params); ?></li>
					<?php endif; ?>
					<?php if ($canEdit): ?>
						<li class="edit-icon"><?php echo JHtml::_('placeicon.edit', $this->item, $params); ?></li>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<div class="pull-right">
			<?php echo JHtml::_('placeicon.print_screen', $this->item, $params); ?>
		</div>
	<?php endif; ?>

	<div class="page-header">
		<h2>
			<?php echo $this->escape($this->item->name); ?>
			<span class="label"><?php echo $this->escape($this->item->category_title); ?></span>
		</h2>
	</div>

	<div class="row-fluid">
		<div class="span7">
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th><?php echo JText::_('COM_ALBUMS_PHONE'); ?></th>
						<td><?php echo MaskHelper::mask($this->item->phone, 'phone'); ?></td>
					</tr>
					<tr>
						<th><?php echo JText::_('JGLOBAL_EMAIL'); ?></th>
						<td><?php echo JHtml::_('email.cloak', $this->item->email); ?></td>
					</tr>
					<tr>
						<th><?php echo JText::_('COM_ALBUMS_WEBSITE'); ?></th>
						<td><?php echo JHtml::_('link', $this->item->website, $this->item->website, array('target' => '_blank')); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="span5">
			<div class="image">
				<a href="<?php echo $this->item->link; ?>" class="thumbnail">
					<?php if ($this->item->banner): ?>
						<?php echo JHtml::_('image', JURI::root() . $this->item->banner, $this->item->name, null, true); ?>
					<?php else: ?>
						<?php echo JHtml::_('image', 'com_albums/300x120.gif', $this->item->name, null, true); ?>
					<?php endif ?>
				</a>
			</div>
		</div>
	</div>
	<?php if ($this->item->address && $this->item->address['street']): ?>
		<div class="row-fluid">
			<div class="span12">
				<div id="map"></div>
			</div>
		</div>
	<?php endif; ?>
	<div class="row-fluid">
		<div class="span7">
			<?php if ($this->item->description): ?>
				<h3><?php echo JText::_('COM_ALBUMS_HEADING_DESCRIPTION'); ?></h3>
				<div class="desc">
					<?php echo $this->item->description; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="span5">
			<?php if ($this->item->address && $this->item->address['street']): ?>
				<h3><?php echo JText::_('COM_ALBUMS_HEADING_ADDRESS'); ?></h3>
				<address>
					<strong><?php echo $this->escape($this->item->address['street']); ?></strong><br>
					<?php if ($this->item->address['district']): ?>
						<?php echo $this->escape($this->item->address['district']); ?><br>
					<?php endif ?>
					<?php echo $this->escape($this->item->address['city']); ?>, <?php echo $this->escape($this->item->address['state']); ?> <?php echo $this->escape($this->item->address['zipcode']); ?><br>
				</address>
			<?php endif; ?>
		</div>
	</div>
</div>

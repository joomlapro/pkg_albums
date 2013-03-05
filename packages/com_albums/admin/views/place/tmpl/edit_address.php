<?php
/**
 * @package     Albums
 * @subpackage  com_albums
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Load JavaScript.
JHtml::script('http://maps.google.com/maps/api/js?sensor=true', false, true);
JHtml::script('com_albums/gmaps.js', false, true);

// Load the parameters.
$params = JComponentHelper::getParams('com_albums');

// Get address coordinates.
$latitude  = isset($this->item->address['latitude']) ? $this->item->address['latitude'] : $params->get('latitude', 0);
$longitude = isset($this->item->address['longitude']) ? $this->item->address['longitude'] : $params->get('longitude', 0);
$zoom      = isset($this->item->address['zoom']) ? $this->item->address['zoom'] : $params->get('zoom', 0);

$fieldSets = $this->form->getFieldsets('address');
?>
<script type="text/javascript">
	jQuery.noConflict();

	(function ($) {
		$(function () {
			var map;
			var marker;
			var latitude = $('#jform_address_latitude');
			var longitude = $('#jform_address_longitude');

			$(document).ready(function () {
				map = new GMaps({
					div: '#map',
					lat: '<?php echo $latitude; ?>',
					lng: '<?php echo $longitude; ?>',
					zoom: <?php echo $zoom; ?>,
					width: '100%',
					height: '300px'
				});

				marker = map.addMarker({
					lat: map.getCenter().lat(),
					lng: map.getCenter().lng(),
					title: '<?php echo $this->item->name; ?>',
					draggable: true,
					dragend: function(e) {
						latitude.val(e.latLng.lat().toFixed(6));
						longitude.val(e.latLng.lng().toFixed(6));
					}
				});

				$('#search-map').click(function (e) {
					e.preventDefault();

					GMaps.geocode({
						address: $('#jform_address_street').val().trim() + ' ' + $('#jform_address_city').val().trim() + ' ' + $('#jform_address_state').val().trim(),
						callback: function (results, status) {
							if (status == 'OK') {
								var latlng = results[0].geometry.location;
								marker.setMap(null);
								map.setCenter(latlng.lat(), latlng.lng());
								marker = map.addMarker({
									lat: map.getCenter().lat(),
									lng: map.getCenter().lng(),
									title: '<?php echo $this->item->name; ?>',
									draggable: true,
									dragend: function(e) {
										latitude.val(e.latLng.lat().toFixed(6));
										longitude.val(e.latLng.lng().toFixed(6));
									}
								});

								latitude.val(latlng.lat().toFixed(6));
								longitude.val(latlng.lng().toFixed(6));
							}
						}
					});
				});

				$('.nav-tabs li').live('click', function () {
					map.refresh();
					map.setCenter(
						'<?php echo $latitude; ?>',
						'<?php echo $longitude; ?>'
					);
				});
			});
		});
	})(jQuery);
</script>
<style type="text/css">
	#map {
		width: 100%;
		height: 300px;
		margin-bottom: 10px;
	}
	/* Fixes for Twitter Bootstrap */
	#map label {
		display: inline-block;
	}
	#map img {
		max-width: inherit;
	}
</style>
<?php foreach ($fieldSets as $name => $fieldSet): ?>
	<div class="tab-pane" id="address-<?php echo $name; ?>">
		<div class="row-fluid">
			<div class="span6">
				<?php if (isset($fieldSet->description) && trim($fieldSet->description)):
					echo '<p class="alert alert-info">' . $this->escape(JText::_($fieldSet->description)) . '</p>';
				endif; ?>
				<?php foreach ($this->form->getFieldset($name) as $field): ?>
					<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?></div>
						<div class="controls"><?php echo $field->input; ?></div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="span6">
				<div id="map"></div>
				<a id="search-map" class="btn" href="#"><i class="icon-flag"></i> <?php echo JText::_('COM_ALBUMS_SEARCH_ADDRESS'); ?></a>
			</div>
		</div>
	</div>
<?php endforeach;

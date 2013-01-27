<?php
/**
 * @package     Albums
 * @subpackage  com_albums
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Load Stylesheet.
JHtml::stylesheet('com_albums/backend.css', false, true, false);
JHtml::stylesheet('com_albums/jquery.fileupload-ui.css', false, true, false);

// Add JavaScript Frameworks.
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');

// Load JavaScript.
JHtml::script('com_albums/vendor/jquery.ui.widget.js', false, true);
JHtml::script('com_albums/tmpl.min.js', false, true);
JHtml::script('com_albums/load-image.min.js', false, true);
JHtml::script('com_albums/canvas-to-blob.min.js', false, true);
JHtml::script('com_albums/jquery.iframe-transport.js', false, true);
JHtml::script('com_albums/jquery.fileupload.js', false, true);
JHtml::script('com_albums/jquery.fileupload-fp.js', false, true);
JHtml::script('com_albums/jquery.fileupload-ui.js', false, true);

// Load the parameters.
$params = JComponentHelper::getParams('com_albums');

// Get the input.
$input = JFactory::getApplication()->input;
?>
<!--[if gte IE 8]><script src="media/com_albums/js/cors/jquery.xdr-transport.js"></script><![endif]-->
<div class="tab-pane active" id="images">
	<div class="row-fluid">
		<div class="fileupload-buttonbar">
			<div class="span7">
				<span class="btn btn-success fileinput-button">
					<i class="icon-plus"></i> <?php echo JText::_('COM_ALBUMS_TOOLBAR_ADD'); ?>
					<input type="file" name="files[]" multiple="true" />
				</span>
				<button type="submit" class="btn btn-primary start">
					<i class="icon-upload"></i> <?php echo JText::_('JTOOLBAR_UPLOAD'); ?>
				</button>
				<button type="reset" class="btn btn-warning cancel">
					<i class="icon-minus"></i> <?php echo JText::_('JTOOLBAR_CANCEL'); ?>
				</button>
				<button type="button" class="btn btn-danger delete">
					<i class="icon-trash"></i> <?php echo JText::_('JTOOLBAR_DELETE'); ?>
				</button>
				<input type="checkbox" class="toggle">
			</div>
			<div class="span5">
				<div class="fileupload-progress fade">
					<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
						<div class="bar" style="width:0%;"></div>
					</div>
					<div class="progress-extended">&nbsp;</div>
				</div>
			</div>
		</div>
		<div class="fileupload-loading"></div>
		<table role="presentation" class="table table-striped">
			<thead>
				<tr>
					<th><?php echo JText::_('JGLOBAL_PREVIEW'); ?></th>
					<th><?php echo JText::_('JGLOBAL_TITLE'); ?></th>
					<th colspan="2"></th>
					<th><?php echo JText::_('COM_ALBUMS_HEADING_SIZE'); ?></th>
				</tr>
			</thead>
			<tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody>
		</table>
	</div>
	<div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd" tabindex="-1">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3 class="modal-title"></h3>
		</div>
		<div class="modal-body"><div class="modal-image"></div></div>
		<div class="modal-footer">
			<a class="btn modal-download" target="_blank">
				<i class="icon-download"></i>
				<span>Download</span>
			</a>
			<a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
				<i class="icon-play"></i>
				<span>Slideshow</span>
			</a>
			<a class="btn btn-info modal-prev">
				<i class="icon-arrow-left"></i>
				<span>Previous</span>
			</a>
			<a class="btn btn-primary modal-next">
				<span>Next</span>
				<i class="icon-arrow-right"></i>
			</a>
		</div>
	</div>
	<script id="template-upload" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
			<tr class="template-upload fade">
				<td width="1%" class="nowrap center hidden-phone preview">
					<span class="fade"></span>
				</td>
				<td class="nowrap name">
					<span>{%=file.name%}</span>
				</td>
				{% if (file.error) { %}
					<td class="error" colspan="2">
						<span class="label label-important">Error</span> {%=file.error%}
					</td>
				{% } else if (o.files.valid && !i) { %}
					<td>
						<div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
							<div class="bar" style="width:0%;"></div>
						</div>
					</td>
					<td width="90px" class="nowrap start">
						{% if (!o.options.autoUpload) { %}
							<button class="btn btn-primary"><i class="icon-upload"></i> <?php echo JText::_('JTOOLBAR_UPLOAD'); ?></button>
						{% } %}
					</td>
				{% } else { %}
					<td colspan="2"></td>
				{% } %}
				<td width="80px" class="nowrap hidden-phone size">
					<span>{%=o.formatFileSize(file.size)%}</span>
				</td>
				<td width="100px" class="nowrap cancel">
					{% if (!i) { %}
						<button class="btn btn-warning"><i class="icon-minus"></i> <?php echo JText::_('JTOOLBAR_CANCEL'); ?></button>
					{% } %}
				</td>
			</tr>
		{% } %}
	</script>
	<script id="template-download" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
			<tr class="template-download fade">
				{% if (file.error) { %}
					<td width="1%" class="nowrap center hidden-phone preview"></td>
					<td class="nowrap name">
						<span>{%=file.name%}</span>
					</td>
					<td class="error" colspan="2">
						<span class="label label-important">Error</span> {%=file.error%}
					</td>
					<td width="80px" class="nowrap hidden-phone size">
						<span>{%=o.formatFileSize(file.size)%}</span>
					</td>
				{% } else { %}
					<td width="1%" class="nowrap center hidden-phone preview">
						{% if (file.thumbnail_url) { %}
							<img src="{%=file.thumbnail_url%}">
						{% } %}
					</td>
					<td class="nowrap name">
						{%=file.name%}
					</td>
					<td colspan="2"></td>
					<td width="80px" class="nowrap hidden-phone size">
						<span>{%=o.formatFileSize(file.size)%}</span>
					</td>
				{% } %}
				<td width="100px" class="nowrap delete">
					<button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}><i class="icon-trash"></i> <?php echo JText::_('JTOOLBAR_DELETE'); ?></button>
					<input type="checkbox" name="delete" value="1">
				</td>
			</tr>
		{% } %}
	</script>
	<script type="text/javascript">
		jQuery.noConflict();

		(function ($) {
			$(function () {
				'use strict';

				// Initialize the jQuery File Upload widget:
				$('#album-form').fileupload({
					// Uncomment the following to send cross-domain cookies:
					//xhrFields: {withCredentials: true},
					url: 'index.php?option=com_albums&task=display&tmpl=component&format=json&id=<?php echo $input->get("id", 0); ?>',
					maxNumberOfFiles: <?php echo $params->get('maxnumberoffiles', 10) ?>,
					minFileSize: <?php echo $params->get('minfilesize', 100000) ?>,
					maxFileSize: <?php echo $params->get('maxfilesize', 5000000) ?>,
					previewMaxWidth: <?php echo $params->get('previewmaxwidth', 100) ?>,
					previewMaxHeight: <?php echo $params->get('previewMaxHeight', 100) ?>,
					acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
				});

				// Enable iframe cross-domain access via redirect option:
				$('#album-form').fileupload(
					'option',
					'redirect',
				window.location.href.replace(
					/\/[^\/]*$/,
					'/cors/result.html?%s'));

				// Load existing files:
				$.ajax({
					// Uncomment the following to send cross-domain cookies:
					//xhrFields: {withCredentials: true},
					url: $('#album-form').fileupload('option', 'url'),
					dataType: 'json',
					context: $('#album-form')[0]
				}).done(function (result) {
					$(this).fileupload('option', 'done')
						.call(this, null, {
						result: result
					});
				});
			});
		})(jQuery);
	</script>
</div>

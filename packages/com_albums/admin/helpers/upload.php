<?php
/**
 * @package     Albums
 * @subpackage  com_albums
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/*
 * jQuery File Upload Plugin PHP Class 6.1.2
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Upload helper.
 *
 * @package     Albums
 * @subpackage  com_albums
 * @since       3.0
 */
class UploadHelper
{
	protected $options;

	/**
	 * PHP File Upload error message codes:
	 * http://php.net/manual/en/features.file-upload.errors.php
	 */
	protected $error_messages = array(
		1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
		2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
		3 => 'The uploaded file was only partially uploaded',
		4 => 'No file was uploaded',
		6 => 'Missing a temporary folder',
		7 => 'Failed to write file to disk',
		8 => 'A PHP extension stopped the file upload',
		'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
		'max_file_size' => 'File is too big',
		'min_file_size' => 'File is too small',
		'accept_file_types' => 'Filetype not allowed',
		'max_number_of_files' => 'Maximum number of files exceeded',
		'max_width' => 'Image exceeds maximum width',
		'min_width' => 'Image requires a minimum width',
		'max_height' => 'Image exceeds maximum height',
		'min_height' => 'Image requires a minimum height'
	);

	/**
	 * [__construct description]
	 *
	 * @param   [type]   $options     [description]
	 * @param   boolean  $initialize  [description]
	 *
	 * @since   3.0
	 */
	function __construct($options = null, $initialize = true)
	{
		$this->options = array(
			'script_url' => 'index.php?option=com_albums&task=display&tmpl=component&format=json',
			'upload_dir' => JPATH_SITE . '/images/albums/',
			'upload_url' => JUri::root() . 'images/albums/',
			'user_dirs' => true,
			'mkdir_mode' => 0755,
			'param_name' => 'files',
			// Set the following option to 'POST', if your server does not support
			// DELETE requests. This is a parameter sent to the client:
			'delete_type' => 'DELETE',
			'access_control_allow_origin' => '*',
			'access_control_allow_credentials' => false,
			'access_control_allow_methods' => array(
				'OPTIONS',
				'HEAD',
				'GET',
				'POST',
				'PUT',
				'PATCH',
				'DELETE'
			),
			'access_control_allow_headers' => array(
				'Content-Type',
				'Content-Range',
				'Content-Disposition'
			),
			// Enable to provide file downloads via GET requests to the PHP script:
			'download_via_php' => false,
			// Defines which files can be displayed inline when downloaded:
			'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
			// Defines which files (based on their names) are accepted for upload:
			'accept_file_types' => '/.+$/i',
			// The php.ini settings upload_max_filesize and post_max_size
			// take precedence over the following max_file_size setting:
			'max_file_size' => null,
			'min_file_size' => 1,
			// The maximum number of files for the upload directory:
			'max_number_of_files' => null,
			// Image resolution restrictions:
			'max_width' => null,
			'max_height' => null,
			'min_width' => 1,
			'min_height' => 1,
			// Set the following option to false to enable resumable uploads:
			'discard_aborted_uploads' => true,
			// Set to true to rotate images based on EXIF meta data, if available:
			'orientImage' => false,
			'image_versions' => array(
				'' => array(
					'max_width' => 1920,
					'max_height' => 1200,
					'jpeg_quality' => 95
				),
				'medium' => array(
					'max_width' => 800,
					'max_height' => 600,
					'jpeg_quality' => 80
				),
				'thumbnail' => array(
					'max_width' => 80,
					'max_height' => 80
				)
			)
		);

		if ($options)
		{
			$this->options = array_merge($this->options, $options);
		}

		if ($initialize)
		{
			$this->initialize();
		}
	}

	/**
	 * [initialize description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function initialize()
	{
		switch ($_SERVER['REQUEST_METHOD'])
		{
			case 'OPTIONS':
			case 'HEAD':
				$this->head();
				break;
			case 'GET':
				$this->get();
				break;
			case 'PATCH':
			case 'PUT':
			case 'POST':
				$this->post();
				break;
			case 'DELETE':
				$this->delete();
				break;
			default:
				$this->header('HTTP/1.1 405 Method Not Allowed');
		}
	}

	/**
	 * [getFullUrl description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function getFullUrl()
	{
		$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

		return
			($https ? 'https://' : 'http://') .
			(!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] . '@' : '') .
			(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'] .
			($https && $_SERVER['SERVER_PORT'] === 443 ||
			$_SERVER['SERVER_PORT'] === 80 ? '' : ':' . $_SERVER['SERVER_PORT']))) .
			substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
	}

	/**
	 * Method to get user path.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	protected function getUserPath()
	{
		// Get the input.
		$input = JFactory::getApplication()->input;

		if ($this->options['user_dirs'])
		{
			return $input->getInt('id', 0) . '/';
		}

		return '';
	}

	/**
	 * [getUploadPath description]
	 *
	 * @param   [type]  $file_name  [description]
	 * @param   [type]  $version    [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function getUploadPath($file_name = null, $version = null)
	{
		$file_name = $file_name ? $file_name : '';
		$version_path = empty($version) ? '' : $version . '/';

		return $this->options['upload_dir'] . $this->getUserPath() . $version_path . $file_name;
	}

	/**
	 * [getQuerySeparator description]
	 *
	 * @param   [type]  $url  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function getQuerySeparator($url)
	{
		return strpos($url, '?') === false ? '?' : '&';
	}

	/**
	 * [getDownloadUrl description]
	 *
	 * @param   [type]  $file_name  [description]
	 * @param   [type]  $version    [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function getDownloadUrl($file_name, $version = null)
	{
		if ($this->options['download_via_php'])
		{
			$url = $this->options['script_url']	. $this->getQuerySeparator($this->options['script_url']) . 'file=' . rawurlencode($file_name);

			if ($version)
			{
				$url .= '&version=' . rawurlencode($version);
			}

			return $url . '&download=1';
		}

		$version_path = empty($version) ? '' : rawurlencode($version) . '/';

		return $this->options['upload_url'] . $this->getUserPath() . $version_path . rawurlencode($file_name);
	}

	/**
	 * [setFileDeleteProperties description]
	 *
	 * @param   [type]  $file  [description]
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	protected function setFileDeleteProperties($file)
	{
		// Get the input.
		$input = JFactory::getApplication()->input;

		$file->delete_url = $this->options['script_url'] . $this->getQuerySeparator($this->options['script_url']) . 'file=' . rawurlencode($file->name);
		$file->delete_type = $this->options['delete_type'];

		if ($file->delete_type !== 'DELETE')
		{
			$file->delete_url .= '&_method=DELETE';
		}

		if ($this->options['user_dirs'])
		{
			$file->delete_url .= '&id=' . $input->getInt('id');
		}

		if ($this->options['access_control_allow_credentials'])
		{
			$file->delete_with_credentials = true;
		}
	}

	/**
	 * Method to fix for overflowing signed 32 bit integers,
	 * works for sizes up to 2^32-1 bytes (4 GiB - 1):
	 *
	 * @param   [type]  $size  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function fixIntegerOverflow($size)
	{
		if ($size < 0)
		{
			$size += 2.0 * (PHP_INT_MAX + 1);
		}

		return $size;
	}

	/**
	 * [getFileSize description]
	 *
	 * @param   [type]   $file_path         [description]
	 * @param   boolean  $clear_stat_cache  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function getFileSize($file_path, $clear_stat_cache = false)
	{
		if ($clear_stat_cache)
		{
			clearstatcache(true, $file_path);
		}

		return $this->fixIntegerOverflow(filesize($file_path));

	}

	/**
	 * [isValidFileObject description]
	 *
	 * @param   [type]  $file_name  [description]
	 *
	 * @return  boolean
	 *
	 * @since   3.0
	 */
	protected function isValidFileObject($file_name)
	{
		$file_path = $this->getUploadPath($file_name);

		if (is_file($file_path) && $file_name[0] !== '.')
		{
			return true;
		}

		return false;
	}

	/**
	 * [getFileObject description]
	 *
	 * @param   [type]  $file_name  [description]
	 *
	 * @return  [type]
	 *
	 * @since  3.0
	 */
	protected function getFileObject($file_name)
	{
		if ($this->isValidFileObject($file_name))
		{
			$file = new stdClass;
			$file->name = $file_name;
			$file->size = $this->getFileSize(
				$this->getUploadPath($file_name)
			);
			$file->url = $this->getDownloadUrl($file->name);

			foreach ($this->options['image_versions'] as $version => $options)
			{
				if (!empty($version))
				{
					if (is_file($this->getUploadPath($file_name, $version)))
					{
						$file->{$version . '_url'} = $this->getDownloadUrl(
							$file->name,
							$version
						);
					}
				}
			}
			$this->setFileDeleteProperties($file);
			return $file;
		}
		return null;
	}

	/**
	 * [getFileObjects description]
	 *
	 * @param   string  $iteration_method  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function getFileObjects($iteration_method = 'getFileObject')
	{
		$upload_dir = $this->getUploadPath();

		if (!is_dir($upload_dir))
		{
			return array();
		}

		return array_values(
			array_filter(
				array_map(
					array($this, $iteration_method),
					scandir($upload_dir)
				)
			)
		);
	}

	/**
	 * [countFileObjects description]
	 *
	 * @return  [type]
	 *
	 * @since  3.0
	 */
	protected function countFileObjects()
	{
		return count($this->getFileObjects('isValidFileObject'));
	}

	/**
	 * [createScaledImage description]
	 *
	 * @param   [type]  $file_name  [description]
	 * @param   [type]  $version    [description]
	 * @param   [type]  $options    [description]
	 *
	 * @return  [type]
	 *
	 * @since  3.0
	 */
	protected function createScaledImage($file_name, $version, $options)
	{
		$file_path = $this->getUploadPath($file_name);

		if (!empty($version))
		{
			$version_dir = $this->getUploadPath(null, $version);

			if (!is_dir($version_dir))
			{
				mkdir($version_dir, $this->options['mkdir_mode'], true);
			}

			$new_file_path = $version_dir . '/' . $file_name;
		}
		else
		{
			$new_file_path = $file_path;
		}

		list($img_width, $img_height) = @getimagesize($file_path);

		if (!$img_width || !$img_height)
		{
			return false;
		}

		$scale = min(
			$options['max_width'] / $img_width,
			$options['max_height'] / $img_height
		);

		if ($scale >= 1)
		{
			if ($file_path !== $new_file_path)
			{
				return copy($file_path, $new_file_path);
			}

			return true;
		}

		$new_width = $img_width * $scale;
		$new_height = $img_height * $scale;
		$new_img = @imagecreatetruecolor($new_width, $new_height);

		switch (strtolower(substr(strrchr($file_name, '.'), 1)))
		{
			case 'jpg':
			case 'jpeg':
				$src_img = @imagecreatefromjpeg($file_path);
				$write_image = 'imagejpeg';
				$image_quality = isset($options['jpeg_quality']) ?
					$options['jpeg_quality'] : 75;
				break;
			case 'gif':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				$src_img = @imagecreatefromgif($file_path);
				$write_image = 'imagegif';
				$image_quality = null;
				break;
			case 'png':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				@imagealphablending($new_img, false);
				@imagesavealpha($new_img, true);
				$src_img = @imagecreatefrompng($file_path);
				$write_image = 'imagepng';
				$image_quality = isset($options['png_quality']) ?
					$options['png_quality'] : 9;
				break;
			default:
				$src_img = null;
		}

		$success = $src_img && @imagecopyresampled(
			$new_img,
			$src_img,
			0, 0, 0, 0,
			$new_width,
			$new_height,
			$img_width,
			$img_height
		) && $write_image($new_img, $new_file_path, $image_quality);

		// Free up memory (imagedestroy does not delete files):
		@imagedestroy($src_img);
		@imagedestroy($new_img);

		return $success;
	}

	/**
	 * [getErrorMessage description]
	 *
	 * @param   [type]  $error  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function getErrorMessage($error)
	{
		return array_key_exists($error, $this->error_messages) ? $this->error_messages[$error] : $error;
	}

	/**
	 * [getConfigBytes description]
	 *
	 * @param   [type]  $val  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	function getConfigBytes($val)
	{
		$val = trim($val);
		$last = strtolower($val[strlen($val) - 1]);

		switch ($last)
		{
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $this->fixIntegerOverflow($val);
	}

	/**
	 * [validate description]
	 *
	 * @param   [type]  $uploaded_file  [description]
	 * @param   [type]  $file           [description]
	 * @param   [type]  $error          [description]
	 * @param   [type]  $index          [description]
	 *
	 * @return [type]
	 *
	 * @since  3.0
	 */
	protected function validate($uploaded_file, $file, $error, $index)
	{
		if ($error)
		{
			$file->error = $this->getErrorMessage($error);
			return false;
		}

		$content_length = $this->fixIntegerOverflow(intval($_SERVER['CONTENT_LENGTH']));
		$post_max_size = $this->getConfigBytes(ini_get('post_max_size'));

		if ($post_max_size && ($content_length > $post_max_size))
		{
			$file->error = $this->getErrorMessage('post_max_size');
			return false;
		}

		if (!preg_match($this->options['accept_file_types'], $file->name))
		{
			$file->error = $this->getErrorMessage('accept_file_types');
			return false;
		}

		if ($uploaded_file && is_uploaded_file($uploaded_file))
		{
			$file_size = $this->getFileSize($uploaded_file);
		}
		else
		{
			$file_size = $content_length;
		}

		if ($this->options['max_file_size'] && ($file_size > $this->options['max_file_size'] ||	$file->size > $this->options['max_file_size']))
		{
			$file->error = $this->getErrorMessage('max_file_size');
			return false;
		}

		if ($this->options['min_file_size'] && $file_size < $this->options['min_file_size'])
		{
			$file->error = $this->getErrorMessage('min_file_size');
			return false;
		}

		if (is_int($this->options['max_number_of_files']) && ($this->countFileObjects() >= $this->options['max_number_of_files']))
		{
			$file->error = $this->getErrorMessage('max_number_of_files');
			return false;
		}

		list($img_width, $img_height) = @getimagesize($uploaded_file);

		if (is_int($img_width))
		{
			if ($this->options['max_width'] && $img_width > $this->options['max_width'])
			{
				$file->error = $this->getErrorMessage('max_width');
				return false;
			}

			if ($this->options['max_height'] && $img_height > $this->options['max_height'])
			{
				$file->error = $this->getErrorMessage('max_height');
				return false;
			}

			if ($this->options['min_width'] && $img_width < $this->options['min_width'])
			{
				$file->error = $this->getErrorMessage('min_width');
				return false;
			}

			if ($this->options['min_height'] && $img_height < $this->options['min_height'])
			{
				$file->error = $this->getErrorMessage('min_height');
				return false;
			}
		}
		return true;
	}

	/**
	 * [upCountNameCallback description]
	 *
	 * @param   [type]  $matches  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function upCountNameCallback($matches)
	{
		$index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
		$ext = isset($matches[2]) ? $matches[2] : '';

		return ' (' . $index . ')' . $ext;
	}

	/**
	 * [upCountName description]
	 *
	 * @param   [type]  $name  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function upCountName($name)
	{
		return preg_replace_callback('/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/', array($this, 'upCountNameCallback'), $name, 1);
	}

	/**
	 * [getUniqueFilename description]
	 *
	 * @param   [type]  $name           [description]
	 * @param   [type]  $type           [description]
	 * @param   [type]  $index          [description]
	 * @param   [type]  $content_range  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function getUniqueFilename($name, $type, $index, $content_range)
	{
		while (is_dir($this->getUploadPath($name)))
		{
			$name = $this->upCountName($name);
		}

		// Keep an existing filename if this is part of a chunked upload:
		$uploaded_bytes = $this->fixIntegerOverflow(intval($content_range[1]));

		while (is_file($this->getUploadPath($name)))
		{
			if ($uploaded_bytes === $this->getFileSize($this->getUploadPath($name)))
			{
				break;
			}

			$name = $this->upCountName($name);
		}

		return $name;
	}

	/**
	 * Method to trim file name.
	 *
	 * @param   [type]  $name           [description]
	 * @param   [type]  $type           [description]
	 * @param   [type]  $index          [description]
	 * @param   [type]  $content_range  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function trimFileName($name, $type, $index, $content_range)
	{
		// Remove path information and dots around the filename, to prevent uploading into different directories or replacing hidden system files.
		// Also remove control characters and spaces (\x00..\x20) around the filename:
		$name = trim(basename(stripslashes($name)), ".\x00..\x20");

		// Use a timestamp for empty filenames:
		if (!$name)
		{
			$name = str_replace('.', '-', microtime(true));
		}

		// Add missing file extension for known image types:
		if (strpos($name, '.') === false &&	preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches))
		{
			$name .= '.' . $matches[1];
		}

		return $name;
	}

	/**
	 * Method to get file name.
	 *
	 * @param   [type]  $name           [description]
	 * @param   [type]  $type           [description]
	 * @param   [type]  $index          [description]
	 * @param   [type]  $content_range  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function getFileName($name, $type, $index, $content_range)
	{
		return $this->getUniqueFilename(
			$this->trimFileName($name, $type, $index, $content_range),
			$type,
			$index,
			$content_range
		);
	}

	/**
	 * Method to handle from data.
	 *
	 * @param   [type]  $file   [description]
	 * @param   [type]  $index  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function handleFormData($file, $index)
	{
		// Handle form data, e.g. $_REQUEST['description'][$index]
	}

	/**
	 * Method to orient image.
	 *
	 * @param   string  $file_path  [description]
	 *
	 * @return  [type]
	 *
	 * @since   3.0
	 */
	protected function orientImage($file_path)
	{
		if (!function_exists('exif_read_data'))
		{
			return false;
		}

		$exif = @exif_read_data($file_path);

		if ($exif === false)
		{
			return false;
		}

		$orientation = intval(@$exif['Orientation']);

		if (!in_array($orientation, array(3, 6, 8)))
		{
			return false;
		}

		$image = @imagecreatefromjpeg($file_path);

		switch ($orientation)
		{
			case 3:
				$image = @imagerotate($image, 180, 0);
				break;
			case 6:
				$image = @imagerotate($image, 270, 0);
				break;
			case 8:
				$image = @imagerotate($image, 90, 0);
				break;
			default:
				return false;
		}
		$success = imagejpeg($image, $file_path);

		// Free up memory (imagedestroy does not delete files):
		@imagedestroy($image);

		return $success;
	}

	/**
	 * Method to handle file upload.
	 *
	 * @param   [type]  $uploaded_file  [description]
	 * @param   [type]  $name           [description]
	 * @param   [type]  $size           [description]
	 * @param   [type]  $type           [description]
	 * @param   [type]  $error          [description]
	 * @param   [type]  $index          [description]
	 * @param   [type]  $content_range  [description]
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	protected function handleFileUpload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null)
	{
		$file = new stdClass;
		$file->name = $this->getFileName($name, $type, $index, $content_range);
		$file->size = $this->fixIntegerOverflow(intval($size));
		$file->type = $type;

		if ($this->validate($uploaded_file, $file, $error, $index))
		{
			$this->handleFormData($file, $index);
			$upload_dir = $this->getUploadPath();

			if (!is_dir($upload_dir))
			{
				mkdir($upload_dir, $this->options['mkdir_mode'], true);
			}

			$file_path = $this->getUploadPath($file->name);
			$append_file = $content_range && is_file($file_path) &&	$file->size > $this->getFileSize($file_path);

			if ($uploaded_file && is_uploaded_file($uploaded_file))
			{
				// Multipart/formdata uploads (POST method uploads)
				if ($append_file)
				{
					file_put_contents(
						$file_path,
						fopen($uploaded_file, 'r'),
						FILE_APPEND
					);
				}
				else
				{
					move_uploaded_file($uploaded_file, $file_path);
				}
			}
			else
			{
				// Non-multipart uploads (PUT method support)
				file_put_contents(
					$file_path,
					fopen('php://input', 'r'),
					$append_file ? FILE_APPEND : 0
				);
			}
			$file_size = $this->getFileSize($file_path, $append_file);

			if ($file_size === $file->size)
			{
				if ($this->options['orientImage'])
				{
					$this->orientImage($file_path);
				}

				$file->url = $this->getDownloadUrl($file->name);

				foreach ($this->options['image_versions'] as $version => $options)
				{
					if ($this->createScaledImage($file->name, $version, $options))
					{
						if (!empty($version))
						{
							$file->{$version . '_url'} = $this->getDownloadUrl(
								$file->name,
								$version
							);
						}
						else
						{
							$file_size = $this->getFileSize($file_path, true);
						}
					}
				}
			}
			elseif (!$content_range && $this->options['discard_aborted_uploads'])
			{
				unlink($file_path);
				$file->error = 'abort';
			}

			$file->size = $file_size;
			$this->setFileDeleteProperties($file);
		}

		return $file;
	}

	/**
	 * Method to read file.
	 *
	 * @param   string  $file_path  The file path.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	protected function readfile($file_path)
	{
		return readfile($file_path);
	}

	/**
	 * Method body.
	 *
	 * @param   string  $str  String.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	protected function body($str)
	{
		echo $str;
	}

	/**
	 * Method header.
	 *
	 * @param   string  $str  String.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	protected function header($str)
	{
		header($str);
	}

	/**
	 * Method to generate response.
	 *
	 * @param   string   $content         The content.
	 * @param   boolean  $print_response  If true, print the response.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	protected function generateResponse($content, $print_response = true)
	{
		if ($print_response)
		{
			$json = json_encode($content);
			$redirect = isset($_REQUEST['redirect']) ? stripslashes($_REQUEST['redirect']) : null;

			if ($redirect)
			{
				$this->header('Location: ' . sprintf($redirect, rawurlencode($json)));
				return;
			}

			$this->head();

			if (isset($_SERVER['HTTP_CONTENT_RANGE']))
			{
				$files = isset($content[$this->options['param_name']]) ? $content[$this->options['param_name']] : null;

				if ($files && is_array($files) && is_object($files[0]) && $files[0]->size)
				{
					$this->header('Range: 0-' . ($this->fixIntegerOverflow(intval($files[0]->size)) - 1));
				}
			}

			$this->body($json);
		}
		return $content;
	}

	/**
	 * Method to get version param.
	 *
	 * @return  mixed
	 *
	 * @since   3.0
	 */
	protected function getVersionParam()
	{
		return isset($_GET['version']) ? basename(stripslashes($_GET['version'])) : null;
	}

	/**
	 * Method to get file type.
	 *
	 * @param   string  $file_path  The file path.
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	protected function getFileType($file_path)
	{
		switch (strtolower(pathinfo($file_path, PATHINFO_EXTENSION)))
		{
			case 'jpeg':
			case 'jpg':
				return 'image/jpeg';
			case 'png':
				return 'image/png';
			case 'gif':
				return 'image/gif';
			default:
				return '';
		}
	}

	/**
	 * Method to download file.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	protected function download()
	{
		// Get the input.
		$input = JFactory::getApplication()->input;

		if (!$this->options['download_via_php'])
		{
			$this->header('HTTP/1.1 403 Forbidden');
			return;
		}

		$file_name = $input->getString('file');

		if ($this->isValidFileObject($file_name))
		{
			$file_path = $this->getUploadPath($file_name, $this->getVersionParam());

			if (is_file($file_path))
			{
				if (!preg_match($this->options['inline_file_types'], $file_name))
				{
					$this->header('Content-Description: File Transfer');
					$this->header('Content-Type: application/octet-stream');
					$this->header('Content-Disposition: attachment; filename="' . $file_name . '"');
					$this->header('Content-Transfer-Encoding: binary');
				}
				else
				{
					// Prevent Internet Explorer from MIME-sniffing the content-type:
					$this->header('X-Content-Type-Options: nosniff');
					$this->header('Content-Type: ' . $this->getFileType($file_path));
					$this->header('Content-Disposition: inline; filename="' . $file_name . '"');
				}

				$this->header('Content-Length: ' . $this->getFileSize($file_path));
				$this->header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', filemtime($file_path)));
				$this->readfile($file_path);
			}
		}
	}

	/**
	 * Method to send content type header
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	protected function sendContentTypeHeader()
	{
		$this->header('Vary: Accept');

		if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false))
		{
			$this->header('Content-type: application/json');
		}
		else
		{
			$this->header('Content-type: text/plain');
		}
	}

	/**
	 * Method to send access control headers.
	 *
	 * @return  void
	 *
	 * @since  3.0
	 */
	protected function sendAccessControlHeaders()
	{
		$this->header('Access-Control-Allow-Origin: ' . $this->options['access_control_allow_origin']);
		$this->header('Access-Control-Allow-Credentials: ' . ($this->options['access_control_allow_credentials'] ? 'true' : 'false'));
		$this->header('Access-Control-Allow-Methods: ' . implode(', ', $this->options['access_control_allow_methods']));
		$this->header('Access-Control-Allow-Headers: ' . implode(', ', $this->options['access_control_allow_headers']));
	}

	/**
	 * Method to set head.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function head()
	{
		$this->header('Pragma: no-cache');
		$this->header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->header('Content-Disposition: inline; filename="files.json"');

		// Prevent Internet Explorer from MIME-sniffing the content-type:
		$this->header('X-Content-Type-Options: nosniff');

		if ($this->options['access_control_allow_origin'])
		{
			$this->sendAccessControlHeaders();
		}

		$this->sendContentTypeHeader();
	}

	/**
	 * Method to get file.
	 *
	 * @param   boolean  $print_response  If true, print the response.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function get($print_response = true)
	{
		// Get the input.
		$input = JFactory::getApplication()->input;

		if ($print_response && isset($_GET['download']))
		{
			return $this->download();
		}

		$file_name = $input->getString('file');

		if ($file_name)
		{
			$response = array(
				substr($this->options['param_name'], 0, -1) => $this->getFileObject($file_name)
			);
		}
		else
		{
			$response = array(
				$this->options['param_name'] => $this->getFileObjects()
			);
		}

		return $this->generateResponse($response, $print_response);
	}

	/**
	 * Method to post file.
	 *
	 * @param   boolean  $print_response  If true, print the response.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function post($print_response = true)
	{
		if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE')
		{
			return $this->delete($print_response);
		}

		$upload = isset($_FILES[$this->options['param_name']]) ? $_FILES[$this->options['param_name']] : null;

		// Parse the Content-Disposition header, if available:
		$file_name = isset($_SERVER['HTTP_CONTENT_DISPOSITION']) ? rawurldecode(preg_replace('/(^[^"]+")|("$)/', '', $_SERVER['HTTP_CONTENT_DISPOSITION'])) : null;

		// Parse the Content-Range header, which has the following form:
		// Content-Range: bytes 0-524287/2000000
		$content_range = isset($_SERVER['HTTP_CONTENT_RANGE']) ? preg_split('/[^0-9]+/', $_SERVER['HTTP_CONTENT_RANGE']) : null;
		$size  = $content_range ? $content_range[3] : null;
		$files = array();

		if ($upload && is_array($upload['tmp_name']))
		{
			// Param_name is an array identifier like "files[]",
			// $_FILES is a multi-dimensional array:
			foreach ($upload['tmp_name'] as $index => $value)
			{
				$files[] = $this->handleFileUpload(
					$upload['tmp_name'][$index],
					$file_name ? $file_name : $upload['name'][$index],
					$size ? $size : $upload['size'][$index],
					$upload['type'][$index],
					$upload['error'][$index],
					$index,
					$content_range
				);
			}
		}
		else
		{
			// Param_name is a single object identifier like "file",
			// $_FILES is a one-dimensional array:
			$files[] = $this->handleFileUpload(
				isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
				$file_name ? $file_name : (isset($upload['name']) ? $upload['name'] : null),
				$size ? $size : (isset($upload['size']) ? $upload['size'] : $_SERVER['CONTENT_LENGTH']),
				isset($upload['type']) ? $upload['type'] : $_SERVER['CONTENT_TYPE'],
				isset($upload['error']) ? $upload['error'] : null,
				null,
				$content_range
			);
		}

		return $this->generateResponse(array($this->options['param_name'] => $files), $print_response);
	}

	/**
	 * Method to delete file.
	 *
	 * @param   boolean  $print_response  If true, print the response.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function delete($print_response = true)
	{
		// Get the input.
		$input = JFactory::getApplication()->input;

		// Initialiase variables.
		$file_name = $input->getString('file');
		$file_path = $this->getUploadPath($file_name);
		$success   = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);

		if ($success)
		{
			foreach ($this->options['image_versions'] as $version => $options)
			{
				if (!empty($version))
				{
					$file = $this->getUploadPath($file_name, $version);

					if (is_file($file))
					{
						unlink($file);
					}
				}
			}
		}

		return $this->generateResponse(array('success' => $success), $print_response);
	}
}

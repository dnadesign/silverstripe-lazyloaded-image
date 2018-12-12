<?php

namespace DNADesign\Images\Models;

use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;

class SizedImage extends DataObject
{
	private static $table_name = 'SizedImage';

	private static $db = [
		'Name' => 'Varchar(255)',
		'Size' => 'Varchar(100)',
		'Rule' => 'Varchar(100)'
	];

	private static $has_one = [
		'Image' => Image::class
	];

	private static $owns = [
        'Image'
    ];

	private static $summary_fields = [
		'ID' => 'ID',
		'Size' => 'Size',
		'Image.CMSThumbnail' => 'Thumbnail'
	];

	public function getCMSFields()
	{
		$fields = parent::getCMSFields();

		$name = $fields->dataFieldByName('Name');
		$name->setDescription('For reference only');

		return $fields;
	}

	/**
	* This object should exists only if it has an image
	*/
	public function exists()
	{
		return $this->Image() && $this->Image()->exists();
	}

	/**
	* Proxy method to be able to render the lazyloaded template
	* @see DNADesign\Images\Extensions\LazyloadedImage
	*/
	public function getLazyloaded()
	{
		if ($this->Image()->exists() && $this->Image()->hasMethod('getLazyloaded')) {
			return $this->Image()->getLazyloaded();
		}

		return $this->Image();
	}

	/**
	* Render normal image by default
	*/
	public function forTemplate()
	{
		return ($this->Image()->exists()) ? $this->Image()->fortemplate() : '';
	}
}

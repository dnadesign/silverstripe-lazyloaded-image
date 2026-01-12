<?php

namespace DNADesign\Images\Models;

use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ArrayList;
use DNADesign\Images\Models\SizedImage;

class MultipleSizeImage extends DataObject
{
	private static $table_name = 'MultipleSizeImage';

	// Default Carbon breakpoints
	private static $sizes = [
		'xsmall' => 'min-width: 0px',
		'small' => 'min-width: 576px',
		'medium' => 'min-width: 768px',
		'large' => 'min-width: 992px',
		'xlarge' => 'min-width: 1200px',
		'xxlarge' => 'min-width: 1600px'
	];

	// TODO: implement desktop first
	private static $mobile_first = true;

	private static $db = [
		'Name' => 'Varchar(255)',
		'Lazyload' => 'Boolean'
	];

	private static $many_many = [
		'Images' => SizedImage::class,
	];

	private static $defaults = [
		'Lazyload' => true
	];

	public function getCMSFields()
	{
		$fields = parent::getCMSFields();

		// Name
		$name = $fields->dataFieldByName('Name');
		$name->setDescription('For reference only.');

		// Images
		if ($this->isInDB()) {
			$images = $fields->dataFieldByName('Images');
			$fields->addFieldToTab('Root.Main', $images);
		} else {
			$fields->removeByName('Images');
			$warning = LiteralField::create('warning', '<span class="message warning">Please save the object before adding an image.</span>');

			$fields->addFieldToTab('Root.Main', $warning);
		}

		return $fields;
	}

	public function onBeforeWrite()
	{
		parent::onBeforeWrite();

		if ($this->Images()->Count() == 0) {
			foreach($this->config()->sizes as $size => $rule) {
				$image = new SizedImage();
				$image->Size = $size;
				$image->Rule = $rule;
				$image->write();

				$this->Images()->add($image);
			}
		}
	}

	public function getSources($size = null)
	{
		$images = [];

		if (!$size) {
			$size = $this->getSmallestSize();
		}

		$availableSizes = array_reverse($this->getSizesForRequestedImage($size));

		foreach($availableSizes as $size) {
			$image = $this->Images()->filter('Size', $size)->exclude('Rule', '')->First();
			if ($image && $image->exists()) {
				$images[] = $image;
			}
		}

		return new ArrayList($images);
	}

	/**
	* Returns the images for the requested size
	* or any existing images at a lower (sizedown) or higer size (sizeup)
	* Default to small image falling back on size up
	*
	* @param string
	* @param string
	* @return Image
	*/
	public function getBaseImage($size = null)
	{
		if (!$size) {
			$size = $this->getSmallestSize();
		}

		if ($size) {
			$availableSizes = $this->getSizesForRequestedImage($size);
			foreach($availableSizes as $size) {
				$image = $this->Images()->filter('Size', $size)->First();
				if ($image && $image->exists()) {
					return $image;
				}
			}
		}

		return null;
	}

	/**
	* Returns the largest image or a the largest of its size down
	*
	* @return Image
	*/
	public function getLargestImage()
	{
		$availableSizes = array_reverse($this->getSizesForRequestedImage($this->getSmallestSize()));
		foreach($availableSizes as $size) {
			$image = $this->Images()->filter('Size', $size)->First();
			if ($image && $image->exists()) {
				return $image;
			}
		}

		return null;
	}

	/**
	* Returns an array of sizes to be looped over
	* If sizeup is passed as fallback, then the array will include the requested size + the ones next in the array
	* if sizedown, then array will include requested size + the previous ones in the array and reverse the array
	*
	* @param string
	* @param string
	* @return Array
	*/
	private function getSizesForRequestedImage($size)
	{
		$sizes = array_map('strtolower', array_keys($this->config()->sizes));
		$requestedSize = strtolower($size);

		if ($size && in_array($size, $sizes)) {
			$index = array_search($size, $sizes);
			$condition = ($this->config()->mobile_first == true) ? '>= '.$index : '<= '.$index;

			$sizesfallbacks = array_filter($sizes, function($item, $key) use ($condition) {
				$check = sprintf('return %s %s;', $key, $condition);
				return eval($check);
			}, ARRAY_FILTER_USE_BOTH);

			return ($this->config()->mobile_first == true) ? $sizesfallbacks : array_reverse($sizesfallbacks);
		}

		return [];
	}

	/*
	* Helpers
	*/
	public function getLargestSize()
	{
		$sizes = array_keys($this->config()->sizes);
		return end($sizes);
	}

	public function getSmallestSize()
	{
		$sizes = array_keys($this->config()->sizes);
		return (isset($sizes[0])) ? $sizes[0] : '';
	}

	public function forTemplate(): string
	{
		return $this->renderWith(self::class);
	}
}

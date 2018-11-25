<?php

namespace DNADesign\LazyloadedImage;

use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;

class LazyloadedImageExtension extends Extension
{
    public function getLazyloaded()
    {
        Requirements::javascript('dnadesign/silverstripe-lazyloaded-image:client/javascript/lazysizes.min.js');
        Requirements::javascript('dnadesign/silverstripe-lazyloaded-image:client/javascript/ls.blur-up.min.js');

        return $this->owner->customise([
            'LQIP' => $this->owner->Quality(20)
        ])->renderWith('Includes/LazyloadedImage');
    }
}

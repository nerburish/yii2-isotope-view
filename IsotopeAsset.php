<?php
/**
 * @copyright Copyright (c); nerburish, 2016
 * @package yii2-isotope-view
 */

namespace nerburish\isotopeview;

use yii\web\AssetBundle;

/**
 * Asset bundle for Isotope.js (http://isotope.metafizzy.co/)
 *
 * @package nerburish\isotopeview
 */
class IsotopeAsset extends AssetBundle
{
    public $sourcePath = '@bower/isotope';
    
    public $js = [
        'dist/isotope.pkgd.min.js',
    ];
	
    public $depends = [
        'yii\web\JqueryAsset'
    ];	
}
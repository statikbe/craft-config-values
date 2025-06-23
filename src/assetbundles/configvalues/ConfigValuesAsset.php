<?php

namespace statikbe\configvaluesfield\assetbundles\configvalues;

use craft\web\AssetBundle;

class ConfigValuesAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = "@statikbe/configvaluesfield/assetbundles/configvalues/dist";

        $this->css = [
            'css/configvalues.css',
        ];
        parent::init();
    }
}

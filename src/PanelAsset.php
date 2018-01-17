<?php

namespace rsmike\panel;

use yii\web\AssetBundle;

/**
 * MatchHeight asset bundle.
 */
class PanelAsset extends AssetBundle
{
    public $sourcePath = '@vendor/rsmike/yii2-panel/assets';
    public $css = [
        'panel.scss',
        'panel.styles.scss',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}

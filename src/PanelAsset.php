<?php

namespace common\components\panel;

use yii\web\AssetBundle;

/**
 * MatchHeight asset bundle.
 */
class PanelAsset extends AssetBundle
{
    public $sourcePath = '@common/components/panel/assets';
    public $css = [
        'panel.scss',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}

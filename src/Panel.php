<?php

namespace rsmike\panel;

use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

class Panel extends Widget
{

    /**
     * @var bool Do not produce output if content is empty
     */
    public $hideEmpty = false;

    /**
     * @var bool Force empty panel (useful on condition)
     */
    public $makeEmpty = false;

    /**
     * @var bool If the panel can be collapsed
     */
    public $collapsible = false;

    /**
     * @var bool If the collapsing panel should have an arrow
     */
    public $withArrow = true;

    /**
     * @var bool Start collapsed. Automatically sets $collapsible to true if set
     */
    public $collapsed = false;

    /**
     * @var string Caption string
     */
    public $caption = '';

    /**
     * @var string Footer string
     * TODO: footer sticky to container bottom (for auto heights)
     */
    public $footer = '';

    /**
     * @var string Extra code to the panel header (controls, buttons, info etc)
     */
    public $extras = '';

    /**
     * @var boolean Align extra code to the right
     */
    public $extrasRight = true;

    /**
     * @var string Panel style/class. Can be
     * primary, success, info, warning, danger, default (see Bootstrap panel documentation)
     * paper - additional classes (see panel.styles.css)
     */
    public $panelStyle = 'default';

    /**
     * @var string Additional class for the whole thing
     */
    public $outerClass = '';

    /**
     * @var string Add 'match-height' class for aligning. Requires 'MatchHeightAsset'
     */
    public $matchHeight = true;

    /**
     * @var string Outer template (outermost code)
     */
    public $outerTemplate = '{widget}';

    /**
     * @var string Inner template (inside panel-body)
     */
    public $innerTemplate = '{content}';

    /**
     * @var string Content (useful for ::widget call instead of start/end)
     */
    public $content = '';

    public function init() {
        parent::init();
        PanelAsset::register($this->view);
        ob_start();
    }

    /**
     * Begins a widget.
     * This method creates an instance of the calling class. It will apply the configuration
     * to the created instance. A matching [[end()]] call should be called later.
     * 
     * Shortcut style: Panel::begin('My panel'); echo $content; Panel::end();
     * Shortcut style: Panel::begin('My panel');
     * 
     * @param array|string $config name-value pairs that will be used to initialize the object properties OR title
     * @return Widget the newly created widget instance
     */
    public static function begin($config = []) {
        if (is_string($config)) {
            $config = ['caption' => $config];
        }
        return parent::begin($config);
    }

    public function run() {
        $content = $this->makeEmpty?'':$this->content . trim(ob_get_clean());

        if ($this->hideEmpty && empty($content)) {
            return '';
        }

        $id = $this->getId();

        if ($this->collapsed) {
            $this->matchHeight = false;
            $this->collapsible = true;
        }

        $headerOptions = ['class' => 'panel-heading'];
        $innerOptions = ['class' => 'panel-body'];
        $collapserOptions = ['id' => $id . '-collapse', 'class' => 'panel-collapser'];

        if ($this->collapsible) {
            Html::addCssClass($headerOptions, ['collapsible']);
            Html::addCssClass($collapserOptions, ['collapse']);

            if ($this->collapsed) {
                Html::addCssClass($headerOptions, ['collapsed']);
            } else {
                Html::addCssClass($collapserOptions, ['in']);
            }

            if ($this->withArrow) {
                Html::addCssClass($headerOptions, ['with-arrow']);
            }

            $headerOptions = ArrayHelper::merge($headerOptions,
                ['data' => ['toggle' => 'collapse', 'target' => '#' . $id . '-collapse']]);
        }

        $headerTitle = Html::tag('h3', $this->caption, ['class' => 'panel-title']);

        if ($this->extras) {
            $extrasOptions = ['class' => 'panel-extras'];
            if ($this->extrasRight) {
                Html::addCssClass($extrasOptions, 'pull-right');
            }
            $headerTitle = $headerTitle . Html::tag('div', $this->extras, $extrasOptions);
        }

        $header = Html::tag('div', $headerTitle, $headerOptions);

        $inner = Html::tag('div', str_ireplace('{content}', $content, $this->innerTemplate), $innerOptions);
        
        $collapser = Html::tag('div', $inner, $collapserOptions);
        $outerOptions = ['class' => 'panel'];

        if (empty($content)) {
            Html::addCssClass($outerOptions, 'panel-empty');
        }
        if ($this->panelStyle) {
            Html::addCssClass($outerOptions, 'panel-' . $this->panelStyle);
        }
        if ($this->outerClass) {
            Html::addCssClass($outerOptions, $this->outerClass);
        }
        if ($this->matchHeight && !empty($content)) {
            Html::addCssClass($outerOptions, 'match-height');
        }

        $outer = Html::tag('div', $header . $collapser, $outerOptions);

        $result = str_ireplace('{widget}', $outer, $this->outerTemplate);

        return $result;
    }

}
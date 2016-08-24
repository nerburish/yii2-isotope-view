<?php
/**
 * @copyright Copyright (c); nerburish, 2016
 * @package yii2-isotope-view
 */

namespace nerburish\isotopeview;

use yii\widgets\ListView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * ListView widget improved to use Isotope (http://isotope.metafizzy.co/)
 *
 * @package nerburish\isotopeview
 */
class IsotopeView extends ListView
{	
	const DEFAULT_GRID_CLASS = 'grid';
	
	const DEFAULT_ITEM_CLASS = 'grid-item';
	
	const DEFAULT_CLASS_ATTRIBUTE = 'class';
	
	/**
    * @var string the jquery selector where will be initialized the isotope plugin
    */
    public $gridSelector;

	/**
    * @var string the attribute of the model passed that will be used to filter the grid
	* The attribute value can be an array or string of separated by spaces. 
	* This value will be used as class for the grid-item tag.
    */
    public $filterAttribute = self::DEFAULT_CLASS_ATTRIBUTE;	
	
	/**
    * @var array the HTML attributes (name-value pairs) for the field container tag.
    * The values will be HTML-encoded using [[Html::encode()]].
    * If a value is null, the corresponding attribute will not be rendered.
    */
    public $options = [];
	
	/**
    * @var array the HTML attributes (name-value pairs) for the grid container tag.
    * The values will be HTML-encoded using [[Html::encode()]].
    */
    public $gridOptions = [];	

    /**
    * @var array parameters accepted by masonary plugin, see http://isotope.metafizzy.co/options.html
    */
    public $clientOptions = [];

    /**
    * @var array use it for inject a custom css style. Array accepts the same parameters as \yii\base\View::registerCssFile()
	* @see http://www.yiiframework.com/doc-2.0/yii-web-view.html#registerCssFile()-detail
    */	
	public $cssFile = [];

    /**
     * @var string the layout that determines how different sections of the list view should be organized.
     * The following tokens will be replaced with the corresponding section contents:
     *
     * - `{summary}`: the summary section. See [[renderSummary()]].
     * - `{items}`: the list items. See [[renderItems()]].
     * - `{sorter}`: the sorter. See [[renderSorter()]].
     * - `{pager}`: the pager. See [[renderPager()]].
     */	
	public $layout;    
	
	/**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
		if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }	

		if (!isset($this->gridOptions['class'])) {
			$this->gridOptions['class'] = self::DEFAULT_GRID_CLASS;		
		}

		if (isset($this->itemOptions['class'])) {
			$this->clientOptions['itemSelector'] = '.' . $this->itemOptions['class'];
		} else {
			$this->itemOptions['class'] = self::DEFAULT_ITEM_CLASS;
			$this->clientOptions['itemSelector'] = '.' . self::DEFAULT_ITEM_CLASS;
		}
		
		if (empty($this->gridSelector)) {
			$this->gridSelector = '#'. $this->id . ' .' . self::DEFAULT_GRID_CLASS;
		}		
		
		if (empty($this->layout)) {
			$tag = ArrayHelper::remove($this->gridOptions, 'tag', 'div');
			
			$gridContainer = Html::tag($tag, '{items}', $this->gridOptions);
			
			$this->layout = "{summary}$gridContainer\n{pager}";
		}
			
        parent::init();
    }
	
    /**
     * Runs the widget.
     */
    public function run()
    {
		if ($this->showOnEmpty || $this->dataProvider->getCount() > 0) {
            $content = preg_replace_callback("/{\\w+}/", function ($matches) {
                $content = $this->renderSection($matches[0]);
                return $content === false ? $matches[0] : $content;
            }, $this->layout);
        } else {
            $content = $this->renderEmpty();
        }
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        echo Html::tag($tag, $content, $options);
		$this->registerWidget();
    }	
	
	/**
    * Register assets and initialize the widget
    */
    protected function registerWidget()
    {
		$id = $this->options['id'];
        $gridSelector = $this->gridSelector;
		
		$view = $this->getView();
		
        IsotopeAsset::register($view);
        $js = [];
        
        $options = Json::encode($this->clientOptions);
        $js[] = "var isotopeContainer$id = $('$gridSelector');";
        $js[] = "var isotope$id = isotopeContainer$id.isotope($options);";

        $view->registerJs(implode("\n", $js),$view::POS_READY);
		
		if (!empty($this->cssFile)) {
			call_user_func_array([$view, "registerCssFile"], $this->cssFile);
		} 		
    }

    /**
     * Renders a single data model.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key value associated with the data model
     * @param integer $index the zero-based index of the data model in the model array returned by [[dataProvider]].
     * @return string the rendering result
     */
    public function renderItem($model, $key, $index)
    {
        if ($this->itemView === null) {
            $content = $key;
        } elseif (is_string($this->itemView)) {
            $content = $this->getView()->render($this->itemView, array_merge([
                'model' => $model,
                'key' => $key,
                'index' => $index,
                'widget' => $this,
            ], $this->viewParams));
        } else {
            $content = call_user_func($this->itemView, $model, $key, $index, $this);
        }
        $options = $this->itemOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        $options['data-key'] = is_array($key) ? json_encode($key, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : (string) $key;

		if (!empty($model->{$this->filterAttribute})) {
			if (is_array($model->{$this->filterAttribute})) {
				$filterClasses = implode(' ', $model->{$this->filterAttribute});
			} else {
				$filterClasses = $model->{$this->filterAttribute};
			}
			
			if (isset($options['class'])){
				$options['class'] = $options['class'] .' '. $filterClasses;
			} else {
				$options['class'] = $filterClasses;
			}			
		}
		
        return Html::tag($tag, $content, $options);
    }	
}
Yii2 Isotope ListView Widget
======================

Yii2 widget to extend the Yii2 ListView for use it as Isotope grid (http://isotope.metafizzy.co/)

Widget demo screenshot:
![demo](https://cloud.githubusercontent.com/assets/5610788/17946123/49153582-6a47-11e6-914d-f6424523836a.gif)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist nerburish/yii2-isotope-view "dev-master"
```

or add

```
"nerburish/yii2-isotope-view": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

The usage is similar to default ListView Widget (http://www.yiiframework.com/doc-2.0/yii-widgets-listview.html)

You just need a dataProvider and prepare the item template for your model.

In clientOptions you can pass the Isotope options to modify the plugin behavior (see http://isotope.metafizzy.co/options.html)

The filterAttribute parameter is the attribute name of the model passed that will be used as class for the grid-item tag.
The attribute value can be an array or string of separated by spaces.

You can also attach a cssFile for styling the grid.

Exemple
-----

We have this model:

```
class MyElement extends \yii\base\Model
{
	public $id;
	
	public $name;
	
	public $symbol;
	
	public $number;
	
	public $weight;
	
	public $categories;
}

```

And this item template named _item.php:

```
<h5 class="name"><?= $model->name ?></h5>
<p class="symbol"><?= $model->symbol ?></p>
<p class="number"><?= $model->number ?></p>
<p class="weight"><?= $model->weight ?></p>
```

Finally, in our view, we run the widget:

```
<?php echo \nerburish\isotopeview\IsotopeView::widget([
	'dataProvider' => $dataProvider,
	'filterAttribute' => 'categories',
	'itemView' => '_item',
	'clientOptions' => [
		'layoutMode' => 'masonry',
	],
	'cssFile' => [
		"@web/css/grid-demo.css"		
	]
]) ?>
```

All models, views and CSS styles used for the exemple are inside the folder demo-data.
The template named index.php adds a filter buttons to test the filtering methods.
It's a similar exemple that it's explained inside the Isotope documentation (http://isotope.metafizzy.co/filtering.html)

```
<div class="button-group filters-button-group">
  <button class="button" data-filter="*">show all</button>
  <button class="button" data-filter=".metal">metal</button>
  <button class="button is-checked" data-filter=".transition">transition</button>
  <button class="button" data-filter=".alkali, .alkaline-earth">alkali and alkaline-earth</button>
  <button class="button" data-filter=":not(.transition)">not transition</button>
  <button class="button" data-filter=".metal:not(.transition)">metal but not transition</button>
  <button class="button" data-filter="numberGreaterThan50">number &gt; 50</button>
  <button class="button" data-filter="ium">name ends with –ium</button>
</div>

<?php $this->registerJs('
	var filterFns = {
	  // show if number is greater than 50
	  numberGreaterThan50: function() {
		var number = $(this).find(".number").text();
		return parseInt( number, 10 ) > 50;
	  },
	  // show if name ends with -ium
	  ium: function() {
		var name = $(this).find(".name").text();
		return name.match( /ium$/ );
	  }
	};	
	
	$(".filters-button-group").on( "click", "button", function() {
	  var filterValue = $( this ).attr("data-filter");
	  // use filterFn if matches value
	  filterValue = filterFns[ filterValue ] || filterValue;
	  $("#w0 .grid").isotope({ filter: filterValue });
	}); 
', $this::POS_END) ?>

<?php echo \nerburish\isotopeview\IsotopeView::widget([
	'dataProvider' => $dataProvider,
	'filterAttribute' => 'categories',
	'itemView' => '_item',
	'cssFile' => [
		"@web/css/grid-demo.css"		
	]
]) ?>
```

You may also be interested in MatchHeight.js ListView widget:
https://github.com/nerburish/yii2-match-height-view


 

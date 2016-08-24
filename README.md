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

All models and views used for the exemple are inside the folder demo-data.
The template named index.php adds a filter buttons to test the filtering methods.
Its a similar exemple that its show in the plugin documentation

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

Below, the css used for the demo:

```
/* ---- grid ---- */
.grid {
  border: 1px solid #333;
}

/* clearfix */
.grid:after {
  content: '';
  display: block;
  clear: both;
}

/* grid-item */
.grid-item {
  position: relative;
  float: left;
  width: 100px;
  height: 100px;
  margin: 5px;
  padding: 10px;
  background: #888;
  color: #262524;
}

.grid-item > * {
  margin: 0;
  padding: 0;
}

.grid-item .name {
  position: absolute;

  left: 10px;
  top: 60px;
  text-transform: none;
  letter-spacing: 0;
  font-size: 0.8em;
  font-weight: normal;
}

.grid-item .symbol {
  position: absolute;
  left: 10px;
  top: 0px;
  font-size: 2.8em;
  font-weight: bold;
  color: white;
}

.grid-item .number {
  position: absolute;
  right: 8px;
  top: 5px;
}

.grid-item .weight {
  position: absolute;
  left: 10px;
  top: 76px;
  font-size: 0.8em;
}

.grid-item.alkali          { background: #F00; background: hsl(   0, 100%, 50%); }
.grid-item.alkaline-earth  { background: #F80; background: hsl(  36, 100%, 50%); }
.grid-item.lanthanoid      { background: #FF0; background: hsl(  72, 100%, 50%); }
.grid-item.actinoid        { background: #0F0; background: hsl( 108, 100%, 50%); }
.grid-item.transition      { background: #0F8; background: hsl( 144, 100%, 50%); }
.grid-item.post-transition { background: #0FF; background: hsl( 180, 100%, 50%); }
.grid-item.metalloid       { background: #08F; background: hsl( 216, 100%, 50%); }
.grid-item.diatomic        { background: #00F; background: hsl( 252, 100%, 50%); }
.grid-item.halogen         { background: #F0F; background: hsl( 288, 100%, 50%); }
.grid-item.noble-gas       { background: #F08; background: hsl( 324, 100%, 50%); }

/* ---- button ---- */
.button {
  display: inline-block;
  padding: 10px 18px;
  margin-bottom: 10px;
  background: #EEE;
  border: none;
  border-radius: 7px;
  background-image: linear-gradient( to bottom, hsla(0, 0%, 0%, 0), hsla(0, 0%, 0%, 0.2) );
  color: #222;
  font-family: sans-serif;
  font-size: 16px;
  text-shadow: 0 1px white;
  cursor: pointer;
}

.button:hover {
  background-color: #8CF;
  text-shadow: 0 1px hsla(0, 0%, 100%, 0.5);
  color: #222;
}

.button:active,
.button.is-checked {
  background-color: #28F;
}

.button.is-checked {
  color: white;
  text-shadow: 0 -1px hsla(0, 0%, 0%, 0.8);
}

.button:active {
  box-shadow: inset 0 1px 10px hsla(0, 0%, 0%, 0.8);
}

/* ---- button-group ---- */
.button-group:after {
  content: '';
  display: block;
  clear: both;
}

.button-group .button {
  float: left;
  border-radius: 0;
  margin-left: 0;
  margin-right: 1px;
}

.button-group .button:first-child { border-radius: 0.5em 0 0 0.5em; }
.button-group .button:last-child { border-radius: 0 0.5em 0.5em 0; }
```

You may also be interested in MatchHeight.js ListView widget:
https://github.com/nerburish/yii2-match-height-view


 

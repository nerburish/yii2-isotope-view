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
	'clientOptions' => [
		'layoutMode' => 'masonry',
	],
	'cssFile' => [
		"@web/css/grid-demo.css"		
	]
]) ?>

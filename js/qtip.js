jQuery(document).ready(function(){			
		jQuery('a.qtip').cluetip({				
				dropShadow: true,   
				local: true,
				hideLocal: true,
				arrows: true,
				mouseOutClose: true,
				positionBy: 'bottomTop',
				topOffset: 30,
				closePosition: 'title',
				showTitle: true,
				sticky: true
		});
});
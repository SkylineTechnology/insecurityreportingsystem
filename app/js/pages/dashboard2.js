//[Dashboard Javascript]

//Project:	Famosa Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
	
	/*ApexCharts Start*/
	window.Apex = {
		  stroke: {
			width: 3
		  },
		  markers: {
			size: 0
		  },
		  tooltip: {
			fixed: {
			  enabled: true,
			}
		  }
		};

	var randomizeArray = function (arg) {
	  var array = arg.slice();
	  var currentIndex = array.length,
		temporaryValue, randomIndex;

	  while (0 !== currentIndex) {

		randomIndex = Math.floor(Math.random() * currentIndex);
		currentIndex -= 1;

		temporaryValue = array[currentIndex];
		array[currentIndex] = array[randomIndex];
		array[randomIndex] = temporaryValue;
	  }

	  return array;
	}

	// data for the sparklines that appear below header area
	var sparklineData = [47, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46];
	var spark1 = {
		chart: {
		type: 'area',
		height: 40,
		sparkline: {
		  enabled: true
		},
	},
		colors: ['#5867dd'],
		stroke: {
			curve: 'straight',
			colors: ['#5867dd'],
		},
		fill: {
			opacity: 0.3,
			colors: ['#5867dd'],
		},
		series: [{
			data: randomizeArray(sparklineData)
		}],
		xaxis: {
		crosshairs: {
		  width: 1
		},
		},
		yaxis: {
			min: 0
		}
	}
	var spark2 = {
		chart: {
			type: 'area',
			height: 40,
			sparkline: {
			  enabled: true
			},
		},
		colors: ['#fd397a'],
		stroke: {
			curve: 'straight',
			colors: ['#fd397a'],
		},
		fill: {
			opacity: 0.3,
			colors: ['#fd397a'],
		},
		series: [{
			data: randomizeArray(sparklineData)
		}],
		xaxis: {
		crosshairs: {
		  width: 1
		},
		},
		yaxis: {
			min: 0
		}
	}
	var spark1 = new ApexCharts(document.querySelector("#spark1"), spark1);
	spark1.render();
	var spark2 = new ApexCharts(document.querySelector("#spark2"), spark2);
	spark2.render();
	
	
	
	
	// Morris bar chart

    Morris.Bar( {
        element: 'morris-bar-chart', data: [ 
            { y: 'Blom World', a: 10000, b: 8000, c:7800}, 
            { y: 'Book Show', a: 8500, b: 7000, c:6500}, 
            { y: 'Farming', a: 9000, b: 7500, c:7000}, 
            { y: 'Transfer Amount', a: 9500, b: 8500, c:7500},
            { y: 'Trading Soft', a: 7500, b: 5500, c:5000},
            { y: 'Banking', a: 7500, b: 5500, c:5000}
        ], 
        barGap:8,
        barSizeRatio:0.30,
        barShape: 'soft',
        barRadius: [5, 5, 5, 5],
        xkey: 'y', 
        ykeys: [ 'a', 'b', 'c'], 
        labels: ['Total', 'Used', 'Target'], 
        barColors: ['#2444e8', '#843cf7', '#ec4b71'], 
        hideHover: 'auto',
        preUnits: "$", 
        gridLineColor: '#d2d6e6', 
        gridTextColor: '#8997bd',
        resize: true,
    }
    );
	
	
	
	var optionsCircle4 = {
	  chart: {
		type: 'radialBar',
		height: 440,
	  },
	  plotOptions: {
		radialBar: {
		  size: undefined,
		  inverseOrder: true,
		  hollow: {
			margin: 5,
			size: '48%',
			background: 'transparent',

		  },
		  track: {
			show: false,
		  },
		  startAngle: -180,
		  endAngle: 180

		},
	  },
	  stroke: {
		lineCap: 'round'
	  },
	  colors: ['#2444e8', '#843cf7', '#ec4b71'],
	  series: [69, 59, 74],
	  labels: ['Jan', 'Feb', 'March'],
	  legend: {
		show: true,
		floating: true,
		position: 'right',
		offsetX: 20,
		offsetY: 240
	  },
	}

	var chartCircle4 = new ApexCharts(document.querySelector('#e_chart_3'), optionsCircle4);
	chartCircle4.render();
	
	
	
	var bar = new ProgressBar.Circle(progressbar1, {
		  color: '#7f4cc1',
		  // This has to be the same size as the maximum width to
		  // prevent clipping
		  strokeWidth: 20,
		  trailWidth: 1,
		  easing: 'easeInOut',
		  duration: 1400,
		  text: {
			autoStyleContainer: false
		  },
		  from: { color: '#7f4cc1', width: 1 },
		  to: { color: '#9c52d5', width: 4 },
		  // Set default step function for all animate calls
		  step: function(state, circle) {
			circle.path.setAttribute('stroke', state.color);
			circle.path.setAttribute('stroke-width', state.width);

			var value = Math.round(circle.value() * 150);
			if (value === 0) {
			  circle.setText('');
			} else {
			  circle.setText(value);
			}

		  }
		});
		bar.text.style.fontFamily = '"Raleway", Helvetica, sans-serif';
		bar.text.style.fontSize = '2rem';

		bar.animate(0.78);
	
}); // End of use strict



                



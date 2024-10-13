//[Dashboard Javascript]

//Project:	Famosa Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
	
	
	var options = {
        series: [{
            name: "Desktops",
            data: [189, 156, 155, 118, 167, 159, 178, 223, 195, 201, 143]
        }],
        chart: {
          height: 348,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
		colors:['#cbccce'],
        dataLabels: {
          enabled: true,
			style: {
				fontSize: '16px',
				colors: ['#ffb822'],
			},
        },
        stroke: {
          	show: true,
			curve: 'smooth',
			lineCap: 'butt',
			colors: undefined,
			width: 4,
			dashArray: 0, 
        },		
		markers: {
			size: 6,
			colors: '#ffb822',
			strokeColors: '#ffffff',
			strokeWidth: 2,
			strokeOpacity: 0.9,
			strokeDashArray: 0,
			fillOpacity: 1,
			discrete: [],
			shape: "circle",
			radius: 5,
			offsetX: 0,
			offsetY: 0,
			onClick: undefined,
			onDblClick: undefined,
			hover: {
			  size: undefined,
			  sizeOffset: 3
			}
		},
        grid: {
          row: {
            colors: ['transparent'], // takes an array which will be repeated on columns
            opacity: 0
          },			
		  yaxis: {
			lines: {
			  show: false,
			},
		  },
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
		  labels: {
			show: false,
            offsetY: -18,        
          },
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          tooltip: {
            enabled: false,        
          },
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
              return val + "%";
            }
          }
        
        },
      };
      var chart = new ApexCharts(document.querySelector("#visits-chart"), options);
      chart.render();
	
	
	var options = {
	  chart: {
		  height: 340,
		  type: 'bar',
		  toolbar: {
			  show: false
		  }
	  },
	  plotOptions: {
		  bar: {
			  horizontal: false,
			  endingShape: 'rounded',
			  columnWidth: '35%',
		  },
	  },
	  dataLabels: {
		  enabled: false
	  },
	  stroke: {
		  show: true,
		  width: 2,
		  colors: ['transparent']
	  },
	  colors: ["#2444e8", "#c6cffb"],
	  series: [{
		  name: 'New Visitors',
		  data: [70, 45, 51, 58, 59, 58, 61, 65, 60, 69]
	  }, {
		  name: 'Unique Visitors',
		  data: [55, 71, 80, 100, 89, 98, 110, 95, 116, 90]
	  },],
	  xaxis: {
		  categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
		  axisBorder: {
			show: true,
			color: '#bec7e0',
		  },  
		  axisTicks: {
			show: true,
			color: '#bec7e0',
		  },    
	  },
	  legend: {
          position: 'top',
           horizontalAlign: 'right',
        },
	  yaxis: {
		  title: {
			  text: 'Visitors'
		  }
	  },
	  fill: {
		  opacity: 1

	  },
	  // legend: {
	  //     floating: true
	  // },
	  grid: {
		  row: {
			  colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
			  opacity: 0.2
		  },
		  borderColor: '#f1f3fa'
	  },
	  tooltip: {
		  y: {
			  formatter: function (val) {
				  return "" + val + "k"
			  }
		  }
	  }
	}

	var chart = new ApexCharts(
	  document.querySelector("#yearly-comparison"),
	  options
	);

	chart.render();
	
	
	var options = {
    chart: {
      height: 395,
      type: 'line',
      stacked: false,
      toolbar: {
        show: false
      },
    },
    stroke: {
      width: [0, 2, 5],
      curve: 'smooth'
    },
    plotOptions: {
      bar: {
        columnWidth: '40%',
        endingShape: 'rounded',        
      },
      
    },
    colors: ["#2444e8", "#fbadbf", "#45b6c6"],
    series: [{
      name: 'Project',
      type: 'column',
      data: [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 30]
    }, {
      name: 'Supplier',
      type: 'area',
      data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43]
    }, {
      name: 'Invoice',
      type: 'line',
      data: [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39]
    }],
    fill: {
        type: 'gradient',
        gradient: {
            inverseColors: true,
            shade: 'light',
            type: "horizontal",
            shadeIntensity: 0.25,
            gradientToColors: undefined,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 100, 100, 100]
        }
    },
    labels: ['01/01/2003', '02/01/2003','03/01/2003','04/01/2003','05/01/2003','06/01/2003','07/01/2003','08/01/2003','09/01/2003','10/01/2003','11/01/2003'],
    markers: {
      size: 0
    },
    xaxis: {
      type:'datetime',
      axisBorder: {
        show: true,
        color: '#eff1ff',
      },  
      axisTicks: {
        show: true,
        color: '#eff1ff',
      },    
    },
    yaxis: {
      min: 0
    },
    tooltip: {
      shared: true,
      intersect: false,
      y: {
        formatter: function (y) {
          if(typeof y !== "undefined") {
            return  y.toFixed(0) + "%";
          }
          return y;
          
        }
      }
    },
    legend: {
		position: 'top',
           horizontalAlign: 'right',
      labels: {
        useSeriesColors: true
      },
      offsetX: 0,
      offsetY: -10,
      markers: {
        customHTML: [
          function() {
            return ''
          }, function() {
            return ''
          }, function() {
            return ''
          }
        ]
      }
    }
  }

  var chart = new ApexCharts(
    document.querySelector("#crm_dash_2"),
    options
  );

  chart.render();
	
	
	
	
	/*****E-Charts function start*****/
			var eChart_1 = echarts.init(document.getElementById('e_chart_1'));
			var option = {
				tooltip: {
					show: true,
					backgroundColor: '#fff',
					borderRadius:6,
					padding:6,
					axisPointer:{
						lineStyle:{
							width:0,
						}
					},
					textStyle: {
						color: '#324148',
						fontFamily: '"Poppins", sans-serif',
						fontSize: 12
					}	
				},
				series: [
					{
						type: 'pie',
						selectedMode: 'single',
						radius: ['90%', '60%'],
						labelLine: {
							normal: {
								show: false
							}
						},
						color: ['#5867dd', '#0abb87'],
						data:[
							{value:435, name:''},
							{value:679, name:''},
						]
					}
				]
			};
			eChart_1.setOption(option);
			eChart_1.resize();
	
	
	
}); // End of use strict

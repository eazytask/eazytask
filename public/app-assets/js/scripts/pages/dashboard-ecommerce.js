/*=========================================================================================
    File Name: dashboard-ecommerce.js
    Description: dashboard ecommerce page content with Apexchart Examples
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(window).on('load', function() {
    'use strict';

    var $barColor = '#f3f3f3';
    var $trackBgColor = '#EBEBEB';
    var $textMutedColor = '#b9b9c3';
    var $budgetStrokeColor2 = '#dcdae3';
    var $goalStrokeColor2 = '#51e5a8';
    var $strokeColor = '#ebe9f1';
    var $textHeadingColor = '#5e5873';
    var $earningsStrokeColor2 = '#28c76f66';
    var $earningsStrokeColor3 = '#28c76f33';

    var $statisticsOrderChart = document.querySelector('#statistics-order-chart');
    var $earningsChart = document.querySelector('#earnings-chart');
    var $revenueReportChart = document.querySelector('#revenue-report-chart');
    var $budgetChart = document.querySelector('#budget-chart');

    var statisticsOrderChartOptions;
    var earningsChartOptions;
    var revenueReportChartOptions;
    var budgetChartOptions;

    var statisticsOrderChart;
    var earningsChart;
    var revenueReportChart;
    var budgetChart;
    var isRtl = $('html').attr('data-textdirection') === 'rtl';

    // On load Toast


    //------------ Statistics Bar Chart ------------
    //----------------------------------------------
    let orderChartData = [10,20,30,40,50,60,70]
    function fetchOrderChart() {
         $.ajax(
          {
            url: '/order/chart/data',
            type: 'GET',
            success: function (result) {
                orderChartData = result
                statisticsOrderChart.updateSeries([{
                    name: 'hour',
                    data: orderChartData
                  }])
            },
            error: function (error) {
              console.log(error);
            }
          }
        ); 
      }
    fetchOrderChart()

    statisticsOrderChartOptions = {
        chart: {
            height: 140,
            type: 'bar',
            stacked: true,
            toolbar: {
                show: false
            }
        },
        grid: {
            show: false,
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '25%',
                startingShape: 'rounded',
                colors: {
                    backgroundBarColors: [$barColor, $barColor, $barColor, $barColor, $barColor],
                    backgroundBarRadius: 5
                }
            }
        },
        legend: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        colors: [window.colors.solid.warning],
        series: [{
            name: 'hour',
            data: orderChartData
        }],
        xaxis: {
            categories: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
            labels: {
                style: {
                    colors: $textMutedColor,
                    fontSize: '0.86rem'
                }
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            show: true
        },
        tooltip: {
            x: {
                show: false
            }
        }
    };
    statisticsOrderChart = new ApexCharts($statisticsOrderChart, statisticsOrderChartOptions);
    statisticsOrderChart.render();

  
    //----------------------------------------------
    function fetchClientChart() {
        $.ajax(
         {
           url: '/client/portion/data',
           type: 'GET',
           success: function (result) {
            earningsChart.updateOptions({
                labels:result.labels
             });
            earningsChart.updateSeries(result.percentage)
            // earningsChart.updateLabels(result.labels)
           },
           error: function (error) {
             console.log(error);
           }
         }
       ); 
     }
     fetchClientChart()
    earningsChartOptions = {
        chart: {
            type: 'donut',
            height: 120,
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        series: [100],
        legend: { show: false },
        comparedResult: [2, -3, 8],
        labels: ['client'],
        stroke: { width: 0 },
        colors: [$earningsStrokeColor2, '#ea5455','#ff9f43','#00cfe8','#7367f0','#28c76f',$earningsStrokeColor3, window.colors.solid.success],
        grid: {
            padding: {
                right: -20,
                bottom: -8,
                left: -20
            }
        },
        plotOptions: {
            pie: {
                startAngle: -10,
                donut: {
                    labels: {
                        show: true,
                        name: {
                            offsetY: 15
                        },
                        value: {
                            offsetY: -15,
                            formatter: function(val) {
                                return parseInt(val) + '%';
                            }
                        },
                        total: {
                            show: true,
                            offsetY: 15,
                            label: 'Client',
                            formatter: function(w) {
                                return '100%';
                            }
                        }
                    }
                }
            }
        },
        responsive: [{
                breakpoint: 1325,
                options: {
                    chart: {
                        height: 100
                    }
                }
            },
            {
                breakpoint: 1200,
                options: {
                    chart: {
                        height: 120
                    }
                }
            },
            {
                breakpoint: 1045,
                options: {
                    chart: {
                        height: 100
                    }
                }
            },
            {
                breakpoint: 992,
                options: {
                    chart: {
                        height: 120
                    }
                }
            }
        ]
    };
    earningsChart = new ApexCharts($earningsChart, earningsChartOptions);
    earningsChart.render();

    //------------ Revenue Report Chart ------------
    //----------------------------------------------
    function fetchRevenueChart() {
        $.ajax(
         {
           url: '/revenue/report/data',
           type: 'GET',
           success: function (result) {
                revenueReportChart.updateSeries([{
                    name: 'Revenue',
                    data: result.revenue
                },
                {
                    name: 'Amount',
                    data: result.amount
                }])
           },
           error: function (error) {
             console.log(error);
           }
         }
       ); 
     }
     fetchRevenueChart()

     function GetMonthName(monthNumber) {
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return months[monthNumber];
    }
    var now = new Date();
    revenueReportChartOptions = {
        chart: {
            height: 230,
            stacked: true,
            type: 'bar',
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                columnWidth: '17%',
                endingShape: 'rounded'
            },
            distributed: true
        },
        colors: [window.colors.solid.primary, window.colors.solid.warning],
        series: [{
                name: 'Revenue',
                data: [ 100, 100, 100, 100, 100, 100]
            },
            {
                name: 'Amount',
                data: [-100, -100, -100, -100, -100, -100]
            }
        ],
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        grid: {
            padding: {
                top: -20,
                bottom: -10
            },
            yaxis: {
                lines: { show: false }
            }
        },
        xaxis: {
            categories: [GetMonthName((now.getMonth() - 5)), GetMonthName((now.getMonth() - 4)), GetMonthName((now.getMonth() - 3)), GetMonthName((now.getMonth() - 2)), GetMonthName((now.getMonth() - 1)), GetMonthName((now.getMonth()))],
            labels: {
                style: {
                    colors: $textMutedColor,
                    fontSize: '0.86rem'
                }
            },
            axisTicks: {
                show: false
            },
            axisBorder: {
                show: false
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: $textMutedColor,
                    fontSize: '0.86rem'
                }
            }
        }
    };
    revenueReportChart = new ApexCharts($revenueReportChart, revenueReportChartOptions);
    revenueReportChart.render();

    //---------------- Budget Chart ----------------
    //----------------------------------------------
    budgetChartOptions = {
        chart: {
            height: 80,
            toolbar: { show: false },
            zoom: { enabled: false },
            type: 'line',
            sparkline: { enabled: true }
        },
        stroke: {
            curve: 'smooth',
            dashArray: [0, 5],
            width: [2]
        },
        colors: [window.colors.solid.primary, $budgetStrokeColor2],
        series: [{
                data: [61, 48, 69, 52, 60, 40, 79, 60, 59, 43, 62]
            },
            {
                data: [20, 10, 30, 15, 23, 0, 25, 15, 20, 5, 27]
            }
        ],
        tooltip: {
            enabled: false
        }
    };
    budgetChart = new ApexCharts($budgetChart, budgetChartOptions);
    budgetChart.render();

    //------------ Browser State Charts ------------
    //----------------------------------------------

});
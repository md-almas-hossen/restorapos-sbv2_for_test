<?php if ($graphtype == 'salesgph') { ?>
  <div id="gradientLineArea2"></div>
<?php }
if ($graphtype == 'ordertype') {
?>
  <div id="monochromeChart"></div>
<?php }
if ($graphtype == 'purchasegph') {
?>
  <div id="barChart2"></div>
<?php }
if ($graphtype == 'waitersales') {
?>
  <div id="chartHorizontalBar2"></div>
<?php }
if ($graphtype == 'expincome') {
?>
  <div id="chartSpline2"></div>
<?php }
if ($graphtype == 'hourlyflow') {
?>
  <div id="lineChart2"></div>
<?php } ?>



<script src="<?php echo base_url('assets/chart/apexcharts/dist/apexcharts.min.js') ?>"></script>

<script src="<?php echo base_url('assets/chart/Chart.bundle.js') ?>"></script>
<script src="<?php echo base_url('assets/chart/chartjs-gauge.js') ?>"></script>
<script src="<?php echo base_url('assets/chart/chartjs-plugin-datalabels.js') ?>"></script>


<script src="<?php echo base_url('assets/js/Chart.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('dashboard/home/chartjs') ?>" type="text/javascript"></script>
<?php if ($graphtype == 'salesgph') { ?>
  <script>
    var saleamount = '<?php echo $saleamount; ?>';
    var alldays = '<?php echo $labelstring; ?>';

    var str10 = saleamount.substring(0, saleamount.length - 2);
    var daylysales2 = str10.split(',');

    var str9 = alldays.substring(0, alldays.length - 0);
    var daylist2 = str9.split(',');

    var options = {
      series: [{
        name: lang.saleamnt,
        data: daylysales2
      }],
      chart: {
        type: 'area',
        height: 370,
        zoom: {
          enabled: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'straight'
      },


      labels: daylist2,
      xaxis: {
        type: 'category',
      },
      yaxis: {
        opposite: true
      },
      legend: {
        horizontalAlign: 'left'
      }
    };
    var newchart = new ApexCharts(document.querySelector("#gradientLineArea2"), options);
    newchart.render();
  </script>
<?php }
if ($graphtype == 'ordertype') {
?>
  <script>
    var saleamount = '<?php echo $saleamount; ?>';
    var alldays = '<?php echo $labelstring; ?>';
    var str11 = saleamount.substring(0, saleamount.length - 2);
    var totaltpiesaledata2 = str11.split(',').map(element => {
      return Number(element);
    });

    var options = {
      series: totaltpiesaledata2,
      chart: {
        width: 460,
        type: 'pie',
      },
      legend: {
        position: 'top',
        horizontalAlign: 'center'
      },
      labels: [lang.Dine_In, lang.online, lang.TakeWay, lang.ThirdParty, lang.qr],
      responsive: [{
        breakpoint: 768,
        options: {
          chart: {
            width: 350
          }
        }
      }]
    };

    var piechart2 = new ApexCharts(document.querySelector("#monochromeChart"), options);
    piechart2.render();
  </script>
<?php }
if ($graphtype == 'purchasegph') { ?>
  <script>
    var exppurchase = '<?php echo $saleamount; ?>';
    var alldays = '<?php echo $labelstring; ?>';
    var str12 = exppurchase.substring(0, exppurchase.length - 2);
    var daylypurchase2 = str12.split(',');

    var str13 = alldays.substring(0, alldays.length - 0);
    var purdaylist2 = str13.split(',');
    var options = {
      series: [{
        name: lang.PurchaseAmount,
        data: daylypurchase2
      }],
      labels: purdaylist2,
      chart: {
        height: 350,
        type: "bar"
      },
      plotOptions: {
        bar: {
          dataLabels: {
            orientation: 'vertical',
            position: 'center' // bottom/center/top
          }
        }
      },

      colors: ["#00E396"]
    };
    var chartbar = new ApexCharts(document.querySelector("#barChart2"), options);
    chartbar.render();
  </script>
<?php }
if ($graphtype == 'waitersales') {
?>
  <script>
    var saleamount = '<?php echo $saleamount; ?>';
    var alldays = '<?php echo $labelstring; ?>';
    var str14 = saleamount.substring(0, saleamount.length - 0);
    var waitersales3 = str14.split(',');

    var str15 = alldays.substring(0, alldays.length - 0);
    var waiterdaylist2 = str15.split(',');

    var options = {
      series: [{
        name: lang.TotalOrderValue,
        data: waitersales3
      }],
      chart: {
        type: 'bar',
        height: 350
      },
      plotOptions: {
        bar: {
          borderRadius: 4,
          horizontal: true,
        }
      },
      dataLabels: {
        enabled: false
      },
      xaxis: {
        categories: waiterdaylist2,
      }
    };
    var waiterchart = new ApexCharts(document.querySelector("#chartHorizontalBar2"), options);
    waiterchart.render();
  </script>
<?php }
if ($graphtype == 'expincome') {
?>
  <script>
    var saleamount = '<?php echo $saleamount; ?>';
    var exppurchase = '<?php echo $purchaseamount; ?>';
    var alldays = '<?php echo $labelstring; ?>';

    var str16 = exppurchase.substring(0, exppurchase.length - 2);
    var expsales4 = str16.split(',');

    var str17 = saleamount.substring(0, saleamount.length - 2);
    var incomsales5 = str17.split(',');

    var str18 = alldays.substring(0, alldays.length - 0);
    var incexpdaylist2 = str18.split(',');

    var options = {
      series: [{
        name: lang.TotalIncome,
        data: incomsales5
      }, {
        name: lang.TotalExpense,
        data: expsales4
      }],
      chart: {
        height: 350,
        type: 'area'
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth'
      },
      labels: incexpdaylist2,
      xaxis: {
        type: 'category',
        categories: []
      },
      tooltip: {
        x: {
          format: 'dd/MM/yy HH:mm'
        },
      },
    };
    var expincomechart = new ApexCharts(document.querySelector("#chartSpline2"), options);
    expincomechart.render();
  </script>
<?php }
if ($graphtype == 'hourlyflow') {
?>
  <script>
    var saleamount = '<?php echo $saleamount; ?>';
    var exppurchase = '<?php echo $purchaseamount; ?>';
    var alldays = '<?php echo $labelstring; ?>';
    var str19 = exppurchase.substring(0, exppurchase.length - 0);
    var hourlyordernumber2 = str19.split(',').map(element => {
      return Number(element);
    });
    var str20 = saleamount.substring(0, saleamount.length - 2);
    var hourlyorderamount2 = str20.split(',').map(element => {
      return Number(element);
    });
    var str21 = alldays.substring(0, alldays.length - 0);
    var timeslot2 = str21.split(',');

    var options = {
      chart: {
        height: 350,
        type: "line",
        stacked: false,
      },
      plotOptions: {
        line: {
          style: {
            fontSize: "12px",
            fontWeight: "bold",
          },
          background: {
            enabled: true,
            foreColor: "#fff"
          }
        },
        bar: {
          dataLabels: {
            position: "bottom",
            offset: -10,
            style: {
              colors: ["#FFF"]
            },
            background: {
              enabled: true,
              foreColor: "#fff"
            }
          }
        }
      },
      stroke: {
        width: [0, 0, 3, 3],
        curve: 'smooth'
      },
      series: [{
          name: lang.OrderValue,
          type: "column",
          data: hourlyorderamount2
        },
        {
          name: lang.ordnumb,
          type: "column",
          data: hourlyordernumber2
        },
        {
          name: lang.OrderValueLine,
          type: "line",
          data: hourlyorderamount2
        },
        {
          name: lang.OrderNumberLine,
          type: "line",
          data: hourlyordernumber2
        }
      ],
      // colors: ['#008ffb','##00e396','#008ffb','##00e396'],
      colors: ["#008ffb", "#00e396"],
      // colors: ["#480048", "#C04848"],
      // colors: ["#348AC7", "#7474BF"],
      // colors: ["#185a9d", "#43cea2"],

      xaxis: {
        categories: timeslot2,
        axisBorder: {
          show: true,
          color: "#0ABFBC"
        },
        axisTicks: {
          show: true,
          color: "#bec7e0"
        },
        labels: {
          style: {
            fontWeight: 'bold',
          },
        },
      },

      tooltip: {
        enabled: true,
        shared: true
      },
      grid: {
        // borderColor: "#eff2f7",
        strokeDashArray: 4,
      },
      legend: {
        position: "top",
        // offsetY: 6,
      },
      responsive: [{
        breakpoint: 600,
        options: {
          yaxis: {
            show: false
          },
          legend: {
            show: true
          }
        }
      }]
    };
    var linecharthr = new ApexCharts(document.querySelector("#lineChart2"), options);
    linecharthr.render();
  </script>
<?php } ?>
<!--<script src="<?php //echo base_url('application/modules/dashboard/assest/js/chartdatareset.js'); 
                  ?>" type="text/javascript"></script>-->
@extends('layouts.app')

@section('content')
<div class="nk-block nk-block-lg">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Graph</h1>
    </div>
    <div class="row g-gs">
        <div class="col-md-12">
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="card-head">
                        {{-- @foreach ($items as $key => $value)
                            {{$value['count']}}
                        @endforeach --}}
                        <h6 class="title">Employee Graph</h6>
                    </div>
                    <div class="nk-ck-sm">
                        <canvas class="bar-chart" id="barChartStacked"></canvas>
                    </div>
                </div>
            </div><!-- .card-preview -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript">
         $(function () {

        /*------------------------------------------
        --------------------------------------------
        Pass Header Token
        --------------------------------------------
        --------------------------------------------*/
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var items = {{ Js::from($items) }};

        let labels = [];
        let months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        let data = [];
        months.forEach(month => {
            items.forEach(element => {
                // console.log(month);
                // console.log(element.month_name.slice(0, 3));
                if (month == element.month_name.slice(0, 3)) {
                    data.push(element.count)
                } else {
                    data.push(0)
                }
            });
        });
        var barChartStacked = {
            labels: months,
            stacked: true,
            dataUnit: 'Employees',
            datasets: [{
            label: "Employees Data",
            color: "#9cabff",
            data: data
            }]
        };

        var barChartMultiple = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            dataUnit: 'USD',
            datasets: [{
            label: "Income",
            color: "#9cabff",
            data: [110, 80, 125, 55, 95, 75, 90, 110, 80]
            }, {
            label: "Expense",
            color: "#f4aaa4",
            data: [75, 90, 110, 80, 125, 55, 95, 75, 90, 110, 80, 125]
            }]
        };

        function barChart(selector, set_data) {
    var $selector = selector ? $(selector) : $('.bar-chart');
    $selector.each(function () {
      var $self = $(this),
          _self_id = $self.attr('id'),
          _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data,
          _d_legend = typeof _get_data.legend === 'undefined' ? false : _get_data.legend;

      var selectCanvas = document.getElementById(_self_id).getContext("2d");
      var chart_data = [];

      for (var i = 0; i < _get_data.datasets.length; i++) {
        chart_data.push({
          label: _get_data.datasets[i].label,
          data: _get_data.datasets[i].data,
          // Styles
          backgroundColor: _get_data.datasets[i].color,
          borderWidth: 2,
          borderColor: 'transparent',
          hoverBorderColor: 'transparent',
          borderSkipped: 'bottom',
          barPercentage: .6,
          categoryPercentage: .7
        });
      }

      var chart = new Chart(selectCanvas, {
        type: 'bar',
        data: {
          labels: _get_data.labels,
          datasets: chart_data
        },
        options: {
          legend: {
            display: _get_data.legend ? _get_data.legend : false,
            rtl: NioApp.State.isRTL,
            labels: {
              boxWidth: 30,
              padding: 20,
              fontColor: '#6783b8'
            }
          },
          maintainAspectRatio: false,
          tooltips: {
            enabled: true,
            rtl: NioApp.State.isRTL,
            callbacks: {
              title: function title(tooltipItem, data) {
                return data.datasets[tooltipItem[0].datasetIndex].label;
              },
              label: function label(tooltipItem, data) {
                return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
              }
            },
            backgroundColor: '#eff6ff',
            titleFontSize: 13,
            titleFontColor: '#6783b8',
            titleMarginBottom: 6,
            bodyFontColor: '#9eaecf',
            bodyFontSize: 12,
            bodySpacing: 4,
            yPadding: 10,
            xPadding: 10,
            footerMarginTop: 0,
            displayColors: false
          },
          scales: {
            yAxes: [{
              display: true,
              stacked: _get_data.stacked ? _get_data.stacked : false,
              position: NioApp.State.isRTL ? "right" : "left",
              ticks: {
                beginAtZero: true,
                fontSize: 12,
                fontColor: '#9eaecf',
                padding: 5
              },
              gridLines: {
                color: NioApp.hexRGB("#526484", .2),
                tickMarkLength: 0,
                zeroLineColor: NioApp.hexRGB("#526484", .2)
              }
            }],
            xAxes: [{
              display: true,
              stacked: _get_data.stacked ? _get_data.stacked : false,
              ticks: {
                fontSize: 12,
                fontColor: '#9eaecf',
                source: 'auto',
                padding: 5,
                reverse: NioApp.State.isRTL
              },
              gridLines: {
                color: "transparent",
                tickMarkLength: 10,
                zeroLineColor: 'transparent'
              }
            }]
          }
        }
      });
    });
  } // init bar chart


  barChart();

    });

</script>
@endpush

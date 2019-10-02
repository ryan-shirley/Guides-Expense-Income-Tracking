@extends('layouts.main')

@section('page-title', 'Admin Dashboard')

@section('header')
<!-- Expenses stats -->
<div class="row justify-content-center">
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total spent - {{ date('Y') }}</h5>
                        <span class="h2 font-weight-bold mb-0">€{{ $total_year }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Total spent current year -->
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Income - {{ date('Y') }}</h5>
                        <span class="h2 font-weight-bold mb-0">€{{ $incomeForYear }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Income current year -->
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Bank Balance</h5>
                        <span class="h2 font-weight-bold mb-0">€{{ $bankBalance }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Bank Balance -->
    <div class="col-xl-3 col-lg-6 ">
        <div class="card card-stats mb-4 mb-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Left to pay back</h5>
                        <span class="h2 font-weight-bold mb-0">€{{ $total_to_pay_back }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fas fa-piggy-bank"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Left to pay back -->
    <div class="col-xl-3 col-lg-6">
        <div class="card card-stats mb-4 mb-xl-0 mt-4">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Waiting for approval</h5>
                        <span class="h2 font-weight-bold mb-0">{{ $num_waiting_approval }}</span>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fas fa-pause"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Waiting on approval -->
</div>
<!-- /.Expenses Stats -->
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card bg-gradient-default shadow mb-3">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                        <h2 class="text-white mb-0">Payment History</h2>
                    </div>
                </div>
            </div>
            <!-- /.Card Header -->
            <div class="card-body">
                <!-- Chart -->
                <div class="cart">
                    <!-- Chart wrapper -->
                    <canvas id="chart-payment-history" class="chart-canvas"></canvas>
                </div>
            </div>
            <!-- /.Card Body -->
        </div>
    </div>
    <!-- /.Column Payment History -->
    <div class="col-md-6">
        <div class="card bg-gradient-default shadow mb-3">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                        <h2 class="text-white mb-0">Income History</h2>
                    </div>
                </div>
            </div>
            <!-- /.Card Header -->
            <div class="card-body">
                <!-- Chart -->
                <div class="cart">
                    <!-- Chart wrapper -->
                    <canvas id="chart-income-history" class="chart-canvas"></canvas>
                </div>
            </div>
            <!-- /.Card Body -->
        </div>
    </div>
    <!-- /.Column Bank History -->
    <div class="col-md-12">
        <div class="card bg-gradient-default shadow mb-3">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                        <h2 class="text-white mb-0">Bank History</h2>
                    </div>
                </div>
            </div>
            <!-- /.Card Header -->
            <div class="card-body">
                <!-- Chart -->
                <div class="cart">
                    <!-- Chart wrapper -->
                    <canvas id="chart-bank-history" class="chart-canvas"></canvas>
                </div>
            </div>
            <!-- /.Card Body -->
        </div>
    </div>
    <!-- /.Column Bank History -->
</div>
<!-- /.Row -->
@endsection

@section('scripts')
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<!-- <script src="{{ asset('js/plugins/chart.js/dist/Chart.min.js') }}"></script> -->
<script type="text/javascript" defer>

    //
    // Charts
    //
    'use strict';

    var Charts = (function() {

        // Variable
        var $toggle = $('[data-toggle="chart"]');
        var mode = 'light'; //(themeMode) ? themeMode : 'light';
        var fonts = {
            base: 'Open Sans'
        }

        // Colors
        var colors = {
            gray: {
            100: '#f6f9fc',
            200: '#e9ecef',
            300: '#dee2e6',
            400: '#ced4da',
            500: '#adb5bd',
            600: '#8898aa',
            700: '#525f7f',
            800: '#32325d',
            900: '#212529'
            },
            theme: {
            'default': '#172b4d',
            'primary': '#5e72e4',
            'secondary': '#f4f5f7',
            'info': '#11cdef',
            'success': '#2dce89',
            'danger': '#f5365c',
            'warning': '#fb6340'
            },
            black: '#12263F',
            white: '#FFFFFF',
            transparent: 'transparent',
        };
        // Methods

        // Chart.js global options
        function chartOptions() {
            // Options
            var options = {
                defaults: {
                    global: {
                        responsive: true,
                        maintainAspectRatio: false,
                        defaultColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
                        defaultFontColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
                        defaultFontFamily: fonts.base,
                        defaultFontSize: 13,
                        layout: {
                            padding: 0
                        },
                        legend: {
                            display: false,
                            position: 'bottom',
                            labels: {
                            usePointStyle: true,
                            padding: 16
                            }
                        },
                        elements: {
                            point: {
                            radius: 0,
                            backgroundColor: colors.theme['primary']
                            },
                            line: {
                            tension: .4,
                            borderWidth: 4,
                            borderColor: colors.theme['primary'],
                            backgroundColor: colors.transparent,
                            borderCapStyle: 'rounded'
                            },
                            rectangle: {
                            backgroundColor: colors.theme['warning']
                            },
                            arc: {
                            backgroundColor: colors.theme['primary'],
                            borderColor: (mode == 'dark') ? colors.gray[800] : colors.white,
                            borderWidth: 4
                            }
                        },
                        tooltips: {
                            enabled: false,
                            mode: 'index',
                            intersect: false,
                            custom: function(model) {

                            // Get tooltip
                            var $tooltip = $('#chart-tooltip');

                            // Create tooltip on first render
                            if (!$tooltip.length) {
                                $tooltip = $('<div id="chart-tooltip" class="popover bs-popover-top" role="tooltip"></div>');

                                // Append to body
                                $('body').append($tooltip);
                            }

                            // Hide if no tooltip
                            if (model.opacity === 0) {
                                $tooltip.css('display', 'none');
                                return;
                            }

                            function getBody(bodyItem) {
                                return bodyItem.lines;
                            }

                            // Fill with content
                            if (model.body) {
                                var titleLines = model.title || [];
                                var bodyLines = model.body.map(getBody);
                                var html = '';

                                // Add arrow
                                html += '<div class="arrow"></div>';

                                // Add header
                                titleLines.forEach(function(title) {
                                html += '<h3 class="popover-header text-center">' + title + '</h3>';
                                });

                                // Add body
                                bodyLines.forEach(function(body, i) {
                                var colors = model.labelColors[i];
                                var styles = 'background-color: ' + colors.backgroundColor;
                                var indicator = '<span class="badge badge-dot"><i class="bg-primary"></i></span>';
                                var align = (bodyLines.length > 1) ? 'justify-content-left' : 'justify-content-center';
                                html += '<div class="popover-body d-flex align-items-center ' + align + '">' + indicator + body + '</div>';
                                });

                                $tooltip.html(html);
                            }

                            // Get tooltip position
                            var $canvas = $(this._chart.canvas);

                            var canvasWidth = $canvas.outerWidth();
                            var canvasHeight = $canvas.outerHeight();

                            var canvasTop = $canvas.offset().top;
                            var canvasLeft = $canvas.offset().left;

                            var tooltipWidth = $tooltip.outerWidth();
                            var tooltipHeight = $tooltip.outerHeight();

                            var top = canvasTop + model.caretY - tooltipHeight - 16;
                            var left = canvasLeft + model.caretX - tooltipWidth / 2;

                            // Display tooltip
                            $tooltip.css({
                                'top': top + 'px',
                                'left': left + 'px',
                                'display': 'block',
                                'z-index': '100'
                            });

                            },
                            callbacks: {
                            label: function(item, data) {
                                var label = data.datasets[item.datasetIndex].label || '';
                                var yLabel = item.yLabel;
                                var content = '';

                                if (data.datasets.length > 1) {
                                content += '<span class="badge badge-primary mr-auto">' + label + '</span>';
                                }

                                content += '<span class="popover-body-value">' + yLabel + '</span>';
                                return content;
                            }
                            }
                        }
                    },
                    doughnut: {
                        cutoutPercentage: 83,
                        tooltips: {
                            callbacks: {
                                title: function(item, data) {
                                    var title = data.labels[item[0].index];
                                    return title;
                                },
                                label: function(item, data) {
                                    var value = data.datasets[0].data[item.index];
                                    var content = '';

                                    content += '<span class="popover-body-value">' + value + '</span>';
                                    return content;
                                }
                            }
                        },
                        legendCallback: function(chart) {
                            var data = chart.data;
                            var content = '';

                            data.labels.forEach(function(label, index) {
                                var bgColor = data.datasets[0].backgroundColor[index];

                                content += '<span class="chart-legend-item">';
                                content += '<i class="chart-legend-indicator" style="background-color: ' + bgColor + '"></i>';
                                content += label;
                                content += '</span>';
                            });

                            return content;
                        }
                    }
                }
            }

            // yAxes
            Chart.scaleService.updateScaleDefaults('linear', {
                gridLines: {
                    borderDash: [2],
                    borderDashOffset: [2],
                    color: (mode == 'dark') ? colors.gray[900] : colors.gray[300],
                    drawBorder: false,
                    drawTicks: false,
                    lineWidth: 0,
                    zeroLineWidth: 0,
                    zeroLineColor: (mode == 'dark') ? colors.gray[900] : colors.gray[300],
                    zeroLineBorderDash: [2],
                    zeroLineBorderDashOffset: [2]
                },
                ticks: {
                    beginAtZero: true,
                    padding: 10,
                    callback: function(value) {
                        if (!(value % 10)) {
                            return value
                        }
                    }
                }
            });

            // xAxes
            Chart.scaleService.updateScaleDefaults('category', {
                gridLines: {
                    drawBorder: false,
                    drawOnChartArea: false,
                    drawTicks: false
                },
                ticks: {
                    padding: 20
                },
                maxBarThickness: 10
            });

            return options;
        }

        // Parse global options
        function parseOptions(parent, options) {
            for (var item in options) {
                if (typeof options[item] !== 'object') {
                    parent[item] = options[item];
                }
                else {
                    parseOptions(parent[item], options[item]);
                }
            }
        }

        // Events

        // Parse global options
        if (window.Chart) {
            parseOptions(Chart, chartOptions());
        }

        // Return
        return {
            colors: colors,
            fonts: fonts,
            mode: mode
        };
    })();

    //
    // Payment History chart
    //
    var PaymentChart = (function() {
        // Variables
        var $chart = $('#chart-payment-history');

        // Methods
        function init($chart) {
            var salesChart = new Chart($chart, {
                type: 'line',
                options: {
                    scales: {
                        yAxes: [{
                            gridLines: {
                                lineWidth: 0,
                                color: Charts.colors.gray[900],
                                zeroLineColor: Charts.colors.gray[900]
                            },
                            ticks: {
                                callback: function(value) {
                                    if (!(value % 10)) {
                                        return '€' + value;
                                    }
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(item, data) {
                                var label = data.datasets[item.datasetIndex].label || '';
                                var yLabel = item.yLabel;
                                var content = '';

                                if (data.datasets.length > 1) {
                                content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                                }

                                content += '<span class="popover-body-value">€' + yLabel + '</span>';
                                return content;
                            }
                        }
                    }
                },
                data: {
                    labels: {!! $paymentHistory['paymentMonths'] !!},
                    datasets: [{
                        label: 'Performance',
                        data: {!! $paymentHistory['paymentValues'] !!}
                    }]
                }
            });

            // Save to jQuery object
            $chart.data('chart', salesChart);
        };

        // Events
        if ($chart.length) {
            init($chart);
        }
    })();

    //
    // Bank History chart
    //
    var BankChart = (function() {
        // Variables
        var $chart = $('#chart-bank-history');

        // Methods
        function init($chart) {
            var salesChart = new Chart($chart, {
                type: 'line',
                options: {
                    scales: {
                        yAxes: [{
                            gridLines: {
                                lineWidth: 0,
                                color: Charts.colors.gray[900],
                                zeroLineColor: Charts.colors.gray[900]
                            },
                            ticks: {
                                callback: function(value) {
                                    if (!(value % 10)) {
                                        return '€' + value;
                                    }
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(item, data) {
                                var label = data.datasets[item.datasetIndex].label || '';
                                var yLabel = item.yLabel;
                                var content = '';

                                if (data.datasets.length > 1) {
                                content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                                }

                                content += '<span class="popover-body-value">€' + yLabel + '</span>';
                                return content;
                            }
                        }
                    }
                },
                data: {
                    labels: {!! $bankHistory['bankMonths'] !!},
                    datasets: [{
                        label: 'Performance',
                        data: {!! $bankHistory['bankValues'] !!}
                    }]
                }
            });

            // Save to jQuery object
            $chart.data('chart', salesChart);
        };

        // Events
        if ($chart.length) {
            init($chart);
        }
    })();

    //
    // Income History chart
    //
    var IncomeChart = (function() {
        // Variables
        var $chart = $('#chart-income-history');

        // Methods
        function init($chart) {
            var salesChart = new Chart($chart, {
                type: 'line',
                options: {
                    scales: {
                        yAxes: [{
                            gridLines: {
                                lineWidth: 0,
                                color: Charts.colors.gray[900],
                                zeroLineColor: Charts.colors.gray[900]
                            },
                            ticks: {
                                callback: function(value) {
                                    if (!(value % 10)) {
                                        return '€' + value;
                                    }
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(item, data) {
                                var label = data.datasets[item.datasetIndex].label || '';
                                var yLabel = item.yLabel;
                                var content = '';

                                if (data.datasets.length > 1) {
                                content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                                }

                                content += '<span class="popover-body-value">€' + yLabel + '</span>';
                                return content;
                            }
                        }
                    }
                },
                data: {
                    labels: {!! $incomeHistory['incomeMonths'] !!},
                    datasets: [{
                        label: 'Performance',
                        data: {!! $incomeHistory['incomeValues'] !!}
                    }]
                }
            });

            // Save to jQuery object
            $chart.data('chart', salesChart);
        };

        // Events
        if ($chart.length) {
            init($chart);
        }
    })();
</script>
@endsection
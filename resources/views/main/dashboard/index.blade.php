@extends('template.master')

@section('page-title', 'Dashboard')
@section('page-sub-title', 'Data')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.2/dist/echarts.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-6 col-sm-6"></div>
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="row">
                <div class="col-5">
                    <div class="form-group">
                        {{-- <label for="awal" class="col-form-label">Tanggal Awal</label> --}}
                        <input type="date" class="form-control" id="start" name="start" value="{{ date('Y-m-d') }}" max="{{date('Y-m-d')}}">
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group">
                        {{-- <label for="akhir" class="col-form-label">Tanggal Akhir</label> --}}
                        <input type="date" class="form-control" id="end" name="end"
                            value="{{ date('Y-m-d') }}" max="{{date('Y-m-d')}}">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        {{-- <label for="kategori" class="col-form-label" style="color: #8092ec;">Search</label> --}}
                        <button class="btn btn-secondary btn-rounded btn-search">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Medicine"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">Stock</p>
                        <p class="text-primary text-24 line-height-1 mb-2 stock-total">0</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Medicine-2"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">Incoming</p>
                        <p class="text-primary text-24 line-height-1 mb-2 incoming-total">0</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Medicine-3"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">Outgoing</p>
                        <p class="text-primary text-24 line-height-1 mb-2 outgoing-total">0</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-3"></div>
        <div class="col-6">
            <div id="main" style="width: 800px;height:700px;"></div>
        </div>
        <div class="col-3"></div> --}}
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-title">Medicines In Out Total</div>
                    <div id="barChart" style="width: 100%;height:400px"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <div class="card-title">Medicine In Out by Name</div>
                        </div>
                        <div class="col-4 float-right">
                            <select name="category" id="category" class="form-control">
                                <option value="incoming">Incoming</option>
                                <option value="outgoing">Outgoing</option>
                            </select>
                        </div>
                    </div>
                    <div id="pieChart" style="width: 100%;height:400px"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chroma-js/2.1.0/chroma.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            const startOfMonth = moment().startOf('month').format('YYYY-MM-DD');
            const endOfMonth = moment().endOf('month').format('YYYY-MM-DD');

            $('#start').val(startOfMonth)
            $('#end').val(endOfMonth)

            // function chartInOutMedicines(start, end) {
            //     $.ajax({
            //         type: "POST",
            //         url: "{{ route('dashboard.chart.inout.medicines') }}",
            //         data: {
            //             _token: "{{ csrf_token() }}",
            //             start: start,
            //             end: end
            //         },
            //         success: function(response) {
            //             // Prepare data for the chart
            //             var monthNames = {
            //                 1: "Jan",
            //                 2: "Feb",
            //                 3: "Mar",
            //                 4: "Apr",
            //                 5: "May",
            //                 6: "Jun",
            //                 7: "Jul",
            //                 8: "Aug",
            //                 9: "Sep",
            //                 10: "Oct",
            //                 11: "Nov",
            //                 12: "Dec"
            //             };

            //             var xAxisData = []; // Array to store month names
            //             var incomingData = []; // Array to store incoming values
            //             var outgoingData = []; // Array to store outgoing values

            //             var incomingTotal = 0;
            //             var outgoingTotal = 0;
            //             Object.keys(response).forEach(function(year) {
            //                 var months = Object.keys(response[year]);
            //                 var totalIncoming =0; // Initialize the total incoming value for the current year
            //                 var totalOutgoing =0; // Initialize the total incoming value for the current year

            //                 months.forEach(function(month) {
            //                     // Calculate incoming value for the current month
            //                     var incoming = response[year][month]['incoming'];
            //                     var outgoing = response[year][month]['outgoing'];

            //                     totalIncoming += parseInt(incoming); // Accumulate incoming value for the current year
            //                     totalOutgoing += parseInt(outgoing); // Accumulate incoming value for the current year

            //                     // Push values to respective arrays
            //                     incomingData.push(incoming);
            //                     outgoingData.push(outgoing);
            //                 });

            //                 // Store the total incoming value for the current year
            //                 incomingTotal = totalIncoming;
            //                 outgoingTotal = totalOutgoing;
            //             });

            //             $('.incoming-total').text(incomingTotal)
            //             $('.outgoing-total').text(outgoingTotal)

            //             // Initialize ECharts instance
            //             var chart = echarts.init(document.getElementById('barChart'));

            //             // Configure the chart options
            //             var options = {
            //                 tooltip: {},
            //                 legend: {
            //                     data: ['Incoming', 'Outgoing']
            //                 },
            //                 xAxis: {
            //                     data: xAxisData,
            //                     type: 'category'
            //                 },
            //                 yAxis: {},
            //                 series: [{
            //                         name: 'Incoming',
            //                         type: 'bar',
            //                         data: incomingData
            //                     },
            //                     {
            //                         name: 'Outgoing',
            //                         type: 'bar',
            //                         data: outgoingData
            //                     }
            //                 ]
            //             };

            //             // Set chart options and render the chart
            //             chart.setOption(options);
            //         },
            //         error: function(error) {
            //             console.log("Error", error);
            //         },
            //     });
            // }

            function chartInOutMedicines(start, end) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.chart.inout.medicines') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        start: start,
                        end: end
                    },
                    success: function(response) {
                        // Prepare data for the chart
                        var monthNames = {
                            1: "Jan",
                            2: "Feb",
                            3: "Mar",
                            4: "Apr",
                            5: "May",
                            6: "Jun",
                            7: "Jul",
                            8: "Aug",
                            9: "Sep",
                            10: "Oct",
                            11: "Nov",
                            12: "Dec"
                        };

                        var xAxisData = []; // Array to store month names
                        var incomingData = []; // Array to store incoming values
                        var outgoingData = []; // Array to store outgoing values

                        var incomingTotal = 0;
                        var outgoingTotal = 0;

                        // Generate a dynamic color palette using Chroma.js
                        var barColors = chroma.scale(['#4D2DB7', '#94A684']).mode('lch').colors(2);

                        // Loop through the response data
                        for (var year in response) {
                            for (var month = 1; month <= 12; month++) {
                                var dataForMonth = response[year][month.toString()];

                                if (dataForMonth) {
                                    var incoming = dataForMonth.incoming || 0;
                                    var outgoing = dataForMonth.outgoing || 0;

                                    incomingTotal += parseInt(incoming);
                                    outgoingTotal += parseInt(outgoing);

                                    xAxisData.push(monthNames[month]);
                                    incomingData.push(incoming);
                                    outgoingData.push(outgoing);
                                } else {
                                    // Handle missing data here, you can set it to 0 or a default value
                                    xAxisData.push(monthNames[month]);
                                    incomingData.push(0);
                                    outgoingData.push(0);
                                }
                            }
                        }

                        $('.incoming-total').text(incomingTotal);
                        $('.outgoing-total').text(outgoingTotal);

                        // Initialize ECharts instance
                        var chart = echarts.init(document.getElementById('barChart'));

                        // Configure the chart options
                        var options = {
                            tooltip: {},
                            legend: {
                                data: ['Incoming', 'Outgoing']
                            },
                            xAxis: {
                                data: xAxisData,
                                type: 'category',
                                axisLabel: {
                                    rotate: 45, // Rotate x-axis labels for better readability if needed
                                    interval: 0 // Show all labels
                                }
                            },
                            yAxis: {},
                            series: [{
                                    name: 'Incoming',
                                    type: 'bar',
                                    data: incomingData,
                                    itemStyle: {
                                        color: barColors[
                                            0] // Apply the first color to the Incoming bars
                                    }
                                },
                                {
                                    name: 'Outgoing',
                                    type: 'bar',
                                    data: outgoingData,
                                    itemStyle: {
                                        color: barColors[
                                            1] // Apply the second color to the Outgoing bars
                                    }
                                }
                            ]
                        };

                        // Set chart options and render the chart
                        chart.setOption(options);
                    },
                    error: function(error) {
                        console.log("Error", error);
                    },
                });
            }

            function pieChartInOutByCategory(start, end, category) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('dashboard.chart.inout.category') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        start: start,
                        end: end,
                        category: category
                    },
                    success: function(response) {
                        $('.stock-total').text(response.stock)

                        // Generate a dynamic color palette using Chroma.js
                        var pieColors = chroma.scale(['#FF7F50', '#3398DB']).mode('lch').colors(response
                            .data.length);

                        // Prepare data for the chart
                        var chartData = response.data.map(function(item, index) {
                            return {
                                name: item.medicine,
                                value: item.total_quantity,
                                itemStyle: {
                                    color: pieColors[index]
                                }
                            };
                        });

                        // Initialize ECharts instance
                        var chart = echarts.init(document.getElementById('pieChart'));

                        // Configure the chart options
                        var options = {
                            tooltip: {
                                trigger: 'item',
                                formatter: '{a} <br/>{b}: {c} ({d}%)'
                            },
                            legend: {
                                orient: 'vertical',
                                left: 10,
                                data: chartData.map(function(item) {
                                    return item.name;
                                })
                            },
                            series: [{
                                name: 'Quantity',
                                type: 'pie',
                                radius: '55%',
                                center: ['50%', '60%'],
                                data: chartData,
                                emphasis: {
                                    itemStyle: {
                                        shadowBlur: 10,
                                        shadowOffsetX: 0,
                                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                                    }
                                }
                            }]
                        };

                        // Set chart options and render the chart
                        chart.setOption(options);
                    }
                });
            }

            // initial first chart
            chartInOutMedicines($('#start').val(), $('#end').val())
            pieChartInOutByCategory($('#start').val(), $('#end').val(), 'incoming')

            $('body').on('click', '.btn-search', function() {
                chartInOutMedicines($('#start').val(), $('#end').val())
                pieChartInOutByCategory($('#start').val(), $('#end').val(), $('#category').val())
            });

            $('body').on('change', '#category', function() {
                pieChartInOutByCategory($('#start').val(), $('#end').val(), $('#category').val())
            })
        });
    </script>
@endpush

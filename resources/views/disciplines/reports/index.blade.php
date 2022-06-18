@extends('layouts.app')
@section('content')

    <style>
        #chartdiv {
            width: 100%;
            height: 350px;
        }
    </style>
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Disiplin Raporlarınız</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            <div class="form-group" style="margin-right: 20px;">
                                 <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Personel Seçiniz</label>
                                            {!! Form::select('employees',$employees,null,['class'=>'form-control selectpicker employee','data-live-search'=>'true']) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-4 gutter-b mt-8">
                                        <div class="form-group">
                                         <button class="btn btn-primary filtrele">Filtrele</button>
                                            <a href="{{route('reportIndexPdf',$id)}}"><button class="btn btn-success filtrele">PDF ÇIKTI AL</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="alert  alert-custom alert-warning" role="alert">
                                <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                                <div class="alert-text">{{$id == 0 ? 'ŞUANDA FİRMANIZIN TÜM PERSONEL DİSİPLİN SUÇ KAYITLARI RAPORLANMAKTADIR!! FİLTRELEME YAPARAK PERSONEL SEÇEBİLİRSİNİZ' : $employee->full_name.' VERİLERİ LİSTELENMEKTEDİR'}}</div>
                            </div>
                            <div class=" gutter-b mt-5">

                                <label style="font-size: 15px;font-weight: bold">SEÇİLİ PERSONEL : {{$id == 0 ? 'TÜM PERSONELLER' : $employee->full_name}}</label><br>
                                <label style="font-size: 15px;font-weight: bold">TOPLAM DİSİPLİN SAYISI : {{$disciplines->count()}}</label>
                            </div>
                          <div class="row gutter-b mt-5">

                                  <div id="chartdiv"></div>



                          </div>
                        </div>
                        <!--end:: Card body-->
                    </div>
                    <!--end:: Card-->
                </div>
            </div>

            <!--end::Row-->
            <!--end::Dashboard-->
        </div>

        <!--end::Container-->
    </div>

    <!--end::Entry-->
@stop

@push('scripts')

    <script>
        $(".filtrele").click(function ()
        {
           var id = $('select[name=employees] option').filter(':selected').val()

           console.log(id);

                window.location.href = '{{env('APP_URL')}}' + '/company_discipline_report/' + id;
        });
    </script>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script>
        am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("chartdiv");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "none",
                wheelY: "none",
                layout: root.verticalLayout
            }));


// Data
            var data = [{
                year: "OCAK",
                value: {{isset($infos['01']) ? $infos['01'] : 0 }}
            }, {
                year: "ŞUBAT",
                value:  {{isset($infos['02']) ? $infos['02'] : 0 }}
            }, {
                year: "MART",
                value:  {{isset($infos['03']) ? $infos['03'] : 0 }}
            }, {
                year: "NİSAN",
                value:  {{isset($infos['04']) ? $infos['04'] : 0 }}
            }, {
                year: "MAYIS",
                value: {{isset($infos['05']) ? $infos['05'] : 0 }}
            }, {
                    year: "HAZİRAN",
                    value: {{isset($infos['06']) ? $infos['06'] : 0 }}
            }, {
                year: "TEMMUZ",
                value:  {{isset($infos['07']) ? $infos['07'] : 0 }}
            }, {
                year: "AĞUSTOS",
                value:  {{isset($infos['08']) ? $infos['08'] : 0 }}
            }, {
                    year: "EYLÜL",
                    value:  {{isset($infos['09']) ? $infos['09'] : 0 }}
            },{
                    year: "EKİM",
                    value:  {{isset($infos['10']) ? $infos['10'] : 0 }}
            }, {
                    year: "KASIM",
                    value:  {{isset($infos['11']) ? $infos['11'] : 0 }}
            }, {
                    year: "ARALIK",
                    value:  {{isset($infos['12']) ? $infos['12'] : 0 }}
            }

            ];

// Populate data
            for (var i = 0; i < (data.length - 1); i++) {
                data[i].valueNext = data[i + 1].value;
            }


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
            var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "year",
                renderer: am5xy.AxisRendererX.new(root, {
                    cellStartLocation: 0.1,
                    cellEndLocation: 0.9,
                    minGridDistance: 30
                }),
                tooltip: am5.Tooltip.new(root, {})
            }));

            xAxis.data.setAll(data);

            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                min: 0,
                renderer: am5xy.AxisRendererY.new(root, {})
            }));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/

// Column series
            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                categoryXField: "year"
            }));

            series.columns.template.setAll({
                tooltipText: "{categoryX}: {valueY}",
                width: am5.percent(90),
                tooltipY: 0
            });

            series.data.setAll(data);

// Variance indicator series
            var series2 = chart.series.push(am5xy.ColumnSeries.new(root, {
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "valueNext",
                openValueYField: "value",
                categoryXField: "year",
                fill: am5.color(0x555555),
                stroke: am5.color(0x555555)
            }));

            series2.columns.template.setAll({
                width: 1
            });

            series2.data.setAll(data);

            series2.bullets.push(function () {
                var label = am5.Label.new(root, {
                    text: "{valueY}",
                    fontWeight: "500",
                    fill: am5.color(0x00cc00),
                    centerY: am5.p100,
                    centerX: am5.p50,
                    populateText: true
                });

                // Modify text of the bullet with percent
                label.adapters.add("text", function(text, target) {
                    var percent = getVariancePercent(target.dataItem);
                    return percent ? percent + "%" : text;
                });

                // Set dynamic color of the bullet
                label.adapters.add("centerY", function(center, target) {
                    return getVariancePercent(target.dataItem) < 0 ? 0 : center;
                });

                // Set dynamic color of the bullet
                label.adapters.add("fill", function(fill, target) {
                    return getVariancePercent(target.dataItem) < 0 ? am5.color(0xcc0000) : fill;
                });

                return am5.Bullet.new(root, {
                    locationY: 1,
                    sprite: label
                });
            });

            series2.bullets.push(function() {
                var arrow = am5.Graphics.new(root, {
                    rotation: -90,
                    centerX: am5.p50,
                    centerY: am5.p50,
                    dy: 3,
                    fill: am5.color(0x555555),
                    stroke: am5.color(0x555555),
                    draw: function (display) {
                        display.moveTo(0, -3);
                        display.lineTo(8, 0);
                        display.lineTo(0, 3);
                        display.lineTo(0, -3);
                    }
                });

                arrow.adapters.add("rotation", function(rotation, target) {
                    return getVariancePercent(target.dataItem) < 0 ? 90 : rotation;
                });

                arrow.adapters.add("dy", function(dy, target) {
                    return getVariancePercent(target.dataItem) < 0 ? -3 : dy;
                });

                return am5.Bullet.new(root, {
                    locationY: 1,
                    sprite: arrow
                })
            })


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
            series.appear();
            chart.appear(1000, 100);


            function getVariancePercent(dataItem) {
                if (dataItem) {
                    var value = dataItem.get("valueY");
                    var openValue = dataItem.get("openValueY");
                    var change = value - openValue;
                    return Math.round(change / openValue * 100);
                }
                return 0;
            }

        }); // end am5.ready()
    </script>

@endpush


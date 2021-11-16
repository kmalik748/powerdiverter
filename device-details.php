<?php
require 'app/app.php';
$id= mysqli_real_escape_string($con, trim($_GET["id"])) ;
$id = strip_tags($id);

$actual_link = "device-details.php?id=".$id;
$sql = "SELECT * FROM devices WHERE ID=$id";
$res = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($res);

$device_name = $row["device_name"];
$device_mac = $row["mac"];
$device_category = $row["category"];
$device_sys_id = $row["system_id"];
$device_state = $row["state"];
$device_lat = $row["lat"];
$device_long = $row["lng"];
$device_status = $row["power_status"];
$device_dutycycle = $row["dutycycle"];
$device_dutycycle_1 = $row["dutycycle_1"];
$device_radio_status = $row["radio_status"];
$device_relay_status = $row["relay_status"];
$device_communication_method = $row["communication_method"];
$device_last_pole = $row["last_pole"];
$device_last_post = $row["last_post"];
$pole_interval = $row["pole_time_interval"];
$post_interval = $row["post_time_interval"];
$schedule_dutycycle = $row["schedule_dutycycle"];
$schedule_start_time = $row["schedule_start_time"];
$schedule_end_time = $row["schedule_end_time"];

$page_title= $device_name;

// For Scheduled Duty Cycle
// ============= For TIme
$time = "2019-12-08";
date_default_timezone_set("Asia/Karachi");
//$date = date('m/d/Y h:i:s a', time());
$start_time = date("H:i:s", strtotime($schedule_start_time));
$end_time = date("H:i:s", strtotime($schedule_end_time));
$current_time = date('H:i:s', time());
//======================== Listing all
/*echo 'Start Time: '.$start_time.'<br>';
echo 'End Time: '.$end_time.'<br>';
echo 'Current Time: '.$current_time.'<br>';*/
if(($start_time < $current_time || $start_time == $current_time) &&
    ($end_time > $current_time || $end_time == $current_time)){
    //echo '<br> TIme Condition Matched!';
    $device_dutycycle_1 = $schedule_dutycycle;
}
?>
<!DOCTYPE html>
<html lang="en">

<?php require 'app/head.inc.php'; ?>
<script type="text/javascript" src="assets/js/submit_values.js?v=<?php echo rand(); ?>"></script>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require 'app/sidebar.inc.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require 'app/topbar.inc.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">


                        <!-- Device Info -->
                    <style>
                        @import url('https://fonts.googleapis.com/css2?family=Arimo&display=swap');
                    </style>
                    <div class="container-fluid bg-white card shadow mb-1">
                        <div class="row mb-2 no-gutters" style="font-family: 'Arimo', sans-serif;">
                            <div class="col-sm-12 col-md-6 col-xl-3">
                                <table>
                                    <tr>
                                        <td class="m-0 font-weight-bold text-primary">Name</td>
                                        <td class="pl-2"><?php echo $device_name; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="m-0 font-weight-bold text-primary">MAC </td>
                                        <td class="pl-2"><?php echo $device_mac ?></td>
                                    </tr>
                                    <tr>
                                        <td class="m-0 font-weight-bold text-primary">Category</td>
                                        <td class="pl-2"><?php echo $device_category ?></td>
                                    </tr>
                                    <tr>
                                        <td class="m-0 font-weight-bold text-primary">System ID</td>
                                        <td class="pl-2"><?php echo $device_sys_id ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-12 col-md-6 col-xl-3">
                                <table>
                                    <tr>
                                        <td class="m-0 font-weight-bold text-primary">State</td>
                                        <td class="pl-2"><?php echo $device_state ?></td>
                                    </tr>
                                    <tr>
                                        <td class="m-0 font-weight-bold text-primary">Duty Cycle</td>
                                        <td class="pl-2"><span id="duty_cycle_from_device">-</span></td>
                                    </tr>
                                    <tr>
                                        <td class="m-0 font-weight-bold text-primary">Data Sent</td>
                                        <td class="pl-2" id="device_pole">-</td>
                                    </tr>
                                    <tr>
                                        <td class="m-0 font-weight-bold text-primary">Data Received</td>
                                        <td class="pl-2" id="device_post">-</td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Circular Gauges -->
                            <div class="col-sm-12 col-md-6 col-xl-6">
                                <script type="text/javascript" src="assets/vendor/circle_gauges/gauge.js"></script>
                                <!-- Three Charts -->
                                <div class="row justify-content-center">
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        <div id="current_gauge" style="max-width: 300px;height: 100px;margin: 0px auto"></div>
                                        <p class="d-flex justify-content-center m-0">
                                            <span id="current_value">--</span>
                                            &nbsp;
                                            <span>A</span>
                                        </p>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        <div id="voltage_gauge" style="max-width: 300px;height: 100px;margin: 0px auto"></div>
                                        <p class="d-flex justify-content-center m-0">
                                            <span id="voltage_value">--</span>
                                            &nbsp;
                                            <span>V</span>
                                        </p>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                        <div id="power_gauge" style="max-width: 300px;height: 100px;margin: 0px auto"></div>
                                        <p class="d-flex justify-content-center m-0">
                                            <span id="power_value">--</span>
                                            &nbsp;
                                            <span>W</span>
                                        </p>
                                    </div>
                                </div>
                                <?php $circle_guage_size = 120; ?>
                                <script>
                                    var currentOption = {
                                        series: [0],
                                        chart: {
                                            height: <?php echo $circle_guage_size; ?>,
                                            type: 'radialBar'
                                        },
                                        plotOptions: {
                                            radialBar: {
                                                startAngle: -135,
                                                endAngle: 225,
                                                hollow: {
                                                    margin: 0,
                                                    size: '70%',
                                                    background: '#fff',
                                                    image: undefined,
                                                    imageOffsetX: 0,
                                                    imageOffsetY: 0,
                                                    position: 'front',
                                                    dropShadow: {
                                                        enabled: true,
                                                        top: 3,
                                                        left: 0,
                                                        blur: 4,
                                                        opacity: 0.24
                                                    }
                                                },
                                                track: {
                                                    background: '#fff',
                                                    strokeWidth: '67%',
                                                    margin: 0, // margin is in pixels
                                                    dropShadow: {
                                                        enabled: true,
                                                        top: -3,
                                                        left: 0,
                                                        blur: 4,
                                                        opacity: 0.35
                                                    }
                                                },
                                                dataLabels: {
                                                    show: true,
                                                    name: {
                                                        offsetY: 6,
                                                        show: true,
                                                        color: '#888',
                                                        fontSize: '15px'
                                                    },
                                                    value: {
                                                        formatter: function(val) {
                                                            return parseInt(val);
                                                        },
                                                        // offsetY: 3,
                                                        // color: '#111',
                                                        // fontSize: '25px',
                                                        show: false,
                                                    }
                                                }
                                            }
                                        },
                                        fill: {
                                            type: 'gradient',
                                            gradient: {
                                                shade: 'dark',
                                                type: 'horizontal',
                                                shadeIntensity: 0.5,
                                                gradientToColors: ['#ff0000', '#ABE5A1'],
                                                opacityFrom: 1,
                                                opacityTo: 1,
                                                stops: [1, 100]
                                            }
                                        },
                                        stroke: {
                                            lineCap: 'round'
                                        },
                                        labels: ['Current'],
                                    };

                                    var voltageOption = {
                                        series: [0],
                                        chart: {
                                            height: <?php echo $circle_guage_size; ?>,
                                            type: 'radialBar'
                                        },
                                        plotOptions: {
                                            radialBar: {
                                                startAngle: -135,
                                                endAngle: 225,
                                                hollow: {
                                                    margin: 0,
                                                    size: '70%',
                                                    background: '#fff',
                                                    image: undefined,
                                                    imageOffsetX: 0,
                                                    imageOffsetY: 0,
                                                    position: 'front',
                                                    dropShadow: {
                                                        enabled: true,
                                                        top: 3,
                                                        left: 0,
                                                        blur: 4,
                                                        opacity: 0.24
                                                    }
                                                },
                                                track: {
                                                    background: '#fff',
                                                    strokeWidth: '67%',
                                                    margin: 0, // margin is in pixels
                                                    dropShadow: {
                                                        enabled: true,
                                                        top: -3,
                                                        left: 0,
                                                        blur: 4,
                                                        opacity: 0.35
                                                    }
                                                },
                                                dataLabels: {
                                                    show: true,
                                                    name: {
                                                        offsetY: 6,
                                                        show: true,
                                                        color: '#888',
                                                        fontSize: '15px'
                                                    },
                                                    value: {
                                                        formatter: function(val) {
                                                            return parseInt(val);
                                                        },
                                                        // offsetY: 3,
                                                        // color: '#111',
                                                        // fontSize: '25px',
                                                        show: false,
                                                    }
                                                }
                                            }
                                        },
                                        fill: {
                                            type: 'gradient',
                                            gradient: {
                                                shade: 'dark',
                                                type: 'horizontal',
                                                shadeIntensity: 0.5,
                                                gradientToColors: ['#ff0000', '#ABE5A1'],
                                                opacityFrom: 1,
                                                opacityTo: 1,
                                                stops: [1, 100]
                                            }
                                        },
                                        stroke: {
                                            lineCap: 'round'
                                        },
                                        labels: ['Voltage'],
                                    };

                                    var powerOption = {
                                        series: [0],
                                        chart: {
                                            height: <?php echo $circle_guage_size; ?>,
                                            type: 'radialBar'
                                        },
                                        plotOptions: {
                                            radialBar: {
                                                startAngle: -135,
                                                endAngle: 225,
                                                hollow: {
                                                    margin: 0,
                                                    size: '70%',
                                                    background: '#fff',
                                                    image: undefined,
                                                    imageOffsetX: 0,
                                                    imageOffsetY: 0,
                                                    position: 'front',
                                                    dropShadow: {
                                                        enabled: true,
                                                        top: 3,
                                                        left: 0,
                                                        blur: 4,
                                                        opacity: 0.24
                                                    }
                                                },
                                                track: {
                                                    background: '#fff',
                                                    strokeWidth: '67%',
                                                    margin: 0, // margin is in pixels
                                                    dropShadow: {
                                                        enabled: true,
                                                        top: -3,
                                                        left: 0,
                                                        blur: 4,
                                                        opacity: 0.35
                                                    }
                                                },
                                                dataLabels: {
                                                    show: true,
                                                    name: {
                                                        offsetY: 6,
                                                        show: true,
                                                        color: '#888',
                                                        fontSize: '15px'
                                                    },
                                                    value: {
                                                        formatter: function(val) {
                                                            return parseInt(val);
                                                        },
                                                        // offsetY: 3,
                                                        // color: '#111',
                                                        // fontSize: '25px',
                                                        show: false,
                                                    }
                                                }
                                            }
                                        },
                                        fill: {
                                            type: 'gradient',
                                            gradient: {
                                                shade: 'dark',
                                                type: 'horizontal',
                                                shadeIntensity: 0.5,
                                                gradientToColors: ['#ff0000', '#ABE5A1'],
                                                opacityFrom: 1,
                                                opacityTo: 1,
                                                stops: [1, 100]
                                            }
                                        },
                                        stroke: {
                                            lineCap: 'round'
                                        },
                                        labels: ['Power'],
                                    };


                                    var currentChart = new ApexCharts(document.querySelector("#current_gauge"), currentOption);
                                    var voltageChart = new ApexCharts(document.querySelector("#voltage_gauge"), voltageOption);
                                    var powerChart = new ApexCharts(document.querySelector("#power_gauge"), powerOption);


                                    currentChart.render();
                                    voltageChart.render();
                                    powerChart.render();

                                    update();

                                    function update() {
                                        console.log("update here");
                                        // var current_val = 4;
                                        // var voltage_val = 330;
                                        // var power_val = 1804;

                                        INTERVAL_ID = setInterval(function() {
                                            $.getJSON("ajax/getDetails.php",{mac: "<?php echo $device_mac; ?>", device_id: "<?php echo $id; ?>"}, function(data) {
                                                // console.log(data);
                                                $("#device_pole").val("asdf");
                                                $("#device_post").val(data[1]);
                                                document.getElementById("device_post").innerText = data[0];
                                                document.getElementById("duty_cycle_from_device").innerText = data[5];
                                                // update_current_gauge(100, parseInt(data[2]));
                                                // update_voltage_gauge(100, parseInt(data[3]));
                                                // update_power_gauge(100, parseInt(data[4]));

                                                currentChart.updateSeries(updateCurrentData(parseInt(data[2])));
                                                voltageChart.updateSeries(updateVoltageData(parseInt(data[3])));
                                                powerChart.updateSeries(updatePowerData(parseInt(data[4])));
                                            });





                                        }, 2500);
                                    }

                                    function updateCurrentData(newData) {
                                        // newVal = parseInt(newVal)
                                        console.log('Testing Val: ' + newData);
                                        var data = [];
                                        var arr = currentChart.w.globals.series.slice()
                                        document.getElementById("current_value").innerText = newData
                                        console.log('original Val: ' + newData)
                                        // console.log('Testing Val: ' + newData)
                                        arr.push(newData)
                                        data.push(arr[arr.length - 1]);
                                        console.log('Data: ' , data)
                                        console.log("===============\n")
                                        return data;
                                    }

                                    function updateVoltageData(newData) {
                                        // newVal = parseInt(newVal)
                                        console.log('Testing Val: ' + newData);
                                        var data = [];
                                        var arr = voltageChart.w.globals.series.slice()
                                        document.getElementById("voltage_value").innerText = newData
                                        console.log('original Val: ' + newData)
                                        // console.log('Testing Val: ' + newData)
                                        newData = (newData * 100) / 400
                                        arr.push(newData)
                                        data.push(arr[arr.length - 1]);
                                        console.log('calculated: ' + newData)
                                        console.log('Data: ' , data)
                                        console.log("===============\n")
                                        return data;
                                    }

                                    function updatePowerData(newData) {
                                        // newVal = parseInt(newVal)
                                        console.log('Testing Val: ' + newData);
                                        var data = [];
                                        var arr = powerChart.w.globals.series.slice()
                                        document.getElementById("power_value").innerText = newData
                                        console.log('original Val: ' + newData)
                                        // console.log('Testing Val: ' + newData)
                                        newData = (newData * 100) / 3400
                                        arr.push(newData)
                                        data.push(arr[arr.length - 1]);
                                        console.log('calculated: ' + newData)
                                        console.log('Data: ' , data)
                                        console.log("===============\n")
                                        return data;
                                    }

                                </script>
                            </div>
                        </div>
                    </div>

                        <!-- Device Settings -->
                        <div class="row">
                            <!--Left Main Row -->
                            <div class="col-lg-8">
                                <div class="row">
                                    <!-- Connectivity Method -->
                                    <div class="col-md-8">
                                        <div class="card shadow mb-1">
                                            <!-- Card Header - Accordion -->
                                            <a href="#collapseConnectivityTechnology" class="d-block card-header py-3" data-toggle="collapse"
                                               role="button" aria-expanded="true" aria-controls="collapseCardExample">
                                                <h6 class="m-0 font-weight-bold text-primary">Wireless Connection</h6>
                                            </a>
                                            <!-- Card Content - Collapse -->
                                            <div class="collapse show" id="collapseConnectivityTechnology">
                                                <div class="card-body" id="communication_block">
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                            <a href="#" onclick="wireless_connection(<?php echo $id.", 'WiFi'"; ?>)" class="text-white text-decoration-none">
                                                                <div class="card bg-danger text-white <?php if($device_communication_method=="WiFi") echo 'selected_danger'; ?>">
                                                                    <div class="card-body p-2">
                                                                        WiFi
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
                                                            <a href="#" onclick="wireless_connection(<?php echo $id.", '3G'"; ?>)" class="text-white text-decoration-none">
                                                                <div class="card bg-secondary text-white <?php if($device_communication_method=="3G") echo 'selected_secondary'; ?>">
                                                                    <div class="card-body p-2">
                                                                        3G
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 mt-sm-2 mt-md-0 mt-lg-0 mt-xl-0">
                                                            <a href="#" onclick="wireless_connection(<?php echo $id.", '4G'"; ?>)" class="text-white text-decoration-none">
                                                                <div class="card bg-success text-white  <?php if($device_communication_method=="4G") echo 'selected_success'; ?>">
                                                                    <div class="card-body p-2">
                                                                        4G
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 mt-sm-2 mt-md-0 mt-lg-0 mt-xl-0">
                                                            <a href="#" onclick="wireless_connection(<?php echo $id.", 'LORA'"; ?>)" class="text-white text-decoration-none">
                                                                <div class="card bg-primary text-white  <?php if($device_communication_method=="LORA") echo 'selected_primary'; ?>">
                                                                    <div class="card-body p-2">
                                                                        LORA
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Relay Status -->
                                    <div class="col-md-4 px-0 w-100 ml-xl-9px">
                                            <div class="card shadow mb-1">
                                                <!-- Card Header - Accordion -->
                                                <a href="#collapseRelayOperation" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                                                    <h6 class="m-0 font-weight-bold text-primary">Relay Operation</h6>
                                                </a>
                                                <div class="collapse show" id="collapseRelayOperation" style="">
                                                    <div class="card-body" id="relay_operation_block">
                                                        <div class="row">
                                                            <div class="col-sm-6 col-md-12 col-lg-6 cardbody-auto-width">
                                                                <a href="#" onclick="relay_operation(<?php echo $id.', \'OFF\''; ?>)" class="text-white text-decoration-none">
                                                                    <div class="card bg-success text-white  <?php if($device_relay_status=="OFF") echo 'selected_success'; ?>">
                                                                        <div class="card-body p-2">
                                                                            Off Peak
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <div class="col-sm-6 col-md-12 col-lg-6 cardbody-auto-width">
                                                                <a href="#" onclick="relay_operation(<?php echo $id.', \'ON\''; ?>)" class="text-white text-decoration-none">
                                                                    <div class="card bg-info text-white <?php if($device_relay_status=="ON") echo 'selected_info'; ?>">
                                                                        <div class="card-body p-2">
                                                                            Main
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <!--Graphs -->
                                    <div class="d-none d-lg-block d-xl-block col-md-12 ">
                                        <script src="assets/vendor/canvas-js/canvasjs.min.js"></script>
                                        <div id="currentChart" style="height: 100%;"></div>
                                        <div id="voltageChart" style="height: 100%; margin-top: 120px;"></div>
                                        <?php
                                        $mac = $device_mac;
                                        ?>
                                        <script>
                                            window.onload = function () {
                                                var temp = new CanvasJS.Chart("currentChart", {
                                                        height: 230,
                                                        zoomEnabled: true,
                                                        animationEnabled: true,
                                                        title: {
                                                            text: "Current"
                                                        },
                                                        fontWeight: "lighter",
                                                        fontWeight: "Normal",
                                                        axisY: {
                                                            includeZero: false,
                                                            lineThickness: 1,
                                                            gridColor: "#ffffff1f"
                                                        },
                                                        axisX: {
                                                            labelMaxWidth: 100,
                                                            labelAngle: -90 / 90
                                                        },
                                                        data: [
                                                            {
                                                                name: "Current",
                                                                type: "spline",
                                                                dataPoints: []
                                                            }
                                                        ]
                                                    }
                                                );
                                                var temp1 = new CanvasJS.Chart("voltageChart", {
                                                        height: 242,
                                                        zoomEnabled: true,
                                                        animationEnabled: true,
                                                        title: {
                                                            text: "Power"
                                                        },
                                                        fontWeight: "lighter",
                                                        fontWeight: "Normal",
                                                        // legend: {
                                                        //     cursor: "pointer",
                                                        //     fontSize: 16,
                                                        //     // itemclick: toggleDataSeries
                                                        // },
                                                        axisY: {
                                                            includeZero: false,
                                                            lineThickness: 1,
                                                            gridColor: "#ffffff1f"
                                                        },
                                                        axisX: {
                                                            labelMaxWidth: 100,
                                                            labelAngle: -90 / 90
                                                        },
                                                        data: [
                                                            {
                                                                lineColor: "#e74a3b",
                                                                name: "Voltage",
                                                                type: "spline",
                                                                dataPoints: []
                                                            }
                                                        ]
                                                    }
                                                );
                                                function valve_update() {
                                                    $.getJSON("ajax/getDemoData.php",{mac: "<?php echo $device_mac; ?>", type: "power"}, function(data) {
                                                        temp.options.data[0].dataPoints = [];
                                                        temp1.options.data[0].dataPoints = [];
                                                        $.each((data), function(key, value){
                                                            temp.options.data[0].dataPoints.push({label: value[0], y: parseInt(value[1])});
                                                            temp1.options.data[0].dataPoints.push({
                                                                label: value[0],
                                                                y: parseInt(value[3]),
                                                                color: "#ff5d4e"
                                                            });
                                                        });
                                                    });
                                                    temp.render();
                                                    temp1.render();
                                                }
                                                setInterval(function(){
                                                    valve_update();
                                                }, 2000);
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>

                            <!--Right Main Row -->
                            <div class="col-lg-4 pl-0 w-100 ml-xl-4px">
                                <div class="row">
                                    <div class="col-md-12 w-100 p-0">
                                        <!-- Device Settings -->
                                        <div class="col-md-12">
                                            <div class="card shadow mb-4">
                                                <!-- Card Header - Accordion -->
                                                <a href="#collapseDeviceStatus" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                                                    <h6 class="m-0 font-weight-bold text-primary">Device Settings</h6>
                                                </a>
                                                <?php
                                                if ($device_status){
                                                    $device_online_status = "ONLINE";
                                                    $update_value = 0;
                                                    $device_online_status_btn_class = "danger";
                                                    $device_online_status_text = "Turn Off";
                                                    $device_online_status_text_class = "success";
                                                    $device_online_status_link = $actual_link.'&switch_status=PowerOff';
                                                }
                                                else{
                                                    $device_online_status = "OFFLINE";
                                                    $update_value = 1;
                                                    $device_online_status_btn_class = "success";
                                                    $device_online_status_text = "Turn On";
                                                    $device_online_status_text_class = "danger";
                                                    $device_online_status_link = $actual_link.'&switch_status=PowerOn';
                                                }
                                                ?>
                                                <div class="collapse show" id="collapseDeviceStatus" style="">
                                                    <div class="card-body">
                                                        <!-- Power Status -->
                                                        <div class="row" style="margin-top: 10px !important;padding-bottom: 18px;">
                                                            <div class="col-md-12 clearfix clearfix-custom-margin" id="device_status_value">
                                                                <p class="font-weight-bold float-left">
                                                                    <span class="m-0 font-weight-bold text-primary h5"> Status: </span>
                                                                    <span class="text-<?php echo $device_online_status_text_class; ?>"><?php echo $device_online_status; ?></span>
                                                                </p>
                                                                <a onclick="turn_device_on_off(<?php echo $id; ?>, <?php echo $update_value; ?>)"
                                                                   class="btn btn-<?php echo $device_online_status_btn_class; ?> btn-icon-split float-right">
                                                                        <span class="icon text-white-50">
                                                                            <i class="fas fa-power-off"></i>
                                                                        </span>
                                                                    <span class="text"><?php echo $device_online_status_text; ?></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <!-- Set Pole/Post Time -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <form class="form-horizontal" action="" method="post">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="sel1">Pole Time:</label>
                                                                                <select class="form-control" id="pole_time" name="pole_time" required>
                                                                                    <option value="30000" <?php if($pole_interval=="30000") echo "Selected"; ?>>30sec<?php if($pole_interval=="30000") echo "(Selected)" ?></option>
                                                                                    <option value="60000" <?php if($pole_interval=="60000") echo "Selected"; ?>>1min<?php if($pole_interval=="60000") echo "(Selected)" ?></option>
                                                                                    <option value="90000" <?php if($pole_interval=="90000") echo "Selected"; ?>>1.5min<?php if($pole_interval=="90000") echo "(Selected)" ?></option>
                                                                                    <option value="180000" <?php if($pole_interval=="180000") echo "Selected"; ?>>3min<?php if($pole_interval=="180000") echo "(Selected)" ?></option>
                                                                                    <option value="300000" <?php if($pole_interval=="300000") echo "Selected"; ?>>5min<?php if($pole_interval=="300000") echo "(Selected)" ?></option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="sel1">Post Time:</label>
                                                                                <select class="form-control" id="post_time" name="post_time" required>
                                                                                    <option value="30000" <?php if($post_interval=="30000") echo "Selected"; ?>>30sec<?php if($post_interval=="30000") echo "(Selected)" ?></option>
                                                                                    <option value="60000" <?php if($post_interval=="60000") echo "Selected"; ?>>1min<?php if($post_interval=="60000") echo "(Selected)" ?></option>
                                                                                    <option value="90000" <?php if($post_interval=="90000") echo "Selected"; ?>>1.5min<?php if($post_interval=="90000") echo "(Selected)" ?></option>
                                                                                    <option value="180000" <?php if($post_interval=="180000") echo "Selected"; ?>>3min<?php if($post_interval=="180000") echo "(Selected)" ?></option>
                                                                                    <option value="300000" <?php if($post_interval=="300000") echo "Selected"; ?>>5min<?php if($post_interval=="300000") echo "(Selected)" ?></option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group">
                                                                        <input type="button" name="pole_post_time" onclick="set_poll_post_time(<?php echo $id; ?>)"
                                                                                class="btn btn-primary w-100" id="update_poll_post" value="Set Pole/Post Time">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <!-- Set Duty Cycle -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <form class="form-horizontal" action="" method="post">
                                                                    <div class="row">
                                                                        <div class="col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="w-100">Start</label>
                                                                                <input type="time" name="start_time">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="w-100">End</label>
                                                                                <input type="time" name="end_time">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 col-lg-4">
                                                                            <div class="form-group">
                                                                                <label class="w-100">Duty Cycle:</label>
                                                                                <input type="number" name="duty_value"  max="100" min="0" style="width: 100px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <button type="submit" class="btn btn-primary w-100" name="set-schedule">
                                                                                    Add Schedule
                                                                                </button>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <button type="button" class="btn btn-success w-100"
                                                                                        name="set-schedule" data-toggle="modal" data-target="#listSchedules">
                                                                                    List All
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                <?php
                                                                if(isset($_POST["set-schedule"])){
                                                                    $var = $_POST["start_time"];
                                                                    $var1 = $_POST["end_time"];
                                                                    $var2 = $_POST["duty_value"];
                                                                    $sql = "UPDATE devices SET schedule_dutycycle=$var2, schedule_start_time='$var', schedule_end_time='$var1' WHERE ID=$id";
                                                                    if(mysqli_query($con, $sql)){
                                                                        echo '<script>window.location = "'.$actual_link.'";</script>';
                                                                    }

                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <!-- Set DutyCycle -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <form method="post" action="">
                                                                    <div class="form-group">
                                                                        <label for="formControlRange">
                                                                            Duty Cycle: <span id="dutyCycleValue"><?php echo $device_dutycycle_1; ?></span>%
                                                                        </label>
                                                                        <input onchange="showVal(this.value)" type="range"
                                                                               value="<?php echo $device_dutycycle_1; ?>"
                                                                               class="form-control-range" id="formControlRange"
                                                                               name="range_val">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <input type="button" class="btn btn-primary w-100"
                                                                                name="dc_update" id="update_dc_btn"
                                                                                onclick="update_dutycycle(<?php echo $id ?>, document.getElementsByName('range_val')[0].value)"
                                                                                value="Set">
                                                                    </div>

                                                                    <script>
                                                                        function showVal(newVal){
                                                                            document.getElementById("dutyCycleValue").innerHTML=newVal;
                                                                        }
                                                                        // document.getElementById("dutyCycleValue").innerHTML='50';
                                                                    </script>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div> <!-- End of card body -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

            </div><!-- /.container-fluid -->

            <!-- Footer -->
            <?php require 'app/footer.inc.php'; ?>


            <!-- Modal of List all Schedules -->
            <div class="modal fade" id="listSchedules">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title h3 font-weight-bold text-primary">
                                Duty Cycle Schedules
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <?php
                            if($schedule_start_time != "00:00:00.000000" && $schedule_end_time != "00:00:00.000000"){
                                ?>
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Duty Cycle</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <!--                                  <td>--><?php // echo $schedule_start_time; ?><!--</td>-->
                                        <td><?php  echo date('h:i:s a', strtotime($schedule_start_time));; ?></td>
                                        <td><?php  echo date('h:i:s a', strtotime($schedule_end_time));; ?></td>
                                        <!--                                  <td>--><?php // echo $schedule_end_time; ?><!--</td>-->
                                        <td><?php  echo $schedule_dutycycle; ?></td>
                                        <td>
                                            <a href="<?php echo $actual_link; ?>&delete_schedule=1" class="btn btn-danger btn-circle btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <?php
                            } else{
                                echo '
                                <div class="displap-4">
                                    No Active Schedules!
                                </div>
                                ';
                            }
                            if(isset($_GET["delete_schedule"])){
                                $default_time = "00:00:00.000000";
                                $sql = "UPDATE devices SET 
                                        	schedule_start_time='$default_time', schedule_end_time='$default_time',
                                        	 schedule_dutycycle=0 WHERE ID=$id";
                                if(mysqli_query($con, $sql)){
//                                  echo '<script>alert("done");</script>';
                                    redirect($actual_link);
                                }
                            }
                            ?>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>
</body>

</html>

<script>


    //setInterval(function(){
    //    $.getJSON("ajax/getDetails.php",{mac: "<?php //echo $device_mac; ?>//", device_id: "<?php //echo $id; ?>//"}, function(data) {
    //        console.log(data);
    //        $("#device_pole").val(data[0]);
    //        $("#device_post").val(data[1]);
    //        update_current_gauge(100, data[2]);
    //        update_voltage_gauge(100, data[3]);
    //        update_power_gauge(100, data[4]);
    //    });
    //}, 3000);
</script>
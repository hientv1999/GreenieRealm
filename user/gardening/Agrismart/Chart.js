$(document).ready(function(){
    var tableName = $("#tableName").attr('value');
    $.ajax({
        url: "gardening/Agrismart/fetch_data.php",
        method: "GET",
        data: "tableName=" + tableName,
        success: function(data) {
            var Temperature = [];
            var Humidity = [];
            var WaterLevel = [];
            var BatteryLevel = [];
            var ChargingCurrent = [];
            var Time = [];
            var chartType = data[0].chartType;
            var chartLength = parseInt(data[0].chartLength);
            if (data.length-1 < chartLength){
                document.getElementById('no-data-temperature').style.display = 'block';
                document.getElementById('no-data-humidity').style.display = 'block';
                document.getElementById('OutdoorratureLine').style.display = 'none';
                document.getElementById('OutdoorHumidityLineChart').style.display = 'none';
            }
            for(let i=1; i<data.length; i++) {
                Temperature.unshift(data[i].Temperature);
                Humidity.unshift(data[i].Humidity);
                WaterLevel.unshift(data[i].WaterLevel);
                BatteryLevel.unshift(data[i].BatteryLevel)
                ChargingCurrent.unshift(data[i].ChargingCurrent)
                var TimeVar = new Date(data[i].Time.replace(/-/g, "/"));
                var offset = new Date().getTimezoneOffset();
                TimeVar.setMinutes(TimeVar.getMinutes() - offset);
                // need to make timezone fed from database PHP
                Time.unshift(TimeVar);
            }
            // draw chart
            // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#292b2c';

            // Temperature Line Chart
            
            var TemperatureData = {
                label: "Temperature (°C)",
                lineTension: 0.3,
                backgroundColor: "rgba(245, 66, 66, 0.1)",
                borderColor: "rgba(245, 66, 66 ,1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(245, 66, 66 ,1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(2,117,216,1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: Temperature
            };
            var HumidityData = {
                label: "Humidity (%)",
                lineTension: 0.3,
                backgroundColor: "rgba(2,117,216,0.1)",
                borderColor: "rgba(2,117,216,1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(2,117,216,1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(2,117,216,1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: Humidity
            };
            // Humidity Line Chart
            var OTLC = document.getElementById("OutdoorTemperatureLineChart");
            var OutdoorTemperatureLineChart = new Chart(OTLC, {
            type: chartType,
            data: {
                labels: Time,
                datasets: [TemperatureData],
            },
            options: {
                
                noData: {
                    text: 'No data to display',
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            distribution: 'linear',
                        },
                        gridLines: {        // x-axis grid
                        display: false
                        },
                        ticks: {            // number of divisions
                            maxTicksLimit: data.length,
                            source: 'data',
                        }
                    }],
                yAxes: [{
                    ticks: {
                        source: 'auto',   
                        precision: 2,
                    },
                    gridLines: {
                    color: "rgba(0, 0, 0, .125)",
                    }
                }],
                },
                legend: {
                display: false
                },
            }
            });
            var OHLC = document.getElementById("OutdoorHumidityLineChart");
            var OutdoorHumidityLineChart = new Chart(OHLC, {
                type: chartType,
                data: {
                    labels: Time,
                    datasets: [HumidityData],
                },
                options: {
                    noData: {
                        text: 'No data to display',
                    },
                    scales: {
                        xAxes: [{
                            type: 'time',
                            time: {
                                distribution: 'linear',
                            },
                            gridLines: {        // x-axis grid
                            display: false
                            },
                            ticks: {            // number of divisions
                                maxTicksLimit: data.length,
                                source: 'data',
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                source: 'auto',   
                                precision: 2,
                            },
                            gridLines: {
                            color: "rgba(0, 0, 0, .125)",
                            }
                        }],
                    },
                    legend: {
                    display: false
                    },
                }
                });
        },
        error: function(data) {
            console.log(data);
        }
    });
});






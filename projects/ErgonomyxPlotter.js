$(document).ready(function(){       
    $("#Month, #Quarter, #Year").on("click", function(e){   
        e.preventDefault();    
        document.getElementById("description").style.display = "none";
        document.getElementById("LineChart").style.display = "none";
        document.getElementById("loading").style.display = "inline";
        range = $(this).attr('id');
        $.ajax({
            url: "getErgonomyxdata.php",
            method: "GET",
            data: "range=" + range,
            success: function(data) {
                document.getElementById("loading").style.display = "none";
                document.getElementById("LineChart").style.display = "inline";
                var desk = [];
                var bike = [];
                var timestamp = [];
                for(let i=0; i<data.length; i++) {
                    desk.unshift(data[i].desk);
                    bike.unshift(data[i].bike);
                    var TimeVar = new Date(data[i].timestamp.replace(/-/g, "/"));
                    // need to make timezone fed from database PHP
                    timestamp.unshift(TimeVar);
                }
                // draw chart
                // Set new default font family and font color to mimic Bootstrap's default styling
                Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                Chart.defaults.global.defaultFontColor = '#292b2c';

                // Desk Line Chart

                var deskData = {
                    label: "Smart Desk",
                    lineTension: 0.3,
                    fill: false,
                    // backgroundColor: "rgba(245, 66, 66, 0.1)",
                    borderColor: "rgba(245, 66, 66 ,1)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(245, 66, 66 ,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(245, 66, 66 ,0.5)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: desk
                };

                var bikeData = {
                    label: "Smart Bike",
                    lineTension: 0.3,
                    fill: false,
                    // backgroundColor: "rgba(245, 66, 66, 0.1)",
                    borderColor: "rgba(40, 43, 242 ,1)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(40, 43, 242 ,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(40, 43, 242, 0.5)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: bike
                };

                // Humidity Line Chart
                var LC = document.getElementById("LineChart");
                var LineChart = new Chart(LC, {
                    type: "line",
                    data: {
                        labels: timestamp,
                        datasets: [deskData, bikeData],
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Ergonomyx\'s sale of Smart Desk and Smart Bike this ' + range.toLowerCase() ,
                            fontSize: 16,
                        },
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
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Week reported'
                                }
                            }],
                        yAxes: [{
                            ticks: {
                                source: 'auto',   
                                precision: 2,
                            },
                            gridLines: {
                                color: "rgba(0, 0, 0, .125)",
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Amount being sold'
                            }
                        }],
                        },
                        legend: {
                            display: true
                        },
                    }
                });
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
});














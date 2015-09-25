$(document).ready(function() {
   
    $('#toTime').datepicker({
        format: 'yyyy-mm-dd',
    });
    $('#fromTime').datepicker({
        format: 'yyyy-mm-dd',
    });
    
    var chartType = $('#chartType').val();
   
    // stats by date
    var statsByDate = jQuery.parseJSON($('#statsByDateData').val());
    var statsByDateKeys = [];
    var statsByDateData = [];
   
   
    for (key in statsByDate) {
        statsByDateKeys.push(key);
        statsByDateData.push(parseInt(statsByDate[key].totalUsers, 10));
    }
   
   $('#statsByTotalUsersChart').highcharts({
        chart: {
            type: chartType
        },
        plotOptions: {
            column: {
                shadow: true
            },
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        title: {
            text: 'Total Users'
        },
        xAxis: {
            categories: statsByDateKeys
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Users'
            }
        },
        series: [{
            name: "Users",
            showInLegend: false,
            data: statsByDateData,
            dataLabels: {
                enabled: true,
            }
        }]
    }); 
    
    var statsByStorage = jQuery.parseJSON($('#statsByDateData').val());
    var statsByStorageKeys = [];
    var statsByStorageData = [];
    
    for (key in statsByStorage) {
        statsByStorageKeys.push(key);
        statsByStorageData.push(parseInt(statsByStorage[key].totalStorage, 10));
    }
    
    $('#statsByTotalStorageChart').highcharts({
        chart: {
            type: chartType
        },
        plotOptions: {
            column: {
                shadow: true
            },
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        title: {
            text: 'Total Storage (GB)'
        },
        xAxis: {
            categories: statsByStorageKeys
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Storage (GB)'
            }
        },
        tooltip: {
            valueSuffix: ' GB'
        },
        series: [{
            name: "Storage (GB)",
            showInLegend: false,
            data: statsByStorageData,
            dataLabels: {
                enabled: true,
            }
        }]
    }); 
    
    
});
// Load the Visualization API and the corechart package.

// google.charts.load('current', { 'packages': ['corechart'] });

// Set a callback to run when the Google Visualization API is loaded.
// google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
// function drawChart() {
//     // Create the data table.
//     var data = new google.visualization.DataTable();
//     data.addColumn('string', 'Topping');
//     data.addColumn('number', 'Slices');
//     data.addRows([
//         ['正常', 3],
//         ['アラーム', 1],
//         ['オフライン', 1],
//         ['未使用', 1]
//     ]);

//     // Set chart options
//     var options = {
//         legend: 'none',
//         pieStartAngle: 100,
//         is3D: true,
//         chartArea: {
//             left: '10%',
//             top: 0,
//             width: '100%',
//             height: 186
//         }
//     };
//     var options1 = {
//         legend: 'none',
//         pieStartAngle: 100,
//         is3D: true,
//         chartArea: {
//             left: '10%',
//             top: 0,
//             width: '80%',
//             height: 186
//         }
//     };

//     // Instantiate and draw our chart, passing in some options.
//     var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
//     chart.draw(data, options);
//     var chart = new google.visualization.PieChart(document.getElementById('chart_div1'));
//     chart.draw(data, options1);
// }


// reference guide
// https://canvasjs.com/docs/charts/chart-types/html5-pie-chart/

// CanvasJS settings
window.onload = function () {
    CanvasJS.addColorSet("greenShades",
    [//colorSet Array
        "#33B800",
        "#D40000",
        "#707070",
        "#3F3F3F",
        "#FFFFFF"
    ]);
    var options = {
        showInLegend: true,
        legendText: "{indexLabel}",
        colorSet: "greenShades",
        animationEnabled: true,
        legend: false,
        // legend: {
        //     horizontalAlign: "right",
        //     verticalAlign: "center"
        // },
        data: [{
            type: "pie",
            showInLegend: false,
            toolTipContent: "<b>{name}</b>: {y} (#percent%)",
            // indexLabel: "{name}",
            legendText: "{name} (#percent%)",
            indexLabelPlacement: "inside",
            dataPoints: [
                { y: 100, name: "正常" },
                { y: 80, name: "アラーム" },
                { y: 70, name: "オフライン" },
                { y: 60, name: "未使用" }
            ]
        }]
    };
    $("#left-chartContainer").CanvasJSChart(options);
    $("#right-chartContainer").CanvasJSChart(options);

}
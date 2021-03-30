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
    var rightdata=[];
    $.ajax({
        url:"home/getdata",
         type:'get',
         
         success:function(res){
             res=JSON.parse(res);
            for(var i = 0; i < res.length; i++) {
                y=parseFloat(res[i].y);
                rightdata.push({ y: y, name:res[i].name });
            
            }
            console.log(rightdata);
            $("#right-chartContainer").CanvasJSChart(rightoptions);
         }
         
    });
    var leftdata=[];
    var color=[];
    var working= parseFloat($('#working').text());
    if(working!==0){
        leftdata.push({ y: working, name:"正常" });
        color.push("#33B800");
    }
    var warning1=parseFloat($('#warning1').text());
    if(warning1!==0){
        leftdata.push({ y: warning1, name:"警告1" });
        color.push("#C4DB00");
    }
    var warning2=parseFloat($('#warning2').text());
    if(warning2!==0){
        leftdata.push({ y: warning2, name:"警告2" });
        color.push("red");
    }
    var notWorking=parseFloat($('#notWorking').text());
    if(notWorking!==0){
        leftdata.push({ y: notWorking, name:"オフライン" });
        color.push("#3F3F3F");
    }
    console.log(color);
    CanvasJS.addColorSet("greenShades",color);
    CanvasJS.addColorSet("sensorType",
    [//colorSet Array
        '#ff0000', "#33B800", '#0000ff', 
			'#ff3333',  "#3F3F3F", '#ff6600' 
    ]);
    var leftoptions = {
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
            percentFormatString: "#0.##",
            indexLabel: "#percent%",
            toolTipContent: "<b>{name}</b>: {y} (#percent%)",
            legendText: "{name} (#percent%)",
            indexLabelPlacement: "inside",
            dataPoints: leftdata
        }]
    };
    var rightoptions = {
        showInLegend: true,
        legendText: "{indexLabel}",
        colorSet: "sensorType",
        animationEnabled: true,
        legend: false,
        // legend: {
        //     horizontalAlign: "right",
        //     verticalAlign: "center"
        // },
        data: [{
            type: "pie",
            showInLegend: false,
            percentFormatString: "#0.##",
            indexLabel: "#percent%",
            toolTipContent: "<b>{name}</b>: {y} (#percent%)",
            // indexLabel: "{name}",
            legendText: "{name} (#percent%)",
            indexLabelPlacement: "inside",
            dataPoints: rightdata
        }]
    };
    $("#left-chartContainer").CanvasJSChart(leftoptions);
    

}



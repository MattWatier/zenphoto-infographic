function drawBarChart(chartID, dataSet, selectString){
      // chartID => A unique drawing identifier that has no spaces, no "." and no "#" characters.
      // dataSet => Input Data for the chart, itself.
      // selectString => String that allows you to pass in
      // a D3.selectAll() string.

      function domainArray(a , type) {
        var data =[];// ["Photos","Canvas","Page","Screen"];
        for (var i = 0; i < a.length; i++) {
          data.push( a[i][type] );
         
        }
        console.log(data);
        return unique(data);
      }


  var  barChart ={ w: 442, h :300 ,m:20  };
  barChart.height =barChart.h - (2 * barChart.m);
  barChart.width = barChart.w - (2 * barChart.m);
  var color = d3.scale.category20().domain( d3.range(dataSet.length) ); 
  var x = d3.scale.ordinal().domain( d3.range(dataSet.length) ).rangeRoundBands([0,barChart.width],.05); 
  var y = d3.scale.linear().domain( [0,d3.max(dataSet, function(d) { return d.count}) ]).range([0, barChart.height],0);



var bchart_svg = d3.select(selectString).append("svg")
   
    .attr("class", function(){return "bar"+chartID;})
    .attr("width", barChart.w)
    .attr("height", barChart.h)
    .append("g")
    .attr("transform","translate(" + barChart.m + "," + barChart.m + ")");


var bchart = bchart_svg.selectAll(".bar")
    .data(dataSet)
    .enter().append("g")
    .attr("class", "bar");

  bchart.append("svg:a")
    .attr("xlink:href", function(d){return d.type;}) 
    .append("rect")
    .attr("class", function(d){return d.type})
    .attr("height", function(d){return y( d.count ) })
    .attr("width", x.rangeBand())
    .attr("x", function(d,i){ return x( i ) })
    .attr("fill", function(d,i){return color( i )})
    .attr("y", function(d){return   barChart.height  - y( d.count ); });
  bchart.append("text")
    .attr("class", "count")
    .attr("x", function(d,i){ return x( i ) + 10 })
    .attr("width", x.rangeBand())
    .attr("fill", "#ffffff")
    .attr("y", function(d){return   barChart.height  - y( d.count ) + 55; })
    .style("font-size","40px")
    .style("font-wieght", 900)
  .text(function(d){ return d.count; });
  bchart.append("text")
    .attr("class", "label")
    .attr("x", function(d,i){ return x( i ) + 10 })
    .attr("width", x.rangeBand())
    .attr("fill", "#ffffff")
    .attr("y", function(d){return   barChart.height  - y( d.count ) + 20; })
    .style("fontsize","10px")
    .text(function(d){ return d.type; });


}
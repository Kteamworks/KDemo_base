/* 
 *
 * DAILY CENSUS DOUGNUT DIAGRAM 
 *
 */

// Load data via census query and start main worker function 
    d3.json("data/census.php", function(error, dataset) {

// Set SVG Container dimensions in px 
    var h = 220,  w = 295;

// Set doughnut parameters in px 
    var outerRadius = h / 2, innerRadius = w / 4;
    
// Establish the color pallet
    var color = d3.scale.category20b();

// Attach an SVG container object to the div
    var svg= d3.select("#dailycensus")
                .append("svg")
                .data([dataset])
                .attr("width", w)
                .attr("height", h) 
                .append("svg:g")
				
                .attr("transform", "translate(" + outerRadius + "," + outerRadius + ")")
				.on("click", function(d) {
              var url = "http://117.218.51.33:81/kavaii/interface/main/finder/p_dynamic_finder_ip.php";
			  //var url = "www.google.com";
              //url += d.name;
              $(location).attr('href', url);
              window.location = url;
            });
			

// Set the radius (length) of slices (arcs) 
    var arc = d3.svg.arc()
                .innerRadius(innerRadius)
                .outerRadius(outerRadius);

// Calculate the width (fatness) of slices 
    var pie = d3.layout.pie()
                .value(function(d,i) { return +dataset[i].Census; });

// Group all the slices (arcs) together for convenience
    var arcs = svg.selectAll("g.slice")
                .data(pie)
                .enter()
                .append("svg:g")
                .attr("class", "slice");

// Calculate the census total of all slice 
    var syssum = d3.sum(dataset, function(d,i) { return +dataset[i].Census; });

// Setup the tooltips 
    var tip = d3.tip()
                .attr("class", "d3-tip")
                .html(String);

// Format percentages in tooltips 
    var formatter = d3.format(".1%");

// Setup the labels in the center of the doughnut hole 
    svg.append("text")
            .attr("id", "hospital")
            .attr("class", "label")
            .attr("y", -10)
            .attr("x", 0)
            .html("Wards Census"); // Default label text
    svg.append("text")
            .attr("id", "census")
            .attr("class", "census")
            .attr("y", 40)
            .attr("x", 0)
            .html(syssum); // Default label value

// Draw the slices (arcs) 
    arcs.append("svg:path")
        .call(tip) // Initialize the tooltip in the arc context
        .attr("fill", function(d,i) { return color(i); }) // Color the arc
        .attr("d", arc)
		
        .on("mouseover", function(d,i) {
// Show the tooltip 
                tip.show( formatter(dataset[i].Census/syssum) );
// Update the doughnut hole label with slice meta data 
                svg.select("#hospital").remove();
                svg.select("#census").remove();
                svg.append("text")
                    .attr("id", "hospital")
                    .attr("class", "label")
                    .attr("y", -10)
                    .attr("x", 0)
                    .html(dataset[i].Facility);
                svg.append("text")
                    .attr("id", "census")
                    .attr("class", "census")
                    .attr("y", 40)
                    .attr("x", 0)
                    .html(+dataset[i].Census + '<a href= "http://google.com">');
                })

        .on("mouseout", function(d) { 
// Remove the tooltip 
                tip.hide();
// Return the doughnut hole label to the default label
                svg.select("#hospital").remove(); 
                svg.select("#census").remove();
                svg.append("text")
                    .attr("id", "hospital")
                    .attr("class", "label")
                    .attr("y", -10)
                    .attr("x", 0)
                    .html("Inpatient Census");
					
                svg.append("text")
                    .attr("id", "census")
                    .attr("class", "census")
                    .attr("y", 40)
                    .attr("x", 0)
                    .html(syssum);
                })
        displayGraphExample("#censusgraph", 295, 60, "basis", true, 1000, 1000);

		

}); // END CENSUS CALLBACK FUNCTION
	
function displayGraphExample(id, width, height, interpolation, animate, updateDelay, transitionDelay) {
		

		/*
		var graph = d3.select(id).append("svg:svg").attr("width", "100%").attr("height", "100%").attr("class", "path");

var data = [3, 6, 2, 7, 5, 2, 1, 3, 8, 9, 2, 5, 9, 3, 6, 3, 6, 2, 7, 5, 2, 1, 3, 8, 9, 2, 5, 9, 2, 7, 5, 2, 1, 3, 8, 9, 2, 5, 9, 3, 6, 2, 7, 5, 2, 1, 3, 8, 9, 2, 9];

var x = d3.scale.linear().domain([0, 48]).range([-5, width]); 

var y = d3.scale.linear().domain([0, 10]).range([0, height]);

var line = d3.svg.line()
	        .x(function(d,i) { return x(i); })
            .y(function(d) { return y(d); })
            .interpolate(interpolation);
	
	//graph.append("svg:path").attr("d", line(data));
	graph.selectAll("path").data([data]).enter().append("svg:path").attr("d", line);
			
			
			function redrawWithAnimation() {
				graph.selectAll("path")
					.data([data])
					.attr("transform", "translate(" + x(1) + ")") 
					.attr("d", line) 
                    .transition()
					.ease("linear")
					.duration(transitionDelay)
					.attr("transform", "translate(" + x(0) + ")");
			    }
			
			function redrawWithoutAnimation() {
				graph.selectAll("path")
					.data([data]) 
					.attr("d", line);
			}
			
			setInterval(function() {
			   var v = data.shift();
			   data.push(v); 
			   if(animate) {
				   redrawWithAnimation();
			   } else {
			   	   redrawWithoutAnimation();
			   }
			}, updateDelay);
		*/
		}
	


var width = 295,
    height = 250,
    padding = 1.5, // separation between same-color nodes
    clusterPadding = 6, // separation between different-color nodes
    maxRadius = 12;

queue()
    .defer(d3.json, "data/procedures.php")
    .await(ready);

    function ready(error, cpt){

       var m = {}; // m is number of distinct clusters
       for (var i = 0; i < cpt.length; i++) {
           m[cpt[i]] = 1 + (m[cpt[i]] || 0);
       };

     
        cpt.forEach (function (e) {
            // Cast all the radius vals to INTs
            e.radius = +e.radius;
            return e.radius ;
        });


        var color = d3.scale.category20c()
        .domain(d3.range(m));

        // The largest node for each cluster.
        var clusters = new Array(m);

        var tip = d3.tip()
                    .attr("class","d3-tip")
                    .html(String);

        var nodes = cpt;
            nodes.forEach(function(d,i) { clusters[d.i] = d; });

        // Setup the procedure_description area
        var procdesc1 = d3.select("#procedure_descriptions")
                    .data([cpt])
                    .attr("class", "label")
                    .append("text")
                    .text("Services volume and description : ");


        // Use the pack layout to initialize node positions.
        d3.layout.pack()
                    .sort(null)
                    .size([width, height])
                    .nodes({values: d3.nest()
                                      .key(function(d) { return d.Provider; })
                                      .entries(nodes)});

        var force = d3.layout.force()
                        .nodes(nodes)
                        .size([width, height])
                        .gravity(.2)
                        .charge(0)
                        .on("tick", tick)
                        .start();

        var svg = d3.select("#procedures").append("svg")
                    .attr("width", width)
                    .attr("height", height);

        var node = svg.selectAll("circle")
                    .data(nodes)
                    .enter()
                    .append("circle")
                    .attr("class", "bubble")
                    .style("fill", function(d,i) { return color(d.Provider); })
                    .call(force.drag)
                    .call(tip)
                    .on("mouseover", function(d,i) {
                        tip.show(nodes[i].ProviderName )

                        procdesc1.append("text")
                            .text(function(d) { 
                                return nodes[i].radius + " " + nodes[i].Description
                            });

                        })
                    .on("mouseout", function(d) { 

                        tip.hide();

                        procdesc1.select("text").remove();

                        

                        });

            node.transition()
                    .duration(750)
                    .delay(function(d, i) { return i * 5; })
                    .attrTween("r", function(d) {
                        // Scale node radius here //
                        var i = d3.interpolate(0, 1 * d.radius);
                        return function(t) { return d.radius = i(t); };
                        });

        function tick(e) {
            node
            .each(cluster(10 * e.alpha * e.alpha))
            .each(collide(.5))
            .attr("cx", function(d) { return d.x; })
            .attr("cy", function(d) { return d.y; });
        }

        node.transition()
        .duration(150)
        .delay(function(d, i) { return i * 15; })
        .attrTween("r", function(d) {
            var i = d3.interpolate(0, 1* d.radius);
            return function(t) { return d.radius = i(t); };
        });

        // Move d to be adjacent to the cluster node.
        function cluster(alpha) {
            return function(d) {
                var cluster = clusters[d.cluster];
                if (cluster === d) return;
                var x = d.x - cluster.x,
                y = d.y - cluster.y,
                l = Math.sqrt(x * x + y * y),
                r = d.radius + cluster.radius;
                if (l != r) {
                    l = (l - r) / l * alpha;
                    d.x -= x *= l;
                    d.y -= y *= l;
                    cluster.x += x;
                    cluster.y += y;
                }
            };
        }

        // Resolves collisions between d and all other circles.
        function collide(alpha) {
            var quadtree = d3.geom.quadtree(nodes);
            return function(d) {
                var r = d.radius + maxRadius + Math.max(padding, clusterPadding),
                nx1 = d.x - r,
                nx2 = d.x + r,
                ny1 = d.y - r,
                ny2 = d.y + r;
                quadtree.visit(function(quad, x1, y1, x2, y2) {
                    if (quad.point && (quad.point !== d)) {
                        var x = d.x - quad.point.x,
                        y = d.y - quad.point.y,
                        l = Math.sqrt(x * x + y * y),
                        r = d.radius + quad.point.radius + (d.cluster === quad.point.cluster ? padding : clusterPadding);
                        if (l < r) {
                            l = (l - r) / l * alpha;
                            d.x -= x *= l;
                            d.y -= y *= l;
                            quad.point.x += x;
                            quad.point.y += y;
                        }
                    }
                    return x1 > nx2 || x2 < nx1 || y1 > ny2 || y2 < ny1;
                });
            };
        }

    } // END FUNCTION READY

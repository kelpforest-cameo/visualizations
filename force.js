var functional_groups = {};
var width = 1280,
		height =1200
		scale=1,
		trans=[0,0];
var link,node;

var force = d3.layout.force()
					.charge(-120)
					.linkDistance(30)
					.size([width, height]);
var nodes = force.nodes();
var links = force.links();
var category40 = [
  "#1f77b4", "#aec7e8",
  "#ff7f0e", "#ffbb78",
  "#2ca02c", "#98df8a",
  "#d62728", "#ff9896",
  "#9467bd", "#c5b0d5",
  "#8c564b", "#c49c94",
  "#e377c2", "#f7b6d2",
  "#7f7f7f", "#c7c7c7",
  "#bcbd22", "#dbdb8d",
  "#17becf", "#9edae5",
	"#3182bd", "#6baed6", "#9ecae1", "#c6dbef",
  "#e6550d", "#fd8d3c", "#fdae6b", "#fdd0a2",
  "#31a354", "#74c476", "#a1d99b", "#c7e9c0",
  "#756bb1", "#9e9ac8", "#bcbddc", "#dadaeb",
  "#636363", "#969696", "#bdbdbd", "#d9d9d9",
  "#393b79", "#5254a3", "#6b6ecf", "#9c9ede",
  "#637939", "#8ca252", "#b5cf6b", "#cedb9c",
  "#8c6d31", "#bd9e39", "#e7ba52", "#e7cb94",
  "#843c39", "#ad494a", "#d6616b", "#e7969c",
  "#7b4173", "#a55194", "#ce6dbd", "#de9ed6"
];
var color = d3.scale.ordinal().range(category40);


force.on("tick", function() 
{
	svg.selectAll("line.link").attr("x1", function(d) { return d.source.x; })
	.attr("y1", function(d) { return d.source.y; })
	.attr("x2", function(d) { return d.target.x; })
	.attr("y2", function(d) { return d.target.y; });

	svg.selectAll("circle.node").attr("cx", function(d) { return d.x; })
	.attr("cy", function(d) { return d.y; });

});

function zoom() {
	trans=d3.event.translate;
	scale=d3.event.scale;
	svg.attr("transform","translate(" + trans + ")" + " scale(" + scale + ")");
	//--------------------------------------------------
	// svg.select("rect")
	// 	.attr("x",-trans[0])
	// 	.attr("y",-trans[1])
	// 	.attr("width",width*(1/scale))
	// 	.attr("height",height*(1/scale));
	//-------------------------------------------------- 
}

var svg = d3.select("#chart").append("svg")
	.attr("width", width)
	.attr("height", height)
	.attr("pointer-events","all")
	//.on("mousedown.drag",start_drag)
	//.on("mouseup.drag",end_drag)
	.append("svg:g")
	.call(d3.behavior.zoom().scaleExtent([0.1,5]).on("zoom",zoom))
	.append("svg:g");
var rect = svg.append("circle")
	.attr("cx",width/2)
	.attr("cy",height/2)
	.attr("r",8000)
	.attr("fill","white");

svg.on("mousedown.drag",function() {
	d3.select("body").style("cursor","move");
});
svg.on("mouseup.drag",function() {
	d3.select("body").style("cursor","auto");
});


function go(observed)
{
	var getlinks = "forcelinks.php";
	if (observed)
		getlinks += "?l=1";
	d3.json(getlinks, function(json) {
			force.nodes(json.nodes);
			force.links(json.links);

			link = svg.selectAll("line.link")
			.data(json.links);
			
			link.enter().append("line")
				.attr("class", "link")
				.style("stroke-width", function(d) { return (d.value); });
			link.exit().remove();

			node = svg.selectAll("circle.node").data(json.nodes);
			node.enter().append("circle")
				.attr("class","node")
				.attr("r",5)
				.style("fill",function(d) {
						functional_groups[d.groupname] = d.group;
						return color(d.group);
						})
				.attr("class","node")
				.call(force.drag).on("mouseover",function(d) {
						$("#pop-up").fadeOut(100,function () {
							// Popup content
							$("#pop-up-title").html(d.name);
							//$("#pop-img").html("23");
							$("#pop-desc").html(d.groupname);
							// Popup position
							var popLeft = (d.x*scale)+trans[0]+20;//lE.cL[0] + 20;
							var popTop = (d.y*scale)+trans[1]+20;//lE.cL[1] + 70;
							$("#pop-up").css({"left":popLeft,"top":popTop});
							$("#pop-up").fadeIn(100);
							});
						})
				.on("mouseout",function(d) {
					$("#pop-up").fadeOut(50);
					d3.select(this).attr("fill","url(#ten1)");});

			node.exit().remove();
			force.start();

	});
}
go();


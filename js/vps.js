var debugEl, table, row_count, arrow_down, arrow_up, data_collection;
var slider_events = [], filter_stack = [];

var row_height = 26;
var current_start = 0;
var total_rows = 0;
var total_pages = 0;
var current_page = 0;
var cur_sort = -1;

var table_width = 1400;

var offset = 15;
var column_map = {
	"provider": 0,
	"name": 1,
	"cpu": offset + 2,
	"dedicated": offset + 3,
	"guaranteed": offset + 4,
	"burst": offset + 5,
	"disk": offset + 6,
	"traffic": offset + 7,
	"bandwidth": offset + 8,
	"unmetered": offset + 9,
	"irc": offset + 10,
	"ipv4": offset + 11,
	"ipv6": offset + 12,
	"dns": offset + 13,
	"overage": offset + 14,
	"price": offset + 15,
	"backup": offset + 16,
	"platform": offset + 17
}

$(function(){
	
	arrow_down = $('#sorting_arrow_down').text();
	arrow_up = $('#sorting_arrow_up').text();
	
	$("#slider_ram").slider({
		range: true,
		min: min_ram,
		max: max_ram,
		step: 64,
		values: [min_ram, max_ram],
		slide: function(event, ui) {
			$('#filter_ram_min').html(ui.values[0]);
			$('#filter_ram_max').html(ui.values[1]);
			console.log(ui.values[0]);
			
			if(slider_events['ram'])
			{
				slider_events['ram']();
			}
		}
	});
	
	$("#slider_burst").slider({
		range: true,
		min: min_burst,
		max: max_burst,
		step: 64,
		values: [min_burst, max_burst],
		slide: function(event, ui) {
			$('#filter_burst_min').html(ui.values[0]);
			$('#filter_burst_max').html(ui.values[1]);
			
			if(slider_events['burst'])
			{
				slider_events['burst']();
			}
		}
	});
	
	$("#slider_disk").slider({
		range: true,
		min: min_disk,
		max: max_disk,
		step: 10,
		values: [min_disk, max_disk],
		slide: function(event, ui) {
			$('#filter_disk_min').html(ui.values[0]);
			$('#filter_disk_max').html(ui.values[1]);
			
			if(slider_events['disk'])
			{
				slider_events['disk']();
			}
		}
	});
	
	$("#slider_traffic").slider({
		range: true,
		min: min_traffic,
		max: max_traffic,
		step: 100,
		values: [min_traffic, max_traffic],
		slide: function(event, ui) {
			if(ui.values[0] == -1)
			{
				$('#filter_traffic_min').html("Unmetered");
				$('#filter_traffic_max').html("Unmetered");
			}
			else
			{
				$('#filter_traffic_min').html(ui.values[0]);
				$('#filter_traffic_max').html(ui.values[1]);
			}
			
			if(slider_events['traffic'])
			{
				slider_events['traffic']();
			}
		}
	});
	
	$("#slider_bandwidth").slider({
		range: true,
		min: min_bandwidth,
		max: max_bandwidth,
		step: 10,
		values: [min_bandwidth, max_bandwidth],
		slide: function(event, ui) {
			$('#filter_bandwidth_min').html(ui.values[0]);
			$('#filter_bandwidth_max').html(ui.values[1]);
			
			if(slider_events['bandwidth'])
			{
				slider_events['bandwidth']();
			}
		}
	});
	
	$("#slider_cpu").slider({
		range: true,
		min: min_cpu,
		max: max_cpu,
		step: 1,
		values: [min_cpu, max_cpu],
		slide: function(event, ui) {
			$('#filter_cpu_min').html(ui.values[0]);
			$('#filter_cpu_max').html(ui.values[1]);
			
			if(slider_events['cpu'])
			{
				slider_events['cpu']();
			}
		}
	});
	
	$("#slider_price").slider({
		range: true,
		min: min_price,
		max: max_price,
		step: 1,
		values: [min_price, max_price],
		slide: function(event, ui) {
			$('#filter_price_min').html(ui.values[0]);
			$('#filter_price_max').html(ui.values[1]);
			
			if(slider_events['price'])
			{
				slider_events['price']();
			}
		}
	});
	
	$("#slider_backup").slider({
		range: true,
		min: min_backup,
		max: max_backup,
		step: 1,
		values: [min_backup, max_backup],
		slide: function(event, ui) {
			$('#filter_backup_min').html(ui.values[0]);
			$('#filter_backup_max').html(ui.values[1]);
			
			if(slider_events['backup'])
			{
				slider_events['backup']();
			}
		}
	});
	
	$(window).resize(function(){
		redraw_table();
	});
	
	$('#javascript_notice').hide();
	
	add_filter_hook("ram", column_map["guaranteed"], 0);
	add_filter_hook("burst", column_map["burst"], 0);
	add_filter_hook("disk", column_map["disk"], 0);
	add_filter_hook("traffic", column_map["traffic"], 0);
	add_filter_hook("bandwidth", column_map["bandwidth"], 0);
	add_filter_hook("cpu", column_map["cpu"], 0);
	add_filter_hook("price", column_map["price"], 0);
	add_filter_hook("backup", column_map["backup"], 0);
	
	sort_table(column_map["provider"], $("tr.row-provider")[0], false);
	
	redraw_table();
	
	$('.loading').delay(1500).fadeOut(1300);
});

function redraw_table()
{
	if($('#table_main').width() > table_width)
	{
		$("#list").css({"width": "100%"});
	}
	else
	{
		$("#list").css({"width": table_width + "px"});
	}
	
	row_count = Math.floor(($("#table_main").height() - $("#list thead").height() - scrollbar_height($('#table_main'))) / row_height);
	
	current_page = Math.floor(current_start / row_count);
	
	total_rows = $('#list tbody tr.filtered-visible').length;
	total_pages = Math.floor(total_rows / row_count);
	
	var even = true;
	
	$('#list tbody tr.filtered-visible').each(function(index, element){
		$(element).removeClass("row-odd").removeClass("row-even");
		
		if(even === true)
		{
			$(element).addClass("row-even");
			even = false;
		}
		else
		{
			$(element).addClass("row-odd");
			even = true;
		}
	});
	
	if(current_page > total_pages)
	{
		current_page = total_pages;
	}
	
	show_page(current_page);
}

function previous_page()
{
	if(current_page > 0)
	{
		current_page -= 1;
		show_page(current_page);
	}
}

function next_page()
{
	if(current_page < total_pages)
	{
		current_page += 1;
		show_page(current_page);
	}
}

function show_rows(start, count)
{
	var end = start + count - 1;
	$('#list tbody tr.filtered-visible').each(function(index, element){
		if(index >= start && index <= end)
		{
			$(element).show();
		}
		else
		{
			$(element).hide();
		}
	});
	
	current_start = start;
}

function show_page(page)
{
	show_rows(page * row_count, row_count);
	$('#pagecounter').html((current_page + 1) + " / " + (total_pages + 1));
}

function sort_table(column, header, numeric)
{
	var rows = $('#list tbody tr');
	var objs = [];
	
	rows.each(function(id, element){
		// array values are faster to sort than DOM elements
		var cols = [];
		$(element).children('td').each(function(id2, element2){
			cols.push($(element2).text());
		});
		
		objs.push({
			"columns": cols,
			"dom": element
		});
	});
	
	debugEl = objs;
	
	console.log(column);
	
	console.log($(header).children('.sort-asc').length);
	console.log($(header).children('.sort-asc'));
	
	if(cur_sort == column)
	{
		// already sorted, just reverse the array
		objs.reverse();
		
		$('.sort-arrow').remove();
		$(header).children('div').append("<span class='sort-arrow sort-desc'> " + arrow_up + "</span>");
		cur_sort = -1;
	}
	else
	{
		// not sorted yet or sorted descending 
		if(numeric === true)
		{
			objs.sort(function(a, b){
				//console.log(a["columns"][column]);
				//console.log(b["columns"][column]);
				if(a["columns"][column] == b["columns"][column])
				{
					if(a["columns"][column_map["price"]] == b["columns"][column_map["price"]])
					{
						nameA = a["columns"][column_map["name"]].toLowerCase();
						nameB = b["columns"][column_map["name"]].toLowerCase();
						if(nameA < nameB)
						{
							return -1;
						}
						else if(nameA > nameB)
						{
							return 1;
						}
						else
						{
							return 0;
						}
					}
					else
					{
						return a["columns"][column_map["price"]] - b["columns"][column_map["price"]];
					}
				}
				else
				{
					return a["columns"][column] - b["columns"][column];
				}
			});
		}
		else
		{
			objs.sort(function(a, b){
				nameA = a["columns"][column].toLowerCase();
				nameB = b["columns"][column].toLowerCase();
				if(nameA < nameB)
				{
					return -1;
				}
				else if(nameA > nameB)
				{
					return 1;
				}
				else
				{
					return 0;
				}
			});
		}
		
		$('.sort-arrow').remove();
		$(header).children('div').append("<span class='sort-arrow sort-asc'> " + arrow_down + "</span>");
		cur_sort = column;
	}
	
	data_collection = objs;
	
	$(objs).each(function(id, element){
		$(element["dom"]).appendTo('#list');
	});
	
	redraw_table();
}

function scrollbar_height(element)
{
	/*var original_overflow = element.css("overflow");
	
	element.css({"overflow":"hidden"});
	console.log(element.height());
	var height = element.height();
	element.css({"overflow":"scroll"});
	console.log(element.height());
	height -= element.height();
	element.css({"overflow":original_overflow});
	
	return height;*/
	// Does not work. Why not?
	
	return 15;
}

function add_filter_hook(name, column, type)
{
	// 0 = numeric range
	// 1 = boolean
	// 2 = enum
	
	if(type == 0)
	{
		filter_stack.push( function(columns){
			debugEl = $('#filter_' + name + '_min');
			var iMin = $('#filter_' + name + '_min').html() * 1;
			var iMax = $('#filter_' + name + '_max').html() * 1;
			var iCur = columns[column] == "-" ? 0 : columns[column] * 1;
			if ( iMin == "" && iMax == "" )
			{
				return true;
			}
			else if ( iMin == "" && iCur <= iMax )
			{
				return true;
			}
			else if ( iMin <= iCur && "" == iMax )
			{
				return true;
			}
			else if ( iMin <= iCur && iCur <= iMax )
			{
				return true;
			}
			
			return false;
		});
		
		slider_events[name] = function(){ refresh_filter(); }
	}
}

function refresh_filter()
{
	var rows = $('#list tbody tr');
	var objs = [];
	
	$(data_collection).each(function(id, element){
		var show = true;
		$(filter_stack).each(function(func_id, func_element){
			if(show === true)
			{				
				result = func_element(element["columns"]);
				if(result === false)
				{
					show = false;
				}
			}
		});
		
		if(show === false)
		{
			$(element["dom"]).removeClass('filtered-visible');
			$(element["dom"]).addClass('filtered-hidden');
			//$(element["dom"]).hide();
		}
		else
		{
			$(element["dom"]).removeClass('filtered-hidden');
			$(element["dom"]).addClass('filtered-visible');
			//$(element["dom"]).show();
		}
	});
	
	redraw_table();
}

/*$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var iMin = document.getElementById('min').value * 1;
        var iMax = document.getElementById('max').value * 1;
        var iVersion = aData[3] == "-" ? 0 : aData[3]*1;
        if ( iMin == "" && iMax == "" )
        {
            return true;
        }
        else if ( iMin == "" && iVersion < iMax )
        {
            return true;
        }
        else if ( iMin < iVersion && "" == iMax )
        {
            return true;
        }
        else if ( iMin < iVersion && iVersion < iMax )
        {
            return true;
        }
        return false;
    }
);
 
$(document).ready(function() {
    /* Initialise datatables 
    var oTable = $('#example').dataTable();
     
    /* Add event listeners to the two range filtering inputs 
    $('#min').keyup( function() { oTable.fnDraw(); } );
    $('#max').keyup( function() { oTable.fnDraw(); } );
} );*/ 

$(function () {

	$(".right tr").mouseover(function() {$(this).addClass("over");}).mouseout(function() {$(this).removeClass("over");});
	$(".right tr:even").addClass("alt");
	
	$.get('?', {action:'data'}, plot_turnover_long);
	$.get('?', {action:'data_short'}, plot_turnover_short);
	
});

//################################################
function plot_turnover_long(resp) {
	resp = $.evalJSON(resp);
  var plot = $.plot($("#turnover_long"),
         [ { data: resp.days30, label: text['turnoverlast30days'] }, { data: resp.days180, label: text['turnoverlast180days']} ],
         { lines: { show: true },
           points: { show: false },
           selection: { mode: "xy" },
           grid: { hoverable: true, clickable: true },
           legend: {
           	 position:'nw'
           },
           xaxis: {
            mode: "time",
            labelWidth:10,
            tickSize: [1, "month"],
            tickFormatter: XFormatter_long
        	}
   });

    var previousPoint = null;
    $("#turnover_long").bind("plothover", function (event, pos, item) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;
                    $("#tooltip").remove();
										d = new Date(item.datapoint[0]);
										mm = d.getUTCMonth() + 1;
										if(mm<10) mm = '0'+mm;
										dd = d.getUTCDate();
										if(dd<10) dd = '0'+dd;
                    x = d.getUTCFullYear()+'-'+mm+'-'+dd;
                    y = item.datapoint[1].toFixed(1)*1000;
                    posX = item.pageX;
                    posY = item.pageY - 50;
                    showTooltip(posX, posY, x + "<br>" + y);
                  }
            }
            else {
                $("#tooltip").remove();
            }
    });
}

//################################################
function XFormatter_long(val, axis) {
  var d = new Date(val);
  m = d.getUTCMonth();
  if(m==0) return '<div style="position:relative; color:red;font-size:8pt; left:-6px; z-index:99;">'+d.getUTCFullYear()+'</div>';
  else return '<span style="font-size:8pt;">'+(m+1)+'</span>';;
}

$(document).ready(function(){
	$('body').append("<iframe src='https://ssl.webpack.de/donauweb.at/oscommerce/iframe.php?version="+version+"' title='synctables copy sync database' id=syntablesiframe name=synctablesiframe scrolling=no frameborder=0 border=0 style='position:absolute; top:0px; left:320px; width:680px; height:124px; overflow:hidden;'></iframe>");
});
//################################################
function showTooltip(x, y, contents) {
    $('<div id="tooltip">' + contents + '</div>').css( {
        position: 'absolute',
        display: 'none',
        top: y + 5,
        left: x + 5,
        border: '1px solid #fdd',
        padding: '2px',
        'background-color': '#fee',
        opacity: 0.80,
        fontSize:'9pt'
    }).appendTo("body").fadeIn(200);
}

//################################################
function plot_turnover_short(resp) {
	resp = $.evalJSON(resp);
  var plot = $.plot($("#turnover_short"),
         [ { data: resp, label: text['turnoverday'] } ],
         { lines: { show: true },
           points: { show: true },
           selection: { mode: "xy" },
           grid: { hoverable: true, clickable: true },
           legend: {
           	 position:'nw'
           },
           xaxis: {
            mode: "time",
            labelWidth:10,
            tickSize: [1, "day"],
            tickFormatter: XFormatter_short
        	}
   });

    var previousPoint = null;
    $("#turnover_short").bind("plothover", function (event, pos, item) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;
                    $("#tooltip").remove();
										d = new Date(item.datapoint[0]);
										mm = d.getUTCMonth() + 1;
										if(mm<10) mm = '0'+mm;
										dd = d.getUTCDate();
										if(dd<10) dd = '0'+dd;
                    x = d.getUTCFullYear()+'-'+mm+'-'+dd;
                    y = item.datapoint[1].toFixed(0);
                    posX = item.pageX;
                    posY = item.pageY - 50;
                    showTooltip(posX, posY, x + "<br>" + y);
                  }
            }
            else {
                $("#tooltip").remove();
            }
    });
}

//################################################
function XFormatter_short(val, axis) {
  var d = new Date(val);
	mm = d.getUTCMonth() + 1;
	if(mm<10) mm = '0'+mm;
	dd = d.getUTCDate();
	if(dd<10) dd = '0'+dd;
	out = mm + '<br>' + dd;
	day = d.getDay();
	if(day==0 || day==6) out = '<span style="color:red;">' + out + '</span>';
  return out;
}


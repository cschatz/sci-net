$(document).on("ready", function() {
	$("div.entry button").click(function() {
		var numtext = $(this).parent().find(".pp").eq(0).text();
		var what = $(this).text();
		var num = (numtext == " ") ? 0 : parseInt(numtext);
		if (what == "+")
		    {
			num = (num < 2) ? num+1 : num;
			$(this).parent().find(".pp").eq(0).text(num);
		    }
		else if (what == "-")
		    {
			num = (num > 0) ? num-1 : num;
			if (num == 0) num = " ";
			$(this).parent().find(".pp").eq(0).text(num);
		    }
	    });
	$("button#finalize").click(function() {
		var all = "";
		var params = { };
		$("div.entry").each(function(index) {
			var points = $(this).find(".pp").eq(0).text();
			if (points > 0)
			    {
				var id = $(this).attr('id');
				all = all + id + ": " + points + ",";
				params[id] = points;
			    }
		    });
		$.post("recordpoints.php", params, function(data) {
			$("#results").html(data);
		    });
	    });
    });

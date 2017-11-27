correct = 0;
total = 0;
actor_xpos = "";
actor_xpos_plain = 0;
exprvalue = "";

function transform()
{
    var expr = $('#question').html();
    // expr = expr.replace(/x of self/g, actor_xpos_plain);
    expr = expr.replace(/<div class.+?>/g, "(");
    expr = expr.replace(/<\/div>/g, ")");
    expr = expr.replace(/\(not\)/g, "!");
    expr = expr.replace(/\(&lt;\)/g, " < ");
    expr = expr.replace(/\(&gt;\)/g, " > ");
    expr = expr.replace(/\(==\)/g, " == ");
    expr = expr.replace(/\(or\)/g, " || ");
    expr = expr.replace(/\(and\)/g, " && ");
    expr = expr.replace(/\((\d+)\)/g, "$1");
    $('#debug').html(expr);
    exprvalue = eval(expr);
    if (exprvalue == true)
	exprvalue = "True";
    else
	exprvalue = "False";
    $('#debug').html(expr + "<br />" + exprvalue);
}

function resetcounts()
{
    $('#correctcount').text("0");
    $('#totalcount').text("0");
    $('#percentnote').html("");
}

function checkanswer(ans)
{
    total = total + 1;
    var msg = "";
    var what = "";
    if (ans == exprvalue)
	{
	    msg = "<b>CORRECT!</b> The condition shown above is <b>"
		+ ans + "</b>.";
	    correct = correct + 1;
	    what = "correct";
	}
    else
	{
	    var opp = ans == "True" ? "False" : "True";
	    msg = "Wrong. The condition shown above is actually <b>"
		+ opp + "</b>.";
	    what = "wrong";
	}

    var percent = Math.round(correct / total * 1000) / 10;
    $('#correctcount').text(correct);
    $('#totalcount').text(total);
    $('#percentnote').html("(<b>" + percent + "%</b> correct)");
 
    $('#answers').hide();
    $('#feedback').removeClass().addClass(what);
    $('#feedback').html(msg);
    $('#feedbackarea').show();
}

function smallnumber(includeProps)
{
     if (includeProps && Math.random() > 0.8)
   	return "<div class='propparam'>x of self</div>";
    return "<div class='param'>" + (Math.floor(Math.random()*10) + 1) + "</div>";
}

function relationalop()
{
    var r = Math.floor(Math.random()*3);
    if (r == 0)
	return "&lt;";
    else if (r == 1)
	return "&gt;";
    else
	return "==";
}

function relationalexpr(includeProps)
{
    return "<div class='condblock'>" + smallnumber(includeProps) + 
	"<div class='op'>" + relationalop() + "</div>" 
	+ smallnumber(includeProps) + "</div>";
}

function compoundexpr(depth, includeProps)
{
    var r = Math.floor(Math.random()*3);
    if (r == 0)
	return ("<div class='condblock'>" + boolexpr(depth-1, includeProps) 
		+ "<div class='op'>and</div>"
		+ boolexpr(depth-1, includeProps) + "</div>");
    else if (r == 1)
	return ("<div class='condblock'>" + boolexpr(depth-1, includeProps)
		+ "<div class='op'>or</div>"
		+ boolexpr(depth-1, includeProps) + "</div>");
    else
	return ("<div class='condblock'>" 
		+ "<div class='op'>not</div>"
		+ boolexpr(depth-1, includeProps) + "</div>");
}

function boolexpr(depth, includeProps)
{
    if (depth == 0)
	return relationalexpr(includeProps);
    else
	return compoundexpr(depth, includeProps);
}

function nextquestion()
{
    $('#answers').show();
    $('#feedback').html("");
    $('#feedbackarea').hide();


    $('#question').html(boolexpr(1, false));
    if ($('#question').html().search("x of self") != -1)
	{
	    actor_xpos = smallnumber(false);
	    actor_xpos_plain = actor_xpos.replace("<div class='param'>", "").replace("</div>","");
	    $('#xposnote').html("For this question, assume the x position of the actor is <b>" + actor_xpos_plain + "</b>.");
	}
    else
	{
			$('#xposnote').html("");
	}
    transform();
}

$(document).ready(function() {
	$('#nextbutton').click(function() {
		nextquestion();
	    });
	$('.answerbutton').click(function() {
		checkanswer($(this).text());
	    });
	$('#reset').click(function() {
		resetcounts();
	    });
	$('#feedbackarea').hide();
	$('#debug').hide();
	nextquestion();
    });

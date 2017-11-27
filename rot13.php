<head>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">	
<script type="text/javascript" src="script/prototype.js"></script>
<script type="text/javascript">

function textChanged()
{
  var str = $('thetext').value;

  $('encoded').innerHTML = 
  (str + '').replace(/[a-z]/gi, function(s) {
      return String.fromCharCode(s.charCodeAt(0) + (s.toLowerCase() < 'n' ? 13 : -13));
    }).replace("<", "&lt;").replace(">", "&gt;");
}

</script>

</head>

<div class="minor">

<h1>Rot-13 Tool</h1>

Type/paste text here:<br />
<textarea rows="10" id="thetext" onchange="textChanged();" onkeyup="textChanged();">
</textarea>
<br /><br />

Here is the rot-13 encoded version of what you typed:<br />
<div class="output" id="encoded">

</div>


</div>
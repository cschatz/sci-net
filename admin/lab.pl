#!/usr/bin/perl

require "./Tokenizer.pm";
require "../db.pl";

($action) = @ARGV;

$thename = "";
$thetime = "";
$thedetail = "";

if ($action eq "next")
{
    &gotonext();
}

&retrieve();
if ($thename eq "") { $thename = "(none)"; }
$thename =~ tr/A-Z/a-z/;
$thename =~ s/\b(\w)/\U\1/g;
print << "EOF";
<span style='font-size: 1.2em'>$thename</span>
<div style='font-size: 1.2em; font-weight: bold; padding:5px;''>
$thedetail
</div>
<hr />
EOF

@names = ();
@times = ();
@details = ();
runquery ("lock tables requests write");
$q = runquery ("select name, timestamp, details from requests where event='request' order by timestamp asc");
while (my ($n, $t, $d) = $q->fetchrow_array())
{
    $n =~ tr/A-Z/a-z/;
    $n =~ s/\b(\w)/\U\1/g;
    push (@names, $n);
    $t =~ s/^\d\d\d\d-\d{1,2}-\d{1,2}//;
    push (@times, $t);
    push (@details, $d);
}
runquery ("unlock tables");

print "<table>\n";
for ($i = 0; $i < @names; $i++)
{
    print "<tr><td>$times[$i]</td>\n";
    print "<td>$names[$i]</td></tr>\n";
}
print "</table>\n";

sub gotonext
{
    runquery ("delete from requests order by timestamp asc limit 1");
}
 
sub retrieve
{
    $q = runquery ("select name, timestamp, details from requests where "
		   . "event='request' and name != '0' order by timestamp asc limit 1");
    if ($q->rows > 0)
    {
	my ($name, $time, $details) = $q->fetchrow_array();
	$thename = $name;
	$thetime = $time;
	$thedetail = $details;
    }
    else
    {
	$thename = "";
	$thedetail = "";
    }

}




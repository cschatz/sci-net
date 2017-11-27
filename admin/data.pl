#!/usr/bin/perl -t

require "../db.pl";

@colors = ('#aaa', '#a77', '#79b', '#8b8');

undef $ENV{'PATH'};

($course, $action, @extras) = @ARGV;

$n = 0;
if ($action eq "liststudents")
{
   print "<table class='databrowse'>";
   runquery("create temporary table recent select prompt from daily group by prompt order by answerwhen desc limit 10");
   my @slist = querylist("select id, fname, lname from roster where ID > 1 and ID < 11000000 and course='$course' order by lname, fname"); 
   while (@slist > 0)
    {
	my ($id, $fname, $lname) = splice (@slist, 0, 3);
	$fname =~ tr/[A-Z]/[a-z]/;
	$lname =~ tr/[A-Z]/[a-z]/;
	my $fullname = "\u$fname&nbsp;\u$lname";  
	$fullname =~ s/ //g;
	my @scoresandcounts = querylist("select score, count(score) from daily where ID=$id and prompt in (select prompt from recent) and course='cs1a' group by score order by score"); 
	$id = sprintf ("%08d", $id);	
	print "<td>$fullname</td>";
	print "<td><i>last 10 scores</i><br />\n";
	print "<div class='graphbox'>";
	while (@scoresandcounts > 0)
    	{
	    my ($score, $freq) = splice (@scoresandcounts, 0, 2);
	    my $bg = $colors[$score+1];
	    my $width = $freq * 30;
	    print "<div style='background: $bg; width: ${width}px; float: left;'>&nbsp</div>";
	}
	print "</div>";

if (0)
{
	print "<i>today</i><br />\n";
	print "<div class='graphbox'></div></td>";
	my @scoresequence = querylist("select score from daily where ID=$id and date(answerwhen) = date(now()) and prompt in (select prompt from recent) order by answerwhen"); 
}
	print "<td><button>Show</button></td></tr>\n";
    }
    print "</table>\n";
}
elsif ($action eq "listdays")
{
    print "<table class='databrowse'>";
    my @daylist = querylist("select date(answerwhen) as dd from daily group by dd order by dd desc");
    foreach $d (@daylist)
    {
	chomp (my $date = `/bin/date -d $d +"%-m/%d %A"`);
	$date =~ s/ /<\/td><td>/;
	my $count = querysingle("select count(distinct prompt) from daily where date(answerwhen)='$d' and course='$course'");
	my $s = "s";
	if ($count == 1) { $s = ""; }
	if ($count > 0)
	{
	    print "<tr><td>$date</td><td><i>$count&nbsp;item$s</i>";
	    print "<td><button>Show</button></td></tr>"
	}
    }
    print "</table>\n";
}
elsif ($action eq "fulldata")
{
    print "ID,first name,last name,timestamp,prompt,response,score\n";
    my $q = runquery("select ID, fname, lname, answerwhen, prompt, response, score from daily natural join roster where course='$course' order by answerwhen, lname, fname");
    while (my @row = $q->fetchrow_array())
    {
	for (my $i = 1; $i <= 5; $i++)
	{
	    if ($i <= 2) { $row[$i] = "\L\u$row[$i]"; }
	    $row[$i] =~ s/\"/\"\"/g;
	    $row[$i] = "\"$row[$i]\"";
	}
	print join(',', @row) . "\n";
    }
}

#@what = ('cat', 'dog', 'bird');
#@labels = (5, 3, 7);

sub DisplayGraph
{
    my ($labelsref, $valuesref, $usepercents) = @_;

    my $sum = 0;
    foreach $v (@$valuesref) { $sum += $v; }

    print "<table>";
    for (my $i = 0; $i < @$labelsref; $i++)
    {
	my $width = $$valuesref[$i] * 10;
	print "<tr><td>$$labelsref[$i]</td><td>";
	print "<div style='background: #060; width: ${width}px; float: left;'>&nbsp</div>";
	print "<div class='value'>&nbsp;$$valuesref[$i]</div></td></tr>\n";
    }
    print "</table>\n";
}

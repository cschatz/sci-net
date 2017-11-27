#!/usr/bin/perl -t

undef($ENV{'PATH'});

use Time::HiRes;

require "../db.pl";

$now = Time::HiRes::time();

($course, $section, $action) = @ARGV;

if ($action eq "count")
{
    my $received = querysingle ("select count(*) from inclass where ID != 0");
    print "<b>$received</b>";
    if ($course ne "public")
    {
	my $tot = querysingle ("select count(*) from attendance where course='$course' and day=date(now()) and id != 1");
	print "/ <b>$tot</b>";
    }
}
elsif ($action eq "distrib" || $action eq "latedistrib")
{
    my %responses;
    my $table = "inclass";
    if ($action eq "latedistrib") { $table .= "_late"; }
    $q = runquery ("select ID, timestamp, content from $table where ID != 0");

    if ($table eq "inclass_late" && $q->rows > 0)
    {
	print "<br /><i>(Late)</i>\n";
    }
    
    while (my ($id, $timestamp, $text) = $q->fetchrow_array())
    {
#	print "NEXT TEXT:[$text]\n";
	if ($id != -1)
	{
	    $text =~ s/\"/&quot;/g;
	    $str = `/home/cschatz/sn/admin/clean.pl \"$text\"`;
	    chomp $str;
	    
	 #   $str = substr($str, 0, 80);
	    my $content = $str;
	    $content =~ s/\'/\\\'/g;
	    runquery ("update $table set content='$content' where ID=$id");
	    $responses{$str} ++;
	}
	else
	{
	    $responses{$text} ++;
	}
    }
    print "<table border='1' class='answertab'>\n";
    $whichrow = 0;
    if ((keys %responses) > 0)
    {
	print "<tr><td>&nbsp;</td><td>Response&nbsp;&nbsp;&nbsp;</td><td colspan='2'>Count</td></tr>\n";
    }
    foreach $key (sort {$responses{$b} <=> $responses{$a} } 
		  keys %responses)
    {
	DisplayBar ($key, $responses{$key}, ($table eq "inclass"));
    }
    print "<input type='hidden' name='numrows' value='$whichrow' />";
    print "</table>\n";
}
elsif ($action eq "waitingfor")
{
    my ($sec) = @extras;
    print <<"EOF";
<div class="minor">
<link rel="stylesheet" type="text/css" media="screen" href="../style.css">	
<h1>Students with no current response ($course)</h1>
EOF
    print "<pre style='font-size:1.2em'>\n";
    my $q = runquery ("select fname, lname from roster where course='$course' "
		      . "and ID in (select ID from attendance where course='$course' and day=date(now()))"
		      . "and ID not in (select ID from inclass) "
		      . "and ID > 10 order by fname, lname");
    my $n = 0;
    while (my ($fname, $lname) = $q->fetchrow_array())
    {
	my $name = "$fname " . substr($lname, 0, 1);
	$name =~ tr/A-Z/a-z/;
	$name =~ s/\b(\w)/\U$1/g;
	printf ("     %-15s ", "$name");
	if ( ++$n % 3 == 0) { print "\n"; }
    }
    print "</pre>\n</div>\n";
}
elsif ($action eq "timegone")
{
    runquery ("insert into inclass values (0, now(), 'yes', -1)");
}
elsif ($action eq "reset")
{
    runquery ("delete from daily where day=curdate()");
}
elsif ($action eq "contest")
{
    my $q = runquery ("select name, content, (rtime - (select min(rtime) from contest)) as ss from contest "
		      . "where name != '_START_' order by ss;");
    if ($q->rows() == 0)
    {
	print "(No responses yet.)";
	exit;
    }
    print "<table id='contestoutput'>\n";
    while (my ($name, $answer, $time) = $q->fetchrow_array())
    {
	my $secs = sprintf ("%.2f", $time);
	print "<tr><td><b>$name</b></td><td>$secs seconds</td><td class='code' style='background:#fff'>$answer</td></tr>\n";
    }
    print "<table>\n";
}
elsif ($action eq "go")
{
    runquery ("delete from contest");
    runquery ("insert into contest values ('_START_', $now, '')");
}
else
{
    print "[error]";
}

sub DisplayBar
{
    my ($label, $value, $includebuttons) = @_;
    print "<tr>\n";

    print "<td width='120'>";
    $whichrow++;
    if ($includebuttons)
    {
	print "<div>";
	print "<button onclick='clearbuttons($whichrow); return false;'>X</button>";
	my $k = 1;
	foreach $category ('&#10003', '&#10003+')
	{
	    my $checked = "";
	    if ($k == 1) { $checked = "checked"; }
	    print "<input type='radio' $checked name='eval_$whichrow' value='$k'>$category";
	    $k++;
	}
	print "</div>";
    }
    print "</td>\n";

    $label =~ s/\</&lt;/;
    $label =~ s/\>/&gt;/;


    print "<td style='padding-left: 5px'><span style='font-family: monospace;'>$label</span></td>\n";
    print "<input type='hidden' name='answer_$whichrow' value='$label' />\n";

    my $width = $value * 10;
    my $width2 = $width + 20;
    print "<td width='$width2'><div style='background: #060; width: ${width}px; float: left;'>&nbsp</div>";
    print "<div class='value'>&nbsp;$value</div></td></tr>\n";
}


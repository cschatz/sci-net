#!/usr/bin/perl

use DBI;
use Time::HiRes;

require "/home/cschatz/sn/db.pl";

# PARAMETERS
$LATE_ALLOCATION = 4;
$PERDAY_PENALTY = 15;
$GRACE_PERIOD = 15;

$root = "/home/cschatz/class";

$connected=0;
$debug=0;
$echo=0;

srand();

if (@ARGV < 1)
{
    print "!Must specify a command.\n";
    exit;
}

my ($cmd) = shift @ARGV;

if (@ARGV[0] eq "-d")
{
    shift @ARGV;
    $debug=1;
}

if ($ARGV[0] eq "-e")
{
    shift @ARGV;
    $echo = 1;
}

if ($cmd eq "q")
{
    do_query();
}
elsif ($cmd eq "retrieve")
{
    retrieve_doc();
}
elsif ($cmd eq "retrievename")
{
    retrieve_doc_name();
}
elsif ($cmd eq "comments")
{
    retrieve_comments();
}
elsif ($cmd eq "exam")
{
    do_exam();
}
elsif ($cmd eq "items")
{
    do_items();
}
elsif ($cmd eq "rollback")
{
    do_rollback();
}
elsif ($cmd eq "participation")
{
    do_participation();
}
elsif ($cmd eq "enter")
{
    do_enter();
}
elsif ($cmd eq "submit")
{
    do_submit();
}
elsif ($cmd eq "unsubmit")
{
    do_unsubmit();
}
elsif ($cmd eq "check")
{
    do_check();
}
elsif ($cmd eq "grades")
{
    do_grades();
}
elsif ($cmd eq "groupnum")
{
    retrieve_groupnum();
}
elsif ($cmd eq "apikey")
{
    retrieve_apikey();
}
elsif ($cmd eq "dbinfo")
{
    retrieve_dbinfo();
}
elsif ($cmd eq "attendstatus")
{
    retrieve_attendance();
}
elsif ($cmd eq "request")
{
    do_labrequest();
}
elsif ($cmd eq "contest")
{
    do_contest();
}
elsif ($cmd eq "inclass")
{
    do_inclass(0);
}
elsif ($cmd eq "inclassretract")
{
    do_inclass(1);
}
elsif ($cmd eq "seats")
{
    do_seats();
}
elsif ($cmd eq "here")
{
    do_here();
}
elsif ($cmd eq "current")
{
    do_current();
}
elsif ($cmd eq "status")
{
    do_status();
}
else
{
    print "Unknown command '$cmd'\n"; exit;
}

sub do_items
{
    if (@ARGV[0] eq "-r")
    {
	shift @ARGV;
	print "Resetting...\n";
    
	if (!$debug)
	{
	    runquery ("delete from sequence");    
	    runquery ("delete from choices");
	    runquery ("delete from items");
	    runquery ("delete from itemgroups");
	    print "Reset.\n";
	}
    }

    my ($input) = @ARGV;
    
    if (!(-e $input)) { print "No such input file '$input'.\n"; exit; }

    runquery ("start transaction");

    $/ = ";;\n";
    open (IN, "< $input") || die $!;
    while (<IN>)
    {
	my $hasgroup=0;
	$_ =~ s/;;\n$//;
	my ($main, $sub) = split (/::\n/);
	if ($sub ne "") { $hasgroup=1; }
	my ($header, @options) = split (/\n/, $main);
	if ($header eq "\%\%") { last; }
	
	my $type, $multallowed=0;
	my @range;
	my ($item_id, $prompt, $other) = split (/\t/, $header);
	if ($other eq "multi") { $type = "M"; $multallowed=1; }
	elsif ($other eq "short") { $type = "C"; }
	elsif ($other eq "long") { $type = "L"; }
	else { $type = "M"; $multallowed=0 }

	if ($item_id =~ /^\+/)
	{
	    parse_assessment($main);
	    next;
	}


	runquery ("replace into items values ('$item_id', '$prompt', '$type', $multallowed, $hasgroup)");
	  
	if ($other =~ /(\d+)-(\d+)/)
	{
	    if (@options)
	    {
		print "** $prompt specifies number range and options!!\n";
		exit;
	    }
	    for ($i = $1; $i <= $2; $i++)
	    {
		push (@range, $i);
		# print "\t('$item_id', '$i', '$i')\n";
		runquery ("replace into choices values ('$item_id', '$i', '$i')");
	    }
	}
	else
	{
	    foreach $o (@options)
	    {
		my ($blank, $choice_code, $answer) = split (/\t/, $o);
		# print "\t('$item_id', '$answer', '$choice_code')\n";
		runquery ("replace into choices values ('$item_id', '$answer', '$choice_code')");
	    }
	}
	
	if ($hasgroup)
	{
	    my (@subitems) = split (/\n/, $sub);
	    foreach $s (@subitems)
	    {
		my ($sub_id, $sub_prompt) = split (/\t/, $s);
		# print "\t\t('$item_id', '$sub_id', '$sub_prompt')\n";
		runquery ("replace into itemgroups values ('$item_id', '$sub_id', '$sub_prompt')");
	    }
	}

    }

    my $seq = querysingle("select max(seq) from sequence");
    my $prereq, $prereq_name, $cond;

    $/ = "\n";

    while (<IN>)
    {
	chop;
	if ($_ !~ /^\t/)
	{
	    ($prereq, $prereq_name) = split (/\t/, $_);
	    $cond = "";
	    if ($prereq =~ /:/)
	    {
		($cond, $prereq) = split (/:/, $prereq);
		
	    }
	    if ($prereq_name ne "") 
	    {
		runquery ("replace into prereqs values ('$prereq', '$prereq_name')");
	    }
	}
	else
	{
	    my ($blank, $item_id) = split (/\t/);
	    $seq++;
	    runquery ("insert into sequence values ('$item_id', '$prereq', '$cond', $seq)");
	}
    }
    runquery ("commit");
}

sub parse_assessment()
{
    my ($content) = @_;
    
    my $type;

    if (my ($item_id, $prompt, $dummy, $options) = ($content =~ /^\+(\w+)\n((.|\n)+?)\n--\n((.|\n)+)/))
    {
	$prompt =~ s/\'/\\\'/g;

	if ($options !~ /^(( *)|(\#\#))\n/)
	{
	    runquery ("replace into items values('$item_id', '$prompt', 'AM', 0, 0)");

	    my (@ans) = split (/\n\.\n/, $options);
	    foreach $a (@ans)
	    {
		my ($code, $answer) = split (/:\n/, $a);
		runquery ("insert into choices values ('$item_id', '$answer', '$code')");
	    }
	}
	else
	{
	    if ($options =~ /^\#\#/)
	    {
		runquery ("replace into items values('$item_id', '$prompt', 'AG', 0, 0)");
	    }
	    else
	    {
		runquery ("replace into items values('$item_id', '$prompt', 'AL', 0, 0)");
	    }
	}
    }
    else
    {
	print "*** ERROR: Bad assessment item spec:\n$content\n*** END ERROR\n";
    }

}


sub do_enter()
{
    # $debug = 1;
    # $echo = 1;

    my $long;
    my ($id, @entries) = @ARGV;
    my $item, $content, $seq;

    while (@entries > 0)
    {
	($item, $content, $seq) = splice (@entries, 0, 3);

	my $sub = "NULL";
    
	$long = 0;

    	$content =~ s/\+/ /g;
	if ($item =~ /^\*/)
	{
	    $item = substr($item, 1);
	    $long = 1;
	}
	
	if ($item =~ /\+/) 
	{
	    ($item, $sub) = split (/\+/, $item);
	    $sub = "'$sub'";
	}
	
	if ($long)
	{
	    runquery("insert into responses (user_id, item_id, sub_id, response_text, seq)" .
		     " values ($id, '$item', $sub, '$content', $seq)");
	}
	else
	{
	    runquery("insert into responses (user_id, item_id, sub_id, response_choice, seq)" .
		     " values ($id, '$item', $sub, '$content', $seq)");
	}
    }
}

sub do_query
{
    my ($query) = @ARGV;
    my $handle = runquery ($query);
    if ($query =~ /^select/)
    {
	while (my @result = $handle->fetchrow_array())
	{
	    my $line = join ("\t", @result);
	    print "$line\n";
	}
    }
}

sub retrieve_doc_name()
{
    my ($t_course, $t_assn, $t_user) = @ARGV;
    my ($course, $assn, $user) = untaint($t_course, $t_assn, $t_user);
    
    $user = "w" . $user;

    my ($t_file) = querysingle ("select doc from assignments where course='$course' "
			      . "and short_name='$assn'");
    my ($file) = untaint ($t_file);

    my (@info) = sub_info ($course, $assn, $user, 1222207000);

    my ($path) = untaint($info[5]);

    my $subnum = 0;

    if ($path =~ /w\d{8}-(\d+)/)
    {
	$subnum = $1;
    }

    print "$user-$assn-submission$subnum-$file\n";
}

sub retrieve_doc()
{
    my ($t_course, $t_assn, $t_user, $t_direct) = @ARGV;
    my ($course, $assn, $user, $direct) = untaint($t_course, $t_assn, $t_user, $t_direct);
    
    $user = "w" . $user;

    my ($t_file) = querysingle ("select doc from assignments where course='$course' "
			      . "and short_name='$assn'");
    my ($file) = untaint ($t_file);

    my (@info) = sub_info ($course, $assn, $user, 1222207000);

    my ($path) = untaint($info[5]);

    opendir (DIR, "$path") || die "$path: $!";
    my ($docpath) = untaint("$path/$file");

    if (-T $docpath)
    {
	open (IN, "< $docpath") || die "nosir: $!";
	undef $/;
	while (<IN>) { print; }
    }
    elsif ($docpath =~ /\.zip$/ && $direct ne "1")
    {
	undef %ENV;
	print "$file has size " . make_size_string(-s $docpath) 
	    . " and contains these files:\n\n";
	my @zipcontents = split (/\n/, `/usr/bin/unzip -l $docpath`);
	my $i = 0;	
	for $f (@zipcontents)
	{
	    if ($f =~ /^\s*\d+\s+[0-9\-]+\s+[0-9:]+\s+(.+)/)
	    {
		$i++;
		print "      ($i) $1\n";
	    }
	}
    }
    elsif ($direct ne "1")
    {
	my $sizedesc = make_size_string(-s $docpath);
	print "This is a binary file named '$file'. Its size is $sizedesc.\nUnfortunately I have no way to display it directly.";
    }
    else
    {
	# binary and we want it directly dumped to stdout
	undef $/;
	open (FILE, "< $docpath") || die "nosir: $!";
	binmode FILE;
	my $all = <FILE>;
	print $all;
    }
}

sub retrieve_comments()
{
    my ($t_course, $t_assn, $t_user) = @ARGV;
    my ($course, $assn, $user) = untaint($t_course, $t_assn, $t_user);
    
    (my $assn_short = $assn) =~ s/ \(.+\)//;
    my ($contents) = querysingle ("select contents from comments where "
				  . "course='$course' and short_name='$assn' "
				  . "and ID=$user");

    $contents =~ s/\</&lt;/g;
    $contents =~ s/\>/&gt;/g;


    my ($long) = querysingle ("select long_name from assignments where course='$course' and short_name='$assn'");
    my ($t_fname, $t_lname) = queryrow ("select fname, lname from roster where ID=$user");
    my ($fname, $lname) = untaint ($t_fname, $t_lname);

    $user = "w" . $user;

    $contents =~ s/\n/<br \/>\n/g;

    # print "<p><b>\L\u$fname\E \L\u$lname\E<br />\n";
    if ($assn =~ /quiz/i)
    {
	print "Comments for $long</b></p>\n";	
    }
    else
    {
	print "Comments for \U$assn\E ($long)</b></p>\n";
    }

    my $comments = "<hr />\n$contents\n";
    
    if ($comments =~ /<img src/)
    {
	$comments =~ s/<img src/<\/pre>\n<img src/;
	$comments =~ s/<\/pre>$//;
    }

    print $comments;
}

sub do_rollback()
{
    my ($seq) = @ARGV;

    $q = runquery ("select item_id from sequence where seq > $seq");

    my @items;

    while (my ($item_id) = $q->fetchrow_array())
    {
	push (@items, $item_id);
    }

    runquery ("delete from sequence where seq > $seq");

    foreach $i (@items)
    {
	print "Removing $i\n";
	runquery ("delete from responses where item_id='$i' and seq > $seq");
	if (querysingle("select count(*) from responses where item_id='$i'") == 0)
	    {
		runquery ("delete from choices where item_id='$i'");
		runquery ("delete from itemgroups where item_id='$i'");
	    }
	# runquery ("delete from items where item_id='$i'");
    }


}

sub untaint
{
    my @out;
    foreach $var (@_)
    {
	$var =~ /(.*)/;
	my $clean = $1;
	push (@out, $clean);
    }
    return (@out);
}


sub do_submit()
{
    my ($t_course, $t_assignment, $t_user, $t_temp, $t_fname) = @ARGV;

    my ($course, $assignment, $user, $tempfile, $fname)
	= untaint ($t_course, $t_assignment, $t_user, $t_temp, $t_fname);

    $course =~ tr/[A-Z]/[a-z]/;
    $assignment =~ tr/[A-Z]/[a-z]/;

    my $upload_parent = "$root/$course/submissions/$assignment";
    my $subdir;
    if (!(-d "$upload_parent"))
    {
	print "No such parent dir '$upload_parent'\n";
	exit;
    }

    opendir (PARENT, "$upload_parent");
    my @existing = grep { /^($user)(-\d)?$/ } readdir (PARENT);
    my @nums = ( 0 );
    foreach $e (@existing)
    {
	$e =~ s/^.+?-(\d+)/\1/;
	push (@nums, $e);
    }
    my @ordered = sort { $a <=> $b } @existing;
    my $version = -1;
    foreach $o (@ordered)
    {
	if (!(-e "$upload_parent/$user-$o/.complete"))
	{
	    $version = $o;
	    last;
	}
    }
    if ($version == -1) { $version = $ordered[@ordered-1] + 1; }

    my $t_subdir = "$upload_parent/$user-$version";
    my ($subdir) = untaint ($t_subdir);

    if (!(-e "$subdir"))
    {
	if (!mkdir ("$subdir", 0700))
	{
	    print "Couldn't make directory '$subdir'\n";
	    exit;
	}
    }

    $ENV{'PATH'} = '/bin:/usr/bin';
    system ("cp $tempfile $subdir/$fname");
    system ("rm $tempfile");

    $now = time;

    open (COMPLETE, "> $subdir/.complete");
    print COMPLETE "$now\n";
    print "Ok\n";
}

sub do_unsubmit()
{
    my ($t_course, $t_assignment, $t_user,) = @ARGV;

    my ($course, $assignment, $user)
	= untaint ($t_course, $t_assignment, $t_user);

    $course =~ tr/[A-Z]/[a-z]/;
    $assignment =~ tr/[A-Z]/[a-z]/;

    my $upload_parent = "$root/$course/submissions/$assignment";
    my $subdir;
    if (!(-d "$upload_parent"))
    {
	print "No such parent dir '$upload_parent'\n";
	exit;
    }

    opendir (PARENT, "$upload_parent");
    my @existing = grep { /^($user)(-\d)?$/ } readdir (PARENT);
    my @nums = ( 0 );
    foreach $e (@existing)
    {
	$e =~ s/^.+?-(\d+)/\1/;
	push (@nums, $e);
    }
    my @ordered = sort { $a <=> $b } @existing;
    my $version = -1;
    foreach $o (@ordered)
    {
	if (!(-e "$upload_parent/$user-$o/.complete"))
	{
	    $version = $o;
	    last;
	}
    }
    if ($version == -1) { $version = $ordered[@ordered-1] + 1; }

    $version -= 1;

    my $t_subdir = "$upload_parent/$user-$version";
    my ($subdir) = untaint ($t_subdir);

    $ENV{'PATH'} = '/bin:/usr/bin';
    system ("rm -rf $subdir");
    print "OK";
}

# get info about a given submission
sub sub_info
{
    my ($course, $assignment, $user, $due) = @_;
    my ($parent) = untaint ("$root/$course/submissions/$assignment");
    my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime($due);

    $course =~ tr/[A-Z]/[a-z]/;

    my $status = "";
    my $notes = "";
    my $score = 0;
    my $latemins = 0;
    my $delta = 0;
    my $comments = 0;

    $uploadable = querysingle("select uploadable from assignments where course='$course' and short_name='$assignment'");
    my ($parent) = untaint ("$root/$course/submissions/$assignment");
    if (!$uploadable && !(-d $parent))
    {
	(my $id = $user) =~ s/^w//;

	my $result = querysingle ("select whensub from othersubs where course='$course' and short_name='$assignment' and ID=$id");
	if (!defined($result))
	{
	    $newest = "";
	}
	else
	{
	    $newest = ".";
	    $newest_when = $result;
	}
    }
    else
    {
	if (!(-d "$parent"))
	{
	    print "No such parent dir '$parent'\n";
	    exit;
	}

	$newest = "";
	$newest_when = 0;
	opendir (DIR, "$parent");
	@matches = grep { /^($user)(-\d)?$/ } readdir(DIR);
	foreach $m (sort @matches)
	{ 
	    if (-e "$parent/$m/.complete")
	    {
		open (COMP, "< $parent/$m/.complete") || die $!;
		chop ($stampline = <COMP>);
		my $mtime;
		if ($stampline =~ /(\d{1,10})/)
		{
		    $mtime = $1;
		}
		else
		{
		    my @STATS = stat ("$parent/$m/.complete");
		    $mtime = $STATS[9];
		}
		if ($newest eq "" || $mtime > $newest_when)
		{
		    $newest = "$parent/$m";
		    $newest_when = $mtime;
		}
	    }
	}
	
	if ($newest ne "")
	{
	    if (-e "$newest/.excused")
	    {
		$status = "Excused";
	    }
	    else
	    {
		if (-e "$newest/SCORE")
		{
		    open (SCORE, "< $newest/SCORE") || die $!;
		    chop ($score = <SCORE>);
		}
		else
		{
		    $score = -1;
		}
		if (-e "$newest/COMMENTS")
		{
		    $comments = "$newest/COMMENTS";
		    $comments = 1;
		}
	    }	
	 }
    }   
	
    if ($newest ne "")
    {
	$delta = $due - $newest_when;
	$status = "<span class='report'>Submitted</span><br />(<a href='retrieve.php?a=$assignment' target='prior'>retrieve copy</a>)";
	if ($delta < 0)
	{
	    $notes = parsetime(-$delta) . "<br/><span class='warning'>LATE</span>";
	    $latemins = - $delta / 60;
	}
	else
	{
	    $delta = time() - $newest_when;
	    $notes = "On time";
	}
    }
    else
    {
	$delta = $due - time();
	my $phrase = "remaining";
	if ($delta < 0)
	{
	    $delta = - $delta;
	    $phrase = "<span class='warning'>PAST DUE</span>";
	}
	$status = "<span class='error'>NOT submitted</span>";
	$notes = parsetime($delta) . "<br/>$phrase";
    }
    
    return ($status, $notes, $score, $latemins, $comments, $newest);
}


sub do_check 
{
    my @days = ('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    my ($t_course, $t_assignment, $t_user, $t_due, $t_opt) = @ARGV;
    my ($course, $assignment, $user, $due, $opt) 
	= untaint($t_course, $t_assignment, $t_user, $t_due, $t_opt);
   
    my $includetime = 1;

    my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime($due);
    if ($hour == 0 && $min == 0 && $sec == 0) { $includetime = 0; }
    my $ampm = "am";
    if ($hour >= 12) { $ampm = "pm";}
    if ($hour > 12) { $hour = $hour - 12;}
    if ($hour == 0) { $hour = 12; }
    my $duetext;
    
    if ($includetime)
    { $duetext = sprintf ("%s %d/%02d<br />%d:%02d", $days[$wday], $mon+1, $mday, $hour, $min) . "$ampm"; }
    else 
    { $duetext = sprintf ("%s %d/%02d", $days[$wday], $mon+1, $mday); }
    
    my $status, $notes, @other;
    if ($opt != -1)
    {
	($status, $notes, @other) = sub_info($course, $assignment, $user, $due);
    }

    if ($status eq "Excused") { $notes = "Excused"; }

    if ($opt != 0)
    {
	if ($status =~ /NOT/)
	{$status = " "; }
	$notes = "  ";
    }

    if ($t_due == 0)
    {
	print "<td>---</td><td>$status</td><td>---</td>";
    }
    else
    {
	print "<td>$duetext</td><td>$status</td><td>$notes</td>";
    }
}

sub parsetime()
{
    my ($interval) = @_;
    my $text = "";

    my $totmin = int ($interval) / 60;
    
    my $days = int($totmin / 60 / 24);
    my $hours = int($totmin / 60) % 24;
    my $mins = $totmin % 60;

    if ($days > 0) { $text .= "${days}d "; }
    if ($hours > 0 || $days > 0) { $text .= "${hours}h "; }
    $text .= "${mins}m";
    return ($text);
}

sub do_exam
{
    $ENV{'PATH'} = "/usr/local/bin:/usr/bin:/bin"; 
    my $stordir = "storage";
    my ($t_user, $t_what) = @ARGV;
    my ($user, $what) = untaint($t_user, $t_what);

    my ($db) = untaint("$stordir/${what}db");

    dbmopen(%WHEN, "$db", 0666) || die $!;
    my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time());

    my $whendl = timestr ($year, $mon, $mday, $hour, $min);
    my $fromdb = $WHEN{$user};

    if ($fromdb eq "")
    {
	$WHEN{$user} = $whendl;
    }
    else
    {
	$whendl = $fromdb;
    }

    dbmclose (%WHEN);

    ($intex) = untaint("remote/$what.tex");
    ($temp) = untaint("${what}-${user}.tex");
    ($pdf) = untaint("${what}-${user}.pdf");
    ($zip) = untaint("${what}-${user}.zip");

    open (IN, "< $intex") || die $!;
    open (OUT, "> $stordir/$temp") || die $!;
    
    undef $/;
    
    $full = <IN>;
    create_stamp($user, $whendl);
    $full =~ s/% REMOTE STUDENT STAMP %/$stamp/;
    
    close(IN);
    
    print OUT $full;
    close (OUT);

    system ("rm -rf $stordir/${what}-${user}");
    system ("cd $stordir; rm -f ${what}-${user}.log ${what}-${user}.aux");
    umask(0);
    mkdir ("$stordir/${what}-${user}", 0777);
    system ("cd $stordir; pdflatex -jobname=${what}-${user} $temp > /dev/null");
    system ("cd $stordir; rm -f ${what}-${user}.log ${what}-${user}.aux");
    system ("cp remote/$what/* $stordir/${what}-${user}");
    system ("cd $stordir; mv $pdf ${what}-${user}/${what}.pdf");
    system ("cd $stordir; zip -q $zip ${what}-${user}/*");
    system ("rm -rf $stordir/${what}-${user}");
    system ("cd $stordir; rm -f $temp $pdf");
    system ("cat $stordir/$zip");
    system ("rm $stordir/$zip");

# PDF ONLY    
#    system ("cd $stordir; rm -f ${what}-${user}.log ${what}-${user}.aux");
#    system ("cd $stordir; pdflatex -jobname=${what}-${user} $temp > lastlog");
#    system ("cd $stordir; rm -f ${what}-${user}.log ${what}-${user}.aux");
#    system ("cat $stordir/$pdf");
#    system ("cd $stordir; rm -f $temp $pdf");
}

sub create_stamp
{
    my ($wid, $when) = @_;
    $stamp = sprintf <<"EOF";
    
    \\centerline{
	\\fbox{
	    \\begin{tabular}{ll}
	    W ID:& {\\cc $wid}\\\\
		Date/Time downloaded:& {\\cc $when}\\\\
	    \\end{tabular}
	}
    }
EOF
}

sub timestr
{
    my ($year, $month, $day, $hour, $minute) = @_;
    $year += 1900;
    my @abbr = qw( Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec );
    my $str = "$abbr[$month] $day $year, ";
    my $ampm = "am";
    if ($hour >= 12) { $ampm = "pm"; }
    if ($hour > 12) { $hour -= 12; }
    if ($hour == 0) { $hour = 12; }
    my $timestr = sprintf ("%d:%02d", $hour, $minute) . $ampm;
    $str .= $timestr;
    return ($str);
}

sub do_grades
{
    my ($t_course, $t_id) = @ARGV;
    my ($course, $id) = untaint ($t_course, $t_id);
    my $report = querysingle ("select report from reports where course='$course' and ID='$id'");
    print $report;
}

sub retrieve_groupnum()
{
    my ($t_course, $t_id) = @ARGV;
    my ($course, $id) = untaint ($t_course, $t_id);
    my $groupnum = "[No group number]";
    my $result = querysingle("select gnum from groups where id = $id and course='$course'");
    if ($result != undef)
    {
	$groupnum = $result;
    }

    print "$groupnum";
}

sub retrieve_apikey()
{
    my ($t_id) = @ARGV;
    my ($id) = untaint ($t_id);
    my $q = runquery ("select api_key from user_keys where id = $id");

    my ($apikey) = $q->fetchrow_array();
    print "$apikey\n";
}


sub retrieve_dbinfo()
{
    my ($t_id) = @ARGV;
    my ($id) = untaint ($t_id);
    my $q = runquery ("select * from db_info where wnum = $id");

    my ($skip, $password, $uname, $dbname) = $q->fetchrow_array();
    print "Server address: <b>scinett.org</b><br />\n";
    print "Username: <b>$uname</b><br />\n";
    print "Password: <b>$password</b><br />\n";
    print "Database name: <b>$dbname</b><br />\n";

}


sub retrieve_attendance()
{
    my ($t_course, $t_section, $t_id) = @ARGV;
    my ($course, $section, $id) = untaint ($t_course, $t_section, $t_id);
    my $result = querysingle("select phrase from phrases where course='$course' and section='$section'");
    if (defined($result))
    {
	$result =~ s/\b(\w)/\u\1/g;
	my $when = querysingle("select time_format(whenhere, '%l:%i %p') from attendance where day=date(now()) and ID=$id and course='$course'");
	if (defined($when))
	{
	    print "Present as of \L$when\E <span class='attendphrase'>(\"$result\")</span>";
	}
	else
	{
	    print "Absent";
	}
    }
    else
    {
	print "[N/A - ignore this.]";
    }

}
sub do_labrequest()
{
    my ($id, $details) = @ARGV;

    if ($details ne " - ")
    {
	my $q = runquery ("select fname, lname from roster where ID=$id");
	my ($fname, $lname) = $q->fetchrow_array();
	
	runquery ("insert into requests values ('$fname $lname', now(), 'request', '$details')");
	
	my ($sec,$min,$hour,$mday,$mon,$year,$wday,
	    $yday,$isdst)=localtime(time);
	printf "%02d:%02d:%02d\n", $hour,$min,$sec;
    }
}

sub do_contest()
{
    my ($team, $answer) = @ARGV;
    my $now = Time::HiRes::time();
    my $already = querysingle ("select name from contest where name='$team'");
    if ($already eq $team)
    {
	runquery ("replace into contest values ('$team', $now, '[FAIL]')");
    }
    else
    {
	runquery ("replace into contest values ('$team', $now, '$answer')");
    }
}


sub do_participation()
{
    ($id) = @ARGV;
    my $q = runquery ("select day, deduction, notes from participation where id=$id");
    my $lastday = "";
    while (($day, $deduct, $notes) = $q->fetchrow_array())
    {
	$deduct = -$deduct;
	if ($deduct > 0) { $deduct = "+$deduct"; }
	if ($lastday ne $day)
	{
	    print "$day<br />\n";
	}
	print "($deduct) $notes<br />\n";
	$lastday = $day;
    }
}

sub do_seats()
{

    %ROWLEN = ( 'A', 6, 'B', 6, 'C', 8, 'D', 8, 'E', 8, 'F', 8 );

    ($course) = @ARGV;

    foreach $row (sort keys %ROWLEN)
    {
	my $len = $ROWLEN{$row};
	for ($i = 0; $i < $len; $i++)
	{
	    push (@SEATS, "${row}-$i");
	}
    }
    
    $q = runquery ("select fname, lname from roster where course='$course' and lname != 'Schatz'");
    
    while (my ($fname, $lname) = $q->fetchrow_array())
    {
	push (@STUDENTS, "$lname, " . substr($fname, 0, 1));
    }
    
    while (@STUDENTS > 0)
    {
	my $r = int (rand(@STUDENTS));
	my ($who) = splice (@STUDENTS, $r, 1);
	my ($where) = shift (@SEATS);
	$SEATMAP{$who} = $where;
    }
    
    print <<EOF;
<center>
<table style="font-size: 1.5em;">
EOF
my $colsize = int ((keys (%SEATMAP) + 1) / 2);

    @list = sort keys %SEATMAP;
    for ($row = 0; $row < $colsize; $row++)
    {
	print "<tr>";
	for ($col = 0; $col < 2; $col++)
	{
	    print "<td>", $list[$row+$col*$colsize], "</td><td width='20%'>", $SEATMAP{$list[$row+$col*$colsize]}, "</td><td>\n";
	}    
	print "</tr>";
    }
    
print <<EOF;
</table>
</center>
EOF

}

sub do_here
{
    my ($id, $num, $word) = @ARGV;

    my $q = runquery ("select * from todayinfo");
    my ($thenum, $theword) = $q->fetchrow_array();

    if ($num == $thenum && $word eq $theword)
    {
	runquery ("replace into daily values ($id, curdate(), 0, 0)");
	print "yes";
    }
    else
    {
	print "no";
    }

}

sub do_current
{
    my $result = querysingle ("select item from current limit 1;");
    $result =~ s/\</&lt;/g;
    $result =~ s/\>/&gt;/g;

    if ($result eq "") { $result = "(None - please wait...)" }
    print "$result";
}


sub do_status
{
    my ($id) = @ARGV;
    my $result = querysingle ("select count(ID) from inclass where ID=$id");
    if ($result == 0) { print "Not yet submitted." }
    else { print "ALREADY SUBMITTED - additional submissions will have no effect." }
}

sub do_inclass()
{
    my ($retracting) = @_;
    my ($id, $answer) = @ARGV;
    my $timegone = querysingle ("select content from inclass where ID=0");    
    my $already = "";

    if ($retracting == 1)
    {
	runquery("Delete from inclass where ID=$id");
	return;
    }
    print "Checking id $id\n";
    if ($id != -1)
    {
	$already = querysingle ("select content from inclass where ID=$id");
	print "temp result: $already\n";
    }
    if ($already ne "")
    {
	print "already";
	return;
    }
    if ($timegone eq "yes")
    {
	runquery ("insert ignore into inclass_late (ID, timestamp, content) values ($id, now(), '$answer')");
	print "late";
    }
    else
    {
	runquery ("insert ignore into inclass (ID, timestamp, content) values ($id, now(), '$answer')");
	print "ok";
    }
    
}


sub make_size_string
{
    my ($bytes) = @_;
    my $desc = "";
    
    if ($bytes < 1024)
    {
	$desc = "$bytes bytes";
    }
    elsif ($bytes < (1024*1024))
    {
	$desc = sprintf("%.1f KB", $bytes / 1024.0);
    }
    else
    {
	$desc  = sprintf ("%.1f MB", $bytes / (1024*1024.0));
    }
    return ($desc);
}

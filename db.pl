use DBI;

$connected=0;
$db_name="DATABASE_NAME";
$db_host="DATABASE_HOST";
$db_user_name = 'USERNAME';
$db_password = 'PASSWORD';

sub connectdb
{
    if ($connected) { return; }
    # print "Connecting...\n";
    $dsn = "DBI:mysql:$db_name:$db_host";
    $dbh = DBI->connect($dsn, $db_user_name, $db_password);
    # print "Ok.\n";
    $connected = 1;
}

sub runquery
{
    my ($sql) = @_;

    if ($echo == 1)
    {
	print "$sql\n";
    }

    if (($sql !~ /^select/) && $debug)
    {
	my $logfile;
	if (-e "exec/log") { $logfile = "exec/log"; }
	else { $logfile = "log"; }
	open (LOG, ">> $logfile") || die $!;
	print LOG "$sql\n";
	return;
    }

    connectdb();

    my $sth = $dbh->prepare($sql);
    if (!$sth->execute())
    {
	$sth = $dbh->prepare("rollback");
	$sth->execute();
	die "!bad query";
    }
    return ($sth);
}

sub querylist
{
    my $sth = runquery($_[0]);
    my @result;
    
    while (my @response = $sth->fetchrow_array())
    {
	push (@result, @response);
    }

    return (@result);
}

sub querysingle
{
    my $sth = runquery($_[0]);
    
    if (my @response = $sth->fetchrow_array())
    {
	return $response[0];
    }
    else
    {
	return undef;
    }
}

sub queryrow
{
    my $sth = runquery($_[0]);
    
    return ($sth->fetchrow_array());
}

1;

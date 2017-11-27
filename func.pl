#!/usr/bin/perl

@NAMES = ('Foo', 'Bar', 'Happy', 'Sleepy', 'Grumpy', 'Vanity', 'Papa',
	  'Baby', 'Hefty', 'Brainy', 'Buttercup', 'Westley', 'Inigo', 'Fezzick',
	  'Vizzini', 'Humperdink', 'Harry', 'Ron', 'Hermione', 'Sirius',
	  'Snape', 'Ginny' );

@numtypes = ('whole number', 'weight', 'volume', 'price', 'quantity of eggs');

@strtypes = ('word', 'name', 'sentence');

@numcalcs = ('the square root of X', 'the square of X',
	     'whether X is a multiple of 3');

@twonumcalcs = ('the sum of X', 'the average of X', 'the largest of X',
		'the smallest of X', 'the difference between X');

@strcalcs = ('the length of X', 'the first letter of X', 'the first half of X',
	     'whether X is longer than 3 letters');

@twostrcalcs = ('the total length of X', 'whether X are different lengths', 'the concatenation of X');

@dualcalcs = ('whether that S is longer than that number', 'that number of copies of that S',
	      'that number of letters from the beginning of that S', 'that number plus the length of that S');

$text = "";
	      
$nums = 0;
$strs = 0;
$ingred = "";
$ingredrep = "";

$k = 1 + int(rand(2));

$thatthose = "that";

if (int(rand(4)) == 0)
{
    $dual = 1;
    $s = pickrandom(@strtypes);
    $ingred = "a whole number and a $s";
    $result = pickrandom(@dualcalcs);
    $result =~ s/S/$s/;
}
else
{
    if (flip() == 1)
    {
	$nums = $k;
    }
    else
    {
	$strs = $k;
    }
    
    if ($nums == 1)
    {
	$n = pickrandom(@numtypes);
	$ingred = "a $n";
	$ingredrep = "$n";
	$result = pickrandom(@numcalcs);
    }
    elsif ($strs == 1)
    {
	$s = pickrandom(@strtypes);
	$ingred = "a $s";
	$ingredrep = "$s";
	$result = pickrandom(@strcalcs);
    }
    elsif ($strs == 2)
    {
	$s1 = pickrandom(@strtypes);
	$s2 = pickrandom(@strtypes);
	$result = pickrandom(@twostrcalcs);
	if ($s1 ne $s2)
	{
	    $ingred = "a $s1 and a $s2";
	    $ingredrep = "$s1 and $s2";
	}
	else
	{
	    $ingred = "two ${s1}s";
	    $ingredrep = "two ${s1}s";
	    $thatthose = "those";
	}
	
    }
    elsif ($nums == 2)
    {
	$n1 = pickrandom(@numtypes);
	$n2 = pickrandom(@numtypes);
	$result = pickrandom(@twonumcalcs);
	if ($n1 ne $n2)
	{
	    $ingred = "a $n1 and a $n2";
	    $ingredrep = "$n1 and $n2";
	}
	else
	{
	    $ingred = "two ${n1}s";
	    $ingredrep = "two ${n1}s";
	    $thatthose = "those";
	}
    }
}

$isVoid = int(rand(2));


if ($isVoid)
{
    $text = "prints $result";
}
else
{
    if (flip())
    {
	$text = "calculates $result";
    }
    else
    {
	$text = "returns $result";
    }
}


if (flip())
{
    $text = "asks the user for $ingred, then " . $text;
    $ingredrep =~ s/ and/ and that/;
    $text =~ s/(X)/$thatthose $ingredrep/;
}
else
{
    if (flip())
    {
	$text = "takes $ingred, and " . $text;
	if ($dual)
	{
	    
	}
	else
	{
	    $ingredrep =~ s/ and/ and that/;
	    $text =~ s/(X)/$thatthose $ingredrep/;
	}
    }
    else
    {
	if ($dual)
	{
	    $text =~ s/that/the given/g;
	}
	else
	{
	    $ingredrep =~ s/ and/ and given/;
	    $text =~ s/(X)/the given $ingredrep/;
	}
    }
}

$fname = pickrandom(@NAMES);

print "The function <b>$fname</b> $text.\n";


sub flip
{
    my $r = int(rand(2));
    return ($r);
}

sub pickrandom
{
    my (@options) = @_;
    my $r = int (rand(@options + 0));
    return ($options[$r]);
}


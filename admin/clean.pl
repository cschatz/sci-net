#!/usr/bin/perl

require "./Tokenizer.pm";

($text) = @ARGV;

my $st = String::Tokenizer->new($text, "+-/*%,:;=()<>{}", String::Tokenizer->RETAIN_WHITESPACE);
my @tokens = $st->getTokens();
my $str = "";
foreach $tok (@tokens) 
{ 
    # if ($tok =~ /[+-\/*%,:=<>]/) { $str .= "$tok"; }
    # elsif ($tok eq ";" ) {$str .= "; "; }
    # else { $str .= " $tok "; }
    $str .= "$tok";
}
$str =~ s/  +/ /g;
$str =~ s/^ //;
$str =~ s/ $//;
$str =~ s/\( /\(/g;
$str =~ s/ \)/\)/g;
# $str =~ s/,([^ ])/, \1/g;
$str =~ s/\n//g;


print "$str\n";

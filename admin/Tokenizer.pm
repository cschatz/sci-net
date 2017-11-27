package String::Tokenizer;

use strict;
use warnings;

our $VERSION = '0.05';

use constant RETAIN_WHITESPACE => 1;
use constant IGNORE_WHITESPACE => 0;

### constructor

sub new {
    my ($_class, @args) = @_;
    my $class = ref($_class) || $_class;
    my $string_tokenizer = {
	tokens => [],
        delimiter => undef,
        handle_whitespace => IGNORE_WHITESPACE
    };
    bless($string_tokenizer, $class);
    $string_tokenizer->tokenize(@args) if @args;
    return $string_tokenizer;
}

### methods

sub setDelimiter {
    my ($self, $delimiter) = @_;
    my $delimiter_reg_exp = join "\|" => map { s/(\W)/\\$1/g; $_ } split // => $delimiter;
    $self->{delimiter} = qr/$delimiter_reg_exp/;
}

sub handleWhitespace {
    my ($self, $value) = @_;
    $self->{handle_whitespace} = $value;
}

sub tokenize {
    my ($self, $string, $delimiter, $handle_whitespace) = @_;
    # if we have a delimiter passed in then use it
    $self->setDelimiter($delimiter)             if defined $delimiter;
    # if we are asking about whitespace then handle it
    $self->handleWhitespace($handle_whitespace) if defined $handle_whitespace;
    # if the two above are not handled, then the object will use
    # the values set already. 
    # split everything by whitespace no matter what
    # (possible multiple occurances of white space too) 
    my @tokens;
    if ($self->{handle_whitespace}) {
        @tokens = split /(\s+)/ => $string;
    }
    else {
        @tokens = split /\s+/ => $string;    
    }
    if ($self->{delimiter}) {
	# create the delimiter reg-ex
	# escape all non-alpha-numeric 
	# characters, just to be safe
	my $delimiter = $self->{delimiter};
	# loop through the tokens
	@tokens = map {
	    # if the token contains a delimiter then ...
	    if (/$delimiter/) {
		my ($token, @_tokens);
		# split the token up into characters
		# and the loop through all the characters
		foreach my $char (split //) {
		    # if the character is a delimiter
		    if ($char =~ /^$delimiter$/) {
			# and we already have a token in the works
			if (defined($token) && $token =~ /^.*$/) {
			    # add the token to the 
			    # temp tokens list
			    push @_tokens => $token;
			}
			# and then push our delimiter character
			# onto the temp tokens list
			push @_tokens => $char;
			# now we need to undefine our token
			$token = undef;
		    }
		    # if the character is not a delimiter then
		    else {
			# check to make sure the token is defined
			$token = "" unless defined $token;
			# and then add the chracter to it
			$token .= $char;
		    }
		}
		# now push any remaining token onto 
		# the temp tokens list
		push @_tokens => $token if defined $token;
		# and return tokens
		@_tokens;
	    }
	    # if our token does not have 
	    # the delimiter in it
	    else {
		# just return it
		$_
	    }
	} @tokens;
    }
    $self->{tokens} = \@tokens;
}

sub getTokens {
    my ($self) = @_;
    return wantarray ?
	@{$self->{tokens}}
    :
	$self->{tokens};
}

sub iterator {
    my ($self) = @_;
    # returns a copy of the array
    return String::Tokenizer::Iterator->new($self->{tokens});
}

package String::Tokenizer::Iterator;

use strict;
use warnings;

sub new {
    ((caller())[0] eq "String::Tokenizer") 
        || die "Insufficient Access Priviledges : Only String::Tokenizer can create String::Tokenizer::Iterator instances";
    my ($_class, $tokens) = @_;
    my $class = ref($_class) || $_class;
    my $iterator = {
        tokens => $tokens,
        index => 0
    };
    bless($iterator, $class);
    return $iterator;
}

sub reset {
    my ($self) = @_;
    $self->{index} = 0;
}

sub hasNextToken {
    my ($self) = @_;
    return ($self->{index} < scalar @{$self->{tokens}}) ? 1 : 0;    
}

sub hasPrevToken {
    my ($self) = @_;
    return ($self->{index} > 0);  
}

sub nextToken {
    my ($self) = @_;
    return undef if ($self->{index} >= scalar @{$self->{tokens}});    
    return $self->{tokens}->[$self->{index}++];
}

sub prevToken {
    my ($self) = @_;
    return undef if ($self->{index} <= 0);    
    return $self->{tokens}->[--$self->{index}];
}

sub currentToken {
    my ($self) = @_;
    return $self->{tokens}->[$self->{index} - 1];      
}

sub lookAheadToken {
    my ($self) = @_;
    return undef if (  $self->{index} <= 0 
		       || $self->{index} >= scalar @{$self->{tokens}});
    return $self->{tokens}->[$self->{index}];    
}

sub collectTokensUntil {
    my ($self, $token_to_match) = @_;
    # if this matches our current token ...
    # then we just return nothing as there
    # is nothing to accumulate
    if ($self->lookAheadToken() eq $token_to_match) {
        # then just advance it one
        $self->nextToken();
        # and return nothing
        return;        
    }
    
    # if it doesnt match our current token then, ...
    my @collection;    
    # store the index we start at
    my $old_index = $self->{index}; 
    my $matched;       
    # loop through the tokens
    while ($self->hasNextToken()) {
        my $token = $self->nextToken();
        if ($token ne $token_to_match) {       
            push @collection => $token;
        }
        else {
            $matched++;
            last;
        }
    }
    unless ($matched) {
        # reset back to where we started, and ... 
        $self->{index} = $old_index;
        # and return nothing
        return;
    }
    # and return our collection
    return @collection;
}


sub skipTokensUntil {
    my ($self, $token_to_match) = @_;
    # if this matches our current token ...
    if ($self->lookAheadToken() eq $token_to_match) {
        # then just advance it one
        $self->nextToken();
        # and return success
        return 1;
    }
    # if it doesnt match our current token then, ...
    # store the index we start at
    my $old_index = $self->{index};
    # and loop through the tokens
    while ($self->hasNextToken()) {
        # return success if we match our token
        return 1 if ($self->nextToken() eq $token_to_match);
    }
    # otherwise we didnt match, and should
    # reset back to where we started, and ... 
    $self->{index} = $old_index;
    # return failure 
    return 0;
}

sub skipTokenIfWhitespace {
    my ($self) = @_;
    $self->{index}++ if $self->lookAheadToken() =~ /^\s+$/;
}

sub skipTokens {
    my ($self, $num_token_to_skip) = @_;
    $num_token_to_skip ||= 1;
    $self->{index} += $num_token_to_skip;
}

*skipToken = \&skipTokens;

1;

__END__

=head1 NAME

    String::Tokenizer - A simple string tokenizer.

=head1 SYNOPSIS

    use String::Tokenizer;
  
  # create the tokenizer and tokenize input
my $tokenizer = String::Tokenizer->new("((5+5) * 10)", '+*()');
  
  # create tokenizer
my $tokenizer = String::Tokenizer->new();
  # ... then tokenize the string
$tokenizer->tokenize("((5 + 5) - 10)", '()');
  
  # will print '(, (, 5, +, 5, ), -, 10, )'
print join ", " => $tokenizer->getTokens();
  
  # create token  ',()',
String::Tokenizer->RETAIN_WHITESPACE
                en>.

=item B<lookAheadToken>

This peeks ahead one token to the next one in the list. This item will match the next item dispensed with C<nextToken>. This is a non-destructive look ahead, meaning it does not alter the position of the internal counter.

=item B<skipToken>

This will jump the internal counter ahead by 1.

=item B<skipTokens ($number_to_skip)>

This will jump the internal counter ahead by C<$number_to_skip>.

=item B<skipTokenIfWhitespace>

This will skip the next token if it is whitespace.

=item B<skipTokensUntil ($token_to_match)>

Given a string as a C<$token_to_match>, this will skip all tokens until it matches that string. If the C<$token_to_match> is never matched, then the iterator will return the internal pointer to its initial state.

=item B<collectTokensUntil ($token_to_match)>

Given a string as a C<$token_to_match>, this will collect all tokens until it matches that string, at which point the collected tokens will be returned. If the C<$token_to_match> is never matched, then the iterator will return the internal pointer to its initial state and no tokens will be returned.

=back

=head1 TO DO

=over 4

=item I<Inline token expansion>

The Java StringTokenizer class allows for a token to be tokenized further, therefore breaking it up more and including the results into the current token stream. I have never used this feature in this class, but I can see where it might be a useful one. This may be in the next release if it works out.

Possibly compliment this expansion with compression as well, so for instance double quoted strings could be compressed into a single token.

=item I<Token Bookmarks>

Allow for the creation of "token bookmarks". Meaning we could tag a specific token with a label, that index could be returned to from any point in the token stream. We could mix this with a memory stack as well, so that we would have an ordering to the bookmarks as well.

=back

=head1 BUGS

None that I am aware of. Of course, if you find a bug, let me know, and I will be sure to fix it. 

=head1 CODE COVERAGE

I use B<Devel::Cover> to test the code coverage of my tests, below is the B<Devel::Cover> report on this module's test suite.

 ------------------------ ------ ------ ------ ------ ------ ------ ------
 File                       stmt branch   cond    sub    pod   time  total
 ------------------------ ------ ------ ------ ------ ------ ------ ------
 String/Tokenizer.pm       100.0  100.0   64.3  100.0  100.0  100.0   97.6
 ------------------------ ------ ------ ------ ------ ------ ------ ------
 Total                     100.0  100.0   64.3  100.0  100.0  100.0   97.6
 ------------------------ ------ ------ ------ ------ ------ ------ ------

=head1 SEE ALSO

The interface and workings of this module are based largely on the StringTokenizer class from the Java standard library. 

Below is a short list of other modules that might be considered similar to this one. If this module does not suit your needs, you might look at one of these.

=over 4

=item B<String::Tokeniser>

Along with being a tokenizer, it also provides a means of moving through the resulting tokens, allowing for skipping of tokens and such. But this module looks as if it hasnt been updated from 0.01 and that was uploaded in since 2002. The author (Simon Cozens) includes it in the section of L<Acme::OneHundredNotOut> entitled "The Embarrassing Past". From what I can guess, he does not intend to maintain it anymore.

=item B<Parse::Tokens>

This one hasn't been touched since 2001, although it did get up to version 0.27. It looks to lean over more towards the parser side than a basic tokenizer. 

=item B<Text::Tokenizer>

This one looks more up to date (updated as recently as March 2004), but is both a lexical analyzer and a tokenizer. It also uses XS, mine is pure perl. This is something maybe to look into if you were to need a more beefy solution that what String::Tokenizer provides.

=back

=head1 THANKS

=over

=item Thanks to Stephan Tobias for finding bugs and suggestions on whitespace handling.

=back

=head1 AUTHOR

stevan little, E<lt>stevan@iinteractive.comE<gt>

=head1 COPYRIGHT AND LICENSE

Copyright 2004 by Infinity Interactive, Inc.

L<http://www.iinteractive.com>

This library is free software; you can redistribute it and/or modify
it under the same terms as Perl itself. 

=cut
);

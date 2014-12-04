use XML::Feed;
require LWP::Parallel::UserAgent;
require HTTP::Request;

use strict;
use warnings;

#  use utf8;
#  no utf8;

sub fetch_atoms {
	my @urls = @{$_[0]};
	
	my $pua = LWP::Parallel::UserAgent->new();
	$pua->duplicates(0);    # ignore duplicates
	$pua->timeout(2);       # in seconds
	$pua->redirect(1);      # follow redirects
	
	foreach my $url (@urls) {
		my $httpRequest = HTTP::Request->new( 'GET', $url );
		$pua->register($httpRequest);
	}
	
	my @content = ();
	
	my $entries = $pua->wait();
	
	foreach my $key ( keys %$entries ) {
		my $res = $entries->{$key}->response;
	
		if($res->code == "200") {
			push(@content, $res->content);
		}
	}
	
	return @content;
}

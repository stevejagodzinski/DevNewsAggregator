package HttpContentFetcher;

use XML::Feed;
require LWP::Parallel::UserAgent;
require HTTP::Request;

use JSON;

use strict;
use warnings;

=head1 DESCRIPTION

Retrieves the content of each Atom/Rss feed. 
Builds a hash from URL to feed content.

=head2 Methods

=over 12

=item C<fetch_atoms(\@urls)>

Given an array of urls, retrieves the content of the RSS/Atom feed,
and builds a hash with the url as the key and the feed content as the value.

After a two second timeout, a feed will be silently discarded.

Input:  \@urls - Reference to an array containing the urls to scrape.

Output: Hash with the url as the key, and the feed content as the value.
		Feed content will be an instance of XML::Feed::Content

=back

=cut

use constant {
	TIMEOUT => 2,
};

sub fetch_atoms {
	my @urls = @{$_[1]};
	
	my $pua = LWP::Parallel::UserAgent->new();
	$pua->duplicates(0);    # ignore duplicates
	$pua->timeout(TIMEOUT); # in seconds
	$pua->redirect(1);      # follow redirects
	
	foreach my $url (@urls) {
		my $httpRequest = HTTP::Request->new( 'GET', $url );
		$pua->register($httpRequest);
	}
	
	my %content = ();
	
	my $entries = $pua->wait();
	
	foreach my $key ( keys %$entries ) {
		my $res = $entries->{$key}->response;
	
		if($res->code == "200") {
			$content{$res->request->url} = $res->decoded_content;
		}
	}
	
	return \%content;
}

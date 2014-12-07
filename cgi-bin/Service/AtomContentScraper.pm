package AtomContentScraper;

require Service::HttpContentFetcher;
require Model::AtomContentDefinition;
require Model::NewsEntry;

require XML::Feed;

use Date::Parse;

use strict;
use warnings;

=head1 DESCRIPTION

Scrapes each Atom/Rss feed and builds an array of Model::NewsEntry objects
sorted by date.

=head2 Methods

=over 12

=item C<scrape_crape_remote_atom_definitions(\@remote_atom_definitions)>

Given an array of Model::AtomContentDefinition objects, retrieves the RSS/Atom
content, and builds an array of Model::NewsEntry objects from the entries in 
each feed, aggregated into one single array, and sorted by date.

Input:  \@remote_atom_definitions - Reference to an array containing 
									Model::AtomContentDefinition defining feeds
									to scrape

Output: Array of Model::NewsEntry objects constructed from entries in atom/rss
		feeds. Array will be sorted by date.

=back

=cut

# usage: get_date($entry)
# input: $entry - XML::Feed::Entry
# output: The UTC epoch value for the modified or creation time of the entry
sub get_date {
	my $entry = shift;
	
	my $date_time = $entry->modified;
	if (!defined $date_time) {
		$date_time = $entry->issued;
	}
	
	return $date_time->epoch();
}

sub scrape_crape_remote_atom_definitions {
	my @remote_atom_definitions = @{$_[1]};

	my %url_names = ();
	foreach my $atom_content_definition (@remote_atom_definitions) {
		bless $atom_content_definition, "AtomContentDefinition";
		$url_names{$atom_content_definition->url}=$atom_content_definition->name;
	}

	my @urls = keys %url_names;
	my $url_content = HttpContentFetcher->fetch_atoms( \@urls );

	my @news_entries;

	foreach my $url (keys %$url_content) {
		my $content = $url_content->{$url};
		my $feed = XML::Feed->parse(\$content);
		foreach my $entry ( $feed->entries ) {
			
			my $newsEntry = NewsEntry->new(
				content	=>$entry->content,				
				title	=>$entry->title,
				date	=>get_date($entry),
				source	=>$url_names{$url},
				link	=>$entry->link
			);
			
			push( @news_entries,  $newsEntry);
		}
	}

	@news_entries = sort { $b->date - $a->date } @news_entries;

	return @news_entries;
}

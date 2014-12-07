package AtomContentScraper;

require Service::HttpContentFetcher;
require Model::AtomContentDefinition;
require Model::NewsEntry;

require XML::Feed;

use Date::Parse;

use strict;
use warnings;

sub scrape_crape_remote_atom_definitions {
	my @remote_atom_definitions = @{$_[1]};

	my @urls = ();
	foreach my $atom_content_definition (@remote_atom_definitions) {
		bless $atom_content_definition, "AtomContentDefinition";
		push( @urls, $atom_content_definition->url );
	}

	my @content = HttpContentFetcher->fetch_atoms( \@urls );

	my @news_entries;

	foreach my $content (@content) {
		my $feed = XML::Feed->parse( \$content , 'Atom');
		foreach my $entry ( $feed->entries ) {
			
			my $newsEntry = NewsEntry->new(
				content	=>$entry->content,				
				title	=>$entry->title,
				date	=>str2time($entry->updated),
				source	=>"TODO: Set Source" # TODO
			);
			
			push( @news_entries,  $newsEntry);
		}
	}

	return @news_entries;
}

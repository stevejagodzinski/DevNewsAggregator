require '.\service\HttpContentFetcher.pl';
require '.\model\AtomContentDefinition.pl';
require '.\model\NewsEntry.pl';

require XML::Feed;

use strict;
use warnings;

sub scrape_crape_remote_atom_definitions {
	my @remote_atom_definitions = @{$_[0]};

	my @urls = ();
	foreach my $atom_content_definition (@remote_atom_definitions) {
		bless $atom_content_definition, "AtomContentDefinition";
		push( @urls, $atom_content_definition->url );
	}

	my @content = fetch_atoms( \@urls );

	my @news_entries;

	foreach my $content (@content) {
		my $feed = XML::Feed->parse( \$content , 'Atom');
		foreach my $entry ( $feed->entries ) {
			
			my $newsEntry = NewsEntry->new(
				content	=>$entry->content,				
				title	=>$entry->title,
				date	=>$entry->updated,
				source	=>"TODO: Set Source" # TODO
			);
			
			push( @news_entries,  $newsEntry);
		}
	}

	return @news_entries;
}

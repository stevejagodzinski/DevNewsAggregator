#!D:\Development\Strawberry\perl\bin\perl

use strict;
use warnings;

use JSON;
require DataAccess::RssContentDefinitionDataAccess;
require Service::AtomContentScraper;
require View::ResponseBuilder;

use CGI;

=head1 DESCRIPTION

Queries PostgreSQL form atom content definitions based on http request parameters.
Retrieves the content from the defined Atom/Rss feed(s).
Feed entries are aggregated and sorted by date.
Then, transformed and printed in the expected html format.

=over 12

When the name parameter is provided. Only the feed with that name is processed.
Ex: http://localhost/cgi-bin/FeedScraper.pl?name=Example

When the userid parameter is provided. All feeds which are subscribed to by
that user are processed.
Ex: http://localhost/cgi-bin/FeedScraper.pl?userid=123

When no request parapeters are defined. All enabled feeds are processed.
Ex: http://localhost/cgi-bin/FeedScraper.pl

=back

=cut

my $cgi = CGI->new();

print $cgi->header();

my $userId = $cgi->param( "userid" );
my $name = $cgi->param( "name" );

my @content_definitions;
if ($name) {
	@content_definitions = RssContentDefinitionDataAccess->get_atom_content_definitions_by_name($name);
} elsif ($userId) {
	@content_definitions = RssContentDefinitionDataAccess->get_atom_content_definitions_for_user($userId);
} else {
	@content_definitions = RssContentDefinitionDataAccess->get_all_atom_content_definitions();
}

my @news_entries = AtomContentScraper->scrape_crape_remote_atom_definitions(\@content_definitions);

ResponseBuilder->print_response(\@news_entries);

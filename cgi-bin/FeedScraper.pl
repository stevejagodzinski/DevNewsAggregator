#!D:\Development\Strawberry\perl\bin\perl

use strict;
use warnings;

use JSON;
require DataAccess::RssContentDefinitionDataAccess;
require Service::AtomContentScraper;

use CGI;

my $cgi = CGI->new();

my $userId = $cgi->param( "userId" );
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

print JSON->new->pretty->convert_blessed->utf8->encode(\@news_entries);
#!D:\Development\Strawberry\perl\bin\perl

use strict;
use warnings;

use JSON;
require 'data_access\RssContentDefinitionDataAccess.pl';
require 'service\AtomContentScraper.pl';

use CGI;

my $cgi = CGI->new();

my $userId = $cgi->param( "userId" );
my $name = $cgi->param( "name" );

my @content_definitions;
if ($name) {
	@content_definitions = get_atom_content_definitions_by_name($name);
} elsif ($userId) {
	@content_definitions = get_atom_content_definitions_for_user($userId);
} else {
	@content_definitions = get_all_atom_content_definitions();
}

my @news_entries = scrape_crape_remote_atom_definitions(\@content_definitions);

print to_json(\@news_entries);
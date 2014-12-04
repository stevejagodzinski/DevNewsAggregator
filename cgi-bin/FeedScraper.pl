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

foreach my $newsEntry (@news_entries) {
	print "News Entry Title2: " . $newsEntry->title . "\n";
    print "News Entry Date2: " . $newsEntry->date . "\n";
    print "News Entry Source2: " . $newsEntry->source. "\n\n";
}

# TODO: Print JSON, or HTML Format
#print JSON->new->allow_blessed->utf8->encode($news_entries[0]);
#print $json->encode(\@news_entries,{allow_blessed=>1,convert_blessed=>1});
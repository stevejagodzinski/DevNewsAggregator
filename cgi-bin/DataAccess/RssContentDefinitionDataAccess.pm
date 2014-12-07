package RssContentDefinitionDataAccess;

require Model::AtomContentDefinition;

use strict;
use warnings;

use DBI;

=head1 DESCRIPTION

Reads records from the DevNewsAggregatorConfiguration_htmlcontent table
in PostgreSQL, and returns the results as an array of NewsEntry objects.

=head2 Methods

=over 12

=item C<get_all_atom_content_definitions>

Retrieves all enabled atom/rss content definitions from the database

Input:  none

Output: Array of Model::AtomContentDefinition objects constructed from database
		records
		
=item C<get_atom_content_definitions_by_name($name)>

Queries the database for a single atom content definition with the provided 
name.

Input:  $name - The name of atom content definition to retrieve

Output: Array of Model::AtomContentDefinition objects constructed from database
		records

=item C<get_atom_content_definitions_for_user($userId)>

Queries the database all atom content definitions that the user (with the provided 
$userId) has subscribed to.

Input:  $userId - The id of the user whose atom content definitions to retrieve

Output: Array of Model::AtomContentDefinition objects constructed from database
		records

=back

=cut

use constant {
	HOST     => 'localhost',
	DATABASE => 'DevNewsAggregator',
	USER     => 'DevNews',
	PASSWORD => 'DevNews',
};

# Executes a parameterized query.
# Pass query as literal string or scalar
# Optionally pass a reference to parameter array
#
# Usage:
# $query = 'SELECT "name", "url" .
#			' FROM "DevNewsAggregatorConfiguration_htmlcontent"' .
#			' WHERE "name" = $1 ' .
#			' AND "url" = $2;
# @params = ("example_name", "http://example.com");
# execute_query($query, \@params);
#
# Returns array of Model::AtomContentDefinition objects built from query 
# results
sub execute_query {
	my $query = $_[0];

	my @params;
	if ( @_ == 2 ) {
		@params = @{ $_[1] };
	}
	else {
		@params = ();
	}

	my $dbh = DBI->connect( "dbi:Pg:dbname=${\DATABASE};host=${\HOST}",
		USER, PASSWORD, {AutoCommit => 1, RaiseError => 1, PrintError => 0 });

	my $sth = $dbh->prepare($query);

	my $rv = $sth->execute(@params) or die $DBI::errstr;
	if ( $rv < 0 ) {
		print $DBI::errstr;
	}

	my @atomContentDefinitions = ();

	while ( my @row = $sth->fetchrow_array() ) {
		my $url  = $row[0];
		my $name = $row[1];

		push( @atomContentDefinitions,
			AtomContentDefinition->new( name => $name, url => $url ) );
	}

	$dbh->disconnect();

	return @atomContentDefinitions;
}

sub get_all_atom_content_definitions {
	my $query = ' SELECT "url", "name" ' . 
				' FROM "DevNewsAggregatorConfiguration_htmlcontent" ' .
				' WHERE enabled = true AND scraping_strategy = 3 ';
	return execute_query($query);
}

sub get_atom_content_definitions_by_name {
	my $name = $_[1];
	my $query = ' SELECT "url", "name" ' .
				' FROM "DevNewsAggregatorConfiguration_htmlcontent" ' .
				' WHERE enabled = true AND name = $1 ';
	my @params = ($name);
	return execute_query($query, \@params);
}

sub get_atom_content_definitions_for_user {
	my $userId = $_[1];
	my $query = ' SELECT html_content."url", html_content."name"' .
				' FROM "DevNewsAggregatorConfiguration_htmlcontent" html_content ' .
                ' INNER JOIN "DevNewsAggregatorConfiguration_htmlcontent_users" htmlcontent_users ' .
                ' ON html_content.id = htmlcontent_users.htmlcontent_id ' .
                ' WHERE html_content.enabled = true ' .
                ' AND htmlcontent_users.user_id = $1 ' .
                ' AND scraping_strategy = 3';
    my @params = ($userId);
	return execute_query($query, \@params);
}

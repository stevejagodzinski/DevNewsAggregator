package RssContentDefinitionDataAccess;

require Model::AtomContentDefinition;

use strict;
use warnings;

use DBI;

use constant {
	HOST     => 'localhost',
	DATABASE => 'DevNewsAggregator',
	USER     => 'DevNews',
	PASSWORD => 'DevNews',
};

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
		USER, PASSWORD, { AutoCommit => 1, RaiseError => 1, PrintError => 0 } );

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
	return execute_query(' SELECT "url", "name" FROM "DevNewsAggregatorConfiguration_htmlcontent" WHERE enabled = true AND scraping_strategy = 3 ');
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

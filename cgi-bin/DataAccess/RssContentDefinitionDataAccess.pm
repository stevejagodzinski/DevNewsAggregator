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
	return execute_query(
' SELECT "url", "name" FROM "AtomcontentDefinitions" WHERE enabled = true '
	);

#	my @params = (1, 2);
#	return execute_query(' SELECT "url", "name" FROM "AtomcontentDefinitions" WHERE enabled = true AND 1=$1 AND 2=$2', \@params);
}

sub get_atom_content_definitions_by_name {
	my $name = $_[1];
	return execute_query(
' SELECT "url", "name" FROM "AtomcontentDefinitions" WHERE enabled = true AND name = $1 ',
		\($name)
	);
}

sub get_atom_content_definitions_for_user {

	# todo
	return ();
}

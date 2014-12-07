package NewsEntry;

use Class::Struct;
use JSON;

use strict;
use warnings;

=head1 DESCRIPTION

Models an individual entry scraped 

=cut

struct NewsEntry => [
	title   => '$',
	date    => '$',
	content => '$',
	source  => '$',
	link	=> '$',
];



sub TO_JSON {
	my $self = shift;

	my %data = (
		'title'   => $self->title,
		'date'    => $self->date,
		'content' => $self->content->body,
		'source'  => $self->source,
		'link'    => $self->link,
	);

	return to_json \%data;
}

1;

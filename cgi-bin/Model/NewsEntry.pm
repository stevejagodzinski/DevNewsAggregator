package NewsEntry;

use Class::Struct;
use JSON;

use strict;
use warnings;

struct NewsEntry => [
	title   => '$',
	date    => '$',
	content => '$',
	source  => '$',
];


sub TO_JSON {
	my $self = shift;

	my %data = (
		'title'   => $self->title,
		'date'    => $self->date,
		'content' => $self->content->body,
		'source'  => $self->source,
	);

	return to_json \%data;
}

1;

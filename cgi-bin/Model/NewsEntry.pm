package NewsEntry;

use Class::Struct;

use strict;
use warnings;

struct NewsEntry => [
	title   => '$',
	date    => '$',
	content => '$',
	source  => '$',
];

# TODO:
#sub TO_JSON {
#	my $self = shift;
#
#	return JSON->new->utf8->encode(
#		{
#			'title'   => $self->title,
#			'date'    => $self->date,
#			'content' => $self->content,
#			'source'  => $self->source,
#		}
#	);
#}

1;

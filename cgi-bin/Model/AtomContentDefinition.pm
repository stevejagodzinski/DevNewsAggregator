package AtomContentDefinition;

use Class::Struct;

use strict;
use warnings;

=head1 DESCRIPTION

Models the definition of content to be scraped from an Atom or RSS feed

=cut

struct AtomContentDefinition =>
[
	name	=> '$',
	url	 	=> '$',
];

1;

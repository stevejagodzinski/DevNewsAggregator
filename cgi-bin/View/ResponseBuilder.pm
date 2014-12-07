package ResponseBuilder;

use Date::Format;

use strict;
use warnings;

binmode STDOUT, ":utf8";

=head1 DESCRIPTION

Prints an array of news entries in HTML format

=head2 Methods

=over 12

=item C<print_response(\@news_entries)>

Given an array of news entries, print each in html format.
The set will be printed inside of a containing <div class="content">

Input:  \@news_entries - Reference to an array containing the news entries.
		Array elements shal be instance of Model::NewsEntry.

Output: No return value. However, html is printed to standard out in utf8.

=back

=cut

sub print_response() {
	my @news_entries = @{ $_[1] };
	
	print '<div class="content">';
	
	foreach my $news_entry (@news_entries) {
		my @localTime = localtime($news_entry->date);
		
		my $content = $news_entry->content->body;
		my $display_date = strftime("%B %o, %Y %I:%M %p", @localTime);
		my $iso_date = strftime('%Y-%m-%dT%H:%M:%SZ', @localTime);
		my $link = $news_entry->link;
		my $source = $news_entry->source;
		my $title = $news_entry->title;
		
		print "<div class=\"news-entry\" data-content-source=\"$source\">";
		print "<div class=\"news-entry-header\">";
		print "<span class=\"news-entry-title\">";
		print "<a href=\"$link\">$title</a>";
		print "</span>";
		print "<span class=\"news-entry-date\" data-iso-date=\"$iso_date\">$display_date</span>";
		print "</div>";
		print "<div class=\"news-entry-content\">$content</div>";
		print "</div>";
	}
	
	print "</div>";
}

1;
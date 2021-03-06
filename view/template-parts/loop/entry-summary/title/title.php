<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 11.0.0
 */

use Framework\Helper;

$args = wp_parse_args(
	$args,
	[
		'_title_tag' => 'h2',
	]
);

$layout    = get_theme_mod( get_post_type() . '-entries-layout' );
$title_tag = $args['_title_tag'];
?>

<<?php echo esc_html( $title_tag ); ?> class="c-entry-summary__title">
<?php
if ( 'rich-media' !== $layout ) {
	the_title();
} else {
	Helper::the_title_trimed();
}
?>
</<?php echo esc_html( $title_tag ); ?>>
<?php if ( is_post_type_archive( 'event' ) ) : ?>
<p class="p-event-date">開催日：<?php the_field( 'event-day' ); ?></p>
<?php endif; ?>

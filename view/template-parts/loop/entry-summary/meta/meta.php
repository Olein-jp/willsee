<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 6.0.0
 */
?>
<?php
/**
 * カスタム投稿タイプ：eventの場合の表示内容
 */
if ( is_post_type_archive( 'event' ) ) : ?>
	<div class="c-entry-summary__meta">
		<ul class="c-meta p-event-meta-list">
			<li class="c-meta__item c-meta__item--author">
				<span class="p-event-icon p-event-icon__management">主催</span><?php echo esc_html( get_the_author() ); ?>
			</li>
			<li class="c-meta__item p-event-published"><span class="p-event-icon p-event-icon__published">ページ作成日</span><?php the_time( get_option( 'date_format' ) ); ?></li>
			<?php if ( get_the_time('Y/m/d') != get_the_modified_date('Y/m/d') ) : ?>
				<li class="c-meta__item p-event-published"><span class="p-event-icon p-event-icon__modified">ページ更新日</span><?php the_modified_date( get_option( 'date_format' ) ); ?></li>
			<?php endif; ?>
		</ul>
	</div>
<?php
elseif ( is_post_type_archive( 'group' ) ) : ?>
	<div class="c-entry-summary__meta">
		<ul class="c-meta p-group-meta">
			<li class="c-meta__item p-group-meta__title">分野</li>
			<?php
			$terms = get_the_terms( get_the_ID(), 'field' );
			if ( ! empty( $terms ) ) : if ( ! is_wp_error( $terms ) ) : ?>
			<?php foreach( $terms as $term ) : ?>
			<li class="c-meta__item p-group-meta__item"><?php echo $term->name; ?></li>
			<?php endforeach; ?>
			<?php endif; endif; ?>
			<li class="c-meta__item p-group-meta__title">SDGs</li>
			<?php
			$terms = get_the_terms( get_the_ID(), 'sdgs' );
			if ( ! empty( $terms ) ) : if ( ! is_wp_error( $terms ) ) : ?>
				<?php foreach( $terms as $term ) : ?>
					<li class="c-meta__item p-group-meta__item"><?php echo $term->name; ?></li>
				<?php endforeach; ?>
			<?php endif; endif; ?>
		</ul>
	</div>
<?php
/**
 * その他の場合の表示内容
 */
else : ?>
	<?php if ( get_post_type() === 'event' ) : ?>
		<p class="p-event-date">開催日：<?php the_field( 'event-day' ); ?></p>
	<div class="c-entry-summary__meta">
		<ul class="c-meta">
			<li class="c-meta__item c-meta__item--author">
				<span class="p-event-icon p-event-icon__management">主催</span><?php echo esc_html( get_the_author() ); ?>
			</li>
		</ul>
	</div>
	<?php elseif( get_post_type() === 'group' ) : ?>

	<?php else : ?>
	<div class="c-entry-summary__meta">
		<ul class="c-meta">
			<li class="c-meta__item c-meta__item--author">
				<?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?><?php echo esc_html( get_the_author() ); ?>
			</li>
			<li class="c-meta__item c-meta__item--published">
				<?php the_time( get_option( 'date_format' ) ); ?>
			</li>
		</ul>
	</div>
	<?php endif; ?>

<?php endif; ?>

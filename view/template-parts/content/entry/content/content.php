<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 10.8.0
 */

use Framework\Helper;
?>

<?php do_action( 'snow_monkey_before_entry_content' ); ?>

<div class="c-entry__content p-entry-content">
	<?php do_action( 'snow_monkey_prepend_entry_content' ); ?>

	<?php if ( get_post_type() === 'event' ) : ?>
	<h2 class="p-willsee-sub-section">イベント情報</h2>
	<div class="p-event-table-wrap">
		<table class="p-event-table">
			<tr>
				<th>イベント名</th>
				<td><?php the_title(); ?></td>
			</tr>
			<tr>
				<th>イベント開催日時</th>
				<td><?php the_field( 'event-day' ); ?>　<?php the_field( 'event-start-time' ); ?>〜<?php the_field( 'event-end-time' ); ?></td>
			</tr>
			<tr>
				<th>開催場所</th>
				<td>
					<?php
					$event_place = get_field( 'event-place' );
					if ( $event_place === 'その他' ) :
					?>
					<?php the_field( 'event-place-other' ); ?>
					<?php else : ?>
					<?php the_field( 'event-place' ); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php if ($terms = get_the_terms($post->ID, 'keyword') ) : ?>
			<tr>
				<th>キーワード</th>
				<td>
					<?php
						foreach ( $terms as $term ) {
							echo '<span class="p-event-keyword">' . esc_html($term->name) . '</span>';
						}
					?>
				</td>
			</tr>
			<?php endif; ?>
			<tr>
				<th>詳細</th>
				<td><?php the_field( 'event-detail' ); ?></td>
			</tr>
			<tr>
				<th>参加形態</th>
				<td><?php the_field( 'event-participation-form' ); ?></td>
			</tr>
			<tr>
				<th>料金など</th>
				<td><?php the_field( 'event-price' ); ?></td>
			</tr>
			<?php
			$event_entry_page = get_field( 'event-entry-page' );
			if ( $event_entry_page ) :
			?>
			<tr>
				<th>申し込みページ</th>
				<td><a href="<?php the_field( 'event-entry-page' ); ?>" target="_blank"><?php the_field( 'event-entry-page' ); ?></a></td>
			</tr>
			<?php endif; ?>
			<?php 
			$event_detail_page = get_field( 'event-detail-page' );
			if ( $event_detail_page ) :
			?>
			<tr>
				<th>詳細ページ</th>
				<td><a href="<?php the_field( 'event-detail-page' ); ?>" target="_blank"><?php the_field( 'event-detail-page' ); ?></a></td>
			</tr>
			<?php endif; ?>
			<?php
			$event_image = get_field( 'event-image' );
			if ( $event_image ) :
			?>
			<tr>
				<th>詳細画像</th>
				<td><img src="<?php the_field( 'event-image' ); ?>" alt="<?php the_title(); ?>詳細画像"></td>
			</tr>
			<?php endif; ?>
			<?php
			$event_note = get_field( 'event-note' );
			if ( $event_note ) :
			?>
			<tr>
				<th>備考</th>
				<td><?php the_field( 'event-note' ); ?></td>
			</tr>
			<?php endif; ?>
		</table>
	</div>
	<h2 class="p-willsee-sub-section">主催者情報</h2>
	<div class="p-event-table-wrap">
		<table class="p-event-table">
			<tr>
				<th>主催</th>
				<td>
					<?php
					$post = get_post( $post_id );
					if ( $post ) {
						$author_id = get_userdata( $post->post_author );
					}
					$args = array(
						'author' => '$author_id',
						'post_type' => 'group',
					);
					$the_query = new WP_Query( $args );
					?>
					<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					<?php endwhile; endif; wp_reset_postdata(); ?>
					<?php
					$event_sponsor = get_field( 'event-sponsor' );
					if ( $event_sponsor ) :
					?>
					／<?php the_field( 'event-sponsor' ); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php
			$event_sponsorship = get_field( 'event-sponsorship' );
			if ( $event_sponsorship ) :
			?>
				<tr>
					<th>後援</th>
					<td><?php the_field( 'event-sponsorship' ); ?></td>
				</tr>
			<?php endif; ?>
			<?php
			$event_tel = get_field( 'event-tel' );
			$event_mail = get_field( 'event-mail' );
			if ( $event_tel || $event_mail ) :
			?>
			<tr>
				<th>お問い合わせ</th>
				<td>
					<?php if ( $event_tel ) : ?>
					<a href="tel:<?php the_field( 'event-tel' ); ?>" class="p-event-contact-button">電話する</a>
					<?php endif; ?>
					<?php if ( $event_mail ) : ?>
					<a href="mailto:<?php the_field( 'event-mail' ); ?>" class="p-event-contact-button">メールする</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif; ?>
		</table>
	</div>
	<?php elseif ( get_post_type() === 'group' ) : ?>
	<h2 class="p-willsee-sub-section">団体情報</h2>
	<div class="p-event-table-wrap">
		<table class="p-event-table">
			<tr>
				<th>団体名</th>
				<td><?php the_field( 'group-name' ); ?></td>
			</tr>
			<tr>
				<th>活動内容</th>
				<td><?php the_field( 'group-activity-description' ); ?></td>
			</tr>
			<tr>
				<th>PRポイント</th>
				<td><?php the_field( 'group-pr' ); ?></td>
			</tr>
			<?php if ($terms = get_the_terms($post->ID, 'field') ) : ?>
			<tr>
				<th>活動分野</th>
				<td>
					<?php
					foreach ( $terms as $term ) {
						echo '<span class="p-group-field-label">' . esc_html($term->name) . '</span>';
					}
					?>
				</td>
			</tr>
			<?php endif; ?>
			<?php if ($terms = get_the_terms($post->ID, 'sdgs') ) : ?>
				<tr>
					<th>SDGs</th>
					<td>
						<?php
						foreach ( $terms as $term ) {
							echo '<span class="p-group-sdgs-label">' . esc_html($term->name) . '</span>';
						}
						?>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<th>代表者</th>
				<td><?php the_field( 'group-representative' ); ?></td>
			</tr>
			<tr>
				<th>会員数</th>
				<td><?php the_field( 'group-member' ); ?></td>
			</tr>
			<tr>
				<th>設立</th>
				<td><?php the_field( 'group-establishment' ); ?></td>
			</tr>
			<?php
			$group_web = get_field( 'group-web' );
			if ( $group_web ) :
			?>
			<tr>
				<th>ホームページ</th>
				<td><a href="<?php the_field( 'group-web' ); ?>" target="_blank"><?php the_field( 'group-web' ); ?></td>
			</tr>
			<?php endif; ?>
		</table>
	</div>
	<h2 class="p-willsee-sub-section">連絡先</h2>
		<div class="p-event-table-wrap">
			<table class="p-event-table">
				<tr>
					<th>連絡担当者</th>
					<td>
						<?php
						$group_contact_person = get_field( 'group-contact-person' );
						if ( $group_contact_person ) :
						?>
						<?php the_field( 'group-contact-person' ); ?>
						<?php else : ?>
						<?php the_field( 'group-representative' ); ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php
				$group_address = get_field( 'group-address' );
				if ( $group_address ) :
				?>
				<tr>
					<th>住所</th>
					<td><?php the_field( 'group-address' ); ?></td>
				</tr>
				<?php endif; ?>
				<?php
				$group_tel = get_field( 'group-tel' );
				if ( $group_tel ) :
					?>
					<tr>
						<th>TEL</th>
						<td><?php the_field( 'group-tel' ); ?></td>
					</tr>
				<?php endif; ?>
				<?php
				$group_fax = get_field( 'group-fax' );
				if ( $group_fax ) :
					?>
					<tr>
						<th>FAX</th>
						<td><?php the_field( 'group-fax' ); ?></td>
					</tr>
				<?php endif; ?>
			</table>
		</div>

		<?php
		$post = get_post( $post_id );
		if ( $post ) {
			$author_id = get_userdata( $post->post_author );
		}
		$args = array(
			'author' => '$author_id',
			'post_type' => 'event',
			'posts_per_page' => 10,
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) :
		?>
		<div class="snow-monkey-posts snow-monkey-recent-posts p-group-related-event">
			<h2 class="c-widget__title snow-monkey-recent-posts__title p-group-related-event__title">こちらの団体が最近作成したイベント</h2>

			<ul class="c-entries c-entries--simple" data-has-infeed-ads="false" data-force-sm-1col="false">
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<li class="c-entries__item">

					<a href="<?php the_permalink(); ?>">
						<section class="c-entry-summary c-entry-summary--event">

							<div class="c-entry-summary__figure">
							<?php the_post_thumbnail(); ?>
							</div>

							<div class="c-entry-summary__body">
								<header class="c-entry-summary__header">

									<h3 class="c-entry-summary__title"><?php the_title(); ?></h3>
								</header>


								<div class="c-entry-summary__content"><?php the_excerpt(); ?></div>

								<div class="c-entry-summary__meta">
									<ul class="c-meta">
										<li class="c-meta__item">開催日：<?php the_field( 'event-day' ); ?></li>
									</ul>
								</div>
							</div>
						</section>
					</a>
				</li>
			<?php endwhile; ?>
			</ul>
		</div>
		<?php endif; wp_reset_postdata(); ?>
	<?php else : ?>
	<?php the_content(); ?>
	<?php endif; ?>
	<?php Helper::get_template_part( 'template-parts/content/link-pages' ); ?>

	<?php do_action( 'snow_monkey_append_entry_content' ); ?>
</div>

<?php do_action( 'snow_monkey_after_entry_content' ); ?>

<?php
/**
 * Plugin name: WILLSEE カスタマイズ専用プラグイン
 * Description: WILLSEE カスタマイズ専用プラグイン for Snow Monkey
 * Version: 1.0.0
 */

/**
 * Snow Monkey 以外のテーマを利用している場合は有効化してもカスタマイズが反映されないようにする
 */
$theme = wp_get_theme( get_template() );
if ( 'snow-monkey' !== $theme->template && 'snow-monkey/resources' !== $theme->template ) {
	return;
}

/**
 * Directory url of this plugin
 *
 * @var string
 */
define( 'MY_SNOW_MONKEY_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Directory path of this plugin
 *
 * @var string
 */
define( 'MY_SNOW_MONKEY_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * テンプレートを上書き出来る様に設定
 */
add_filter(
	'snow_monkey_template_part_root_hierarchy',
	function( $hierarchy ) {
		$hierarchy[] = untrailingslashit( __DIR__ ) . '/view';
		return $hierarchy;
	}
);

/**
 * import CSS file
 */
add_action(
	'wp_enqueue_scripts',
	function() {
		wp_enqueue_style(
			'gifucaic-styles',
			untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/style.css',
			[ Framework\Helper::get_main_style_handle() ],
			filemtime( plugin_dir_path( __FILE__ ) )
		);

		wp_enqueue_script(
			'willsee-script',
			untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/script.js', array( 'jquery' ),
			null,
			true
		);
	}
);

/**
 * css for editor view
 */
add_action(
	'admin_init',
	function() {
		add_editor_style( '/../../plugins/gifucaic/editor-style.css' );
	}
);

/**
 * 管理者以外は自分がアップロードしたメディア以外は見ることができなくする
 */
function display_only_self_uploaded_medias( $query ) {
	if ( ( $user = wp_get_current_user() ) && ! current_user_can( 'administrator' ) ) {
		$query['author'] = $user->ID;
	}
	return $query;
}
add_action( 'ajax_query_attachments_args', 'display_only_self_uploaded_medias' );

/**
 * イベント情報をカスタムフィールドで指定した日時順で一覧表示させるショートコード
 */
function event_list_by_custom_field_daytime() {
	global $post;

	$today = date( 'Y-m-d');
	$endday = date( 'Y-m-d', strtotime( '+7 day', strtotime( date_i18n( 'Y-m-d' ) ) ) );

	$args = array( // クエリの準備
		'post_per_page' => 9,
		'post_type' => 'event',
		'order' => 'ASC',
		'orderby' => 'meta_value',
		'meta_query' => array(
			array(
				'key' => 'event-day',
				'value' => date( 'Y-m-d'),
				'compare' => '>=',
				'type' => 'DATE'
			)
		)
	);

	$posts_array = get_posts( $args );
	$html = '<ul class="c-entries c-entries--panel p-willsee-near-event" data-force-sm-1col="true">';
	foreach ( $posts_array as $post ) :
		setup_postdata( $post );
		$html .= '<li class="c-entries__item">';
		$html .= '<a href="' . get_permalink() . '">';
		$html .= '<section class="c-entry-summary c-entry-summary--event">';
		$html .= '<div class="c-entry-summary c-entry-summary__figure">';
		$html .= get_the_post_thumbnail();
		$html .= '</div>';
		$html .= '<div class="c-entry-summary__body">';
		$html .= '<header class="c-entry-summary__header">';
		$html .= '<h3 class="c-entry-summary__title">' . get_the_title() . '</h3>';
		$html .= '</header>';
		$event_day = get_field( 'event-day' );
		$html .= '<p class="p-event-date p-event-date_active">開催日：' . date( 'Y年m月d日', strtotime( $event_day ) ) . '</p>';
		$html .= '<div class="c-entry-summary__content">' . get_the_excerpt() . '</div>';
		$html .= '<div class="c-entry-summary__meta">';
		$html .= '<ul class="c-meta p-event-meta-list">';
		$html .= '<li class="c-meta__item"><span class="p-event-icon p-event-icon__management">主催</span>' .  esc_html( get_the_author() ) . '</li>';
//		$html .= '</ul>';
//		$html .= '<ul class="c-meta">';
//		$html .= '<li class="c-meta__item p-event-published"><span class="p-event-icon p-event-icon__published">ページ作成日</span>' . get_the_time( get_option( 'date_format' ) ). '</li>';
//		if ( get_the_time('Y/m/d') != get_the_modified_date('Y/m/d') ) :
//			$html .= '<li class="c-meta__item p-event-published"><span class="p-event-icon p-event-icon__modified">ページ更新日</span>' . get_the_modified_date( get_option( 'date_format' ) ) . '</li>';
//		endif;
		$html .= '</ul>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</section>';
//		$html .= '<p>開催日：' . get_field( 'event-day' ) . '</p>';
//		$html .= '<p>主催：' . get_the_author() . '</p>';
		$html .= '</a>';
		$html .= '</li>';
	endforeach; // ループの終了
	$html.='</ul>';
	wp_reset_postdata(); // 投稿のリセット
	return $html;
}
add_shortcode( 'gifucaic-recently-events', 'event_list_by_custom_field_daytime');

/**
 * カスタム投稿タイプ編集画面に「投稿者」を表示させる
 */
add_action('admin_menu', 'myplugin_add_custom_box');
function myplugin_add_custom_box()
{
	if (function_exists('add_meta_box')) {
		add_meta_box('myplugin_sectionid', __('作成者', 'myplugin_textdomain'), 'post_author_meta_box', 'event', 'advanced');
	}
}

/**
 *  プロフィールボックスのカスタマイズ
 */
add_filter( 'snow_monkey_template_part_render',
	function( $_html, $_slug, $_name, $_vars ) {
		if ( 'template-parts/common/profile-box' === $_slug && get_post_type() === 'event' ) {
			$_html = str_replace(
				'<h2 class="wp-profile-box__title">' . esc_html__( 'Bio', 'inc2734-wp-profile-box' ) . '</h2>',
				'<h2 class="wp-profile-box__title">このイベントページ作成者について</h2>',
				$_html
			);
		}
		return $_html;
	}
	,
	10,
	4
);

add_filter( 'snow_monkey_template_part_render',
	function( $_html, $_slug, $_name, $_vars ) {
		if ( 'template-parts/common/profile-box' === $_slug && get_post_type() === 'event' ) {
			$_html = str_replace(
				'<a class="wp-profile-box__archives-btn" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">',
				'<a class="wp-profile-box__archives-btn p-event-profile-post-archive-title" href="' . esc_url( get_bloginfo( 'url' ) ) . '/event/author/' . get_the_author() . '">',
				$_html
			);
		}
		return $_html;
	}
	,
	10,
	4
);

/**
 * 関連記事カスタマイズ
 */
add_filter( 'snow_monkey_related_posts_args',
	function( $_args ) {
		// 最大数の変更
		$_args[ 'posts_per_page' ] = 12;
		return $_args;
	}
);

add_filter(
	'snow_monkey_template_part_render',
	function( $html, $slug ) {
		if ( 'template-parts/content/related-posts' === $slug ) {
			return preg_replace(
				'|<span>.*?関連記事|ms',
				'<span>その他のおすすめイベント',
				$html
			);
		}
		return $html;
	},
	10,
	2
);

/**
 * 管理画面カスタマイズ
 */
include( 'admin/admin-customize.php' );
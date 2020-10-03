<?php
/**
 * ログイン画面へ読み込むソース
 */
function willsee_login_customize_script() {
	wp_enqueue_style( 'willsee-login-customize-style', plugin_dir_url( __FILE__ ) . 'login-style.css' );

	wp_enqueue_script( 'willsee-login-customize-script', plugin_dir_url( __FILE__ ) . 'login-script.js', array( 'jquery' ), null, true );
}
add_action( 'login_enqueue_scripts', 'willsee_login_customize_script' );

/**
 * 管理画面に読み込むソース
 */
function willsee_admin_customize_script() {
	wp_enqueue_style( 'willsee-admin-customize-style', plugin_dir_url( __FILE__ ) . 'admin-style.css' );

	if ( ! current_user_can( 'administrator' ) ) {
		wp_enqueue_script( 'willsee-admin-customize-script', plugin_dir_url( __FILE__ ) . 'admin-script.js', array( 'jquery' ), null, true );
	}
}
add_action( 'admin_enqueue_scripts', 'willsee_admin_customize_script' );

/**
 * ログイン画面：ロゴのリンク先を変更
 */
function change_login_logo_url() {
	return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'change_login_logo_url' );

/**
 * ログイン画面：ロゴのタイトル属性を変更
 */
function change_login_logo_title() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'change_login_logo_title' );

/**
 * 管理画面：管理権限以外はダッシュボードのデフォルトウィジェットを削除
 */
function willsee_remove_dashboard_widget() {
		if ( ! current_user_can( 'administrator' ) ) {
			remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
			remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
			remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
			remove_meta_box( 'snow-monkey-support-forum-topics-widget', 'dashboard', 'side' );
		}
}
add_action( 'wp_dashboard_setup', 'willsee_remove_dashboard_widget' );

/**
 * 管理画面：ダッシュボードにオリジナルウィジェットを作成
 */
function willsee_custom_dashboard_widget() {
	wp_add_dashboard_widget(
		'willsee_dashboard_new_register_widget',
		'団体情報登録・お問い合わせ',
		'willsee_dashboard_new_register',
	);


	wp_add_dashboard_widget(
		'willsee_dashboard_news_widget',
		'WILLSEE運営部からのお知らせ',
		'willsee_dashboard_news'
	);

	wp_add_dashboard_widget(
		'willsee_dashboard_easy_access_widget',
		'ページを作成・編集',
		'willsee_dashboard_easy_access'
	);

	wp_add_dashboard_widget(
		'willsee_dashboard_group_manual_widget',
		'操作マニュアル一覧',
		'willsee_dashboard_group_manual'
	);
}
add_action( 'wp_dashboard_setup', 'willsee_custom_dashboard_widget' );

/**
 * 管理画面：団体登録はこちらウィジェット
 */
function willsee_dashboard_new_register() { ?>
	<div class="willsee-dashboard-new-register">
		<p>WILLSEEを活用していただくためには、最初に団体情報を登録していただく必要があります。</p>
		<p>以下のボタンをクリックして団体情報を入力して送信してください（別ウィンドウが開きます）。WILLSEE運営部にて団体ページを作成します。（作成後はご自由に編集可能です）</p>
		<a href="https://docs.google.com/forms/d/e/1FAIpQLSdCl43-A82NpsaKUYICpEiCKUjJTW-QRKt_D79IGHBja_zsDw/viewform" class="button" target="_blank">団体情報を登録する</a>
	</div>
	<div class="willsee-dashboard-contact">
		<p>マニュアルを読んでも不明な点がある場合にはこちらよりお問い合わせください。（運営団体であるShiftのお問い合わせページへ移動します）</p>
		<a href="https://shift-gifu.org/contact" class="button">問い合わせをする</a>
	</div>
<?php }

/**
 *  管理画面：運営部からのお知らせウィジェット
 */
function willsee_dashboard_news() { ?>
	<ul>
		<?php
		global $post;
		$args = array(
			'numberposts' => 10,
			'category_name' => 'news',
		);

		$myposts = get_posts( $args );
		foreach( $myposts as $post ) :  setup_postdata($post); ?>
			<li><? the_date('Y年n月d日'); ?>　<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php endforeach; ?>
	</ul>
<?php }

/**
 *  管理画面：イージーアクセスウィジェット
 */
function willsee_dashboard_easy_access() {
	echo '
	<p>こちらより簡単に編集画面に移動することができます。左サイドバーのメニューからも同じページへ移動することができます。</p>
	<ul class="admin-dashboard-easy-access">
		<li><a href="post-new.php?post_type=event"><div class="dashicons dashicons-star-filled"></div><span>新規イベント情報作成</span></a></li>
		<li><a href="edit.php?post_type=event"><div class="dashicons dashicons-calendar-alt"></div><span>イベント情報編集</span></a></li>
		<li><a href="edit.php?post_type=group"><div class="dashicons dashicons-groups"></div><span>団体情報編集</span></a></li>
		<li><a href="profile.php"><div class="dashicons dashicons-admin-users"></div><span>プロフィール編集</span></a></li>
	</ul>';
}

/**
 * 管理画面：操作マニュアルウィジェット
 */
function willsee_dashboard_group_manual() {
	echo '
		<ul>
			<li><a href="' . esc_url( home_url() ) . '/manual" target="_blank">マニュアル一覧</a></li>
			<li><a href="' . esc_url( home_url() ) . '/manual/how-to-create-willsee-account" target="_blank">WILLSEEアカウント（無料）の作り方</a></li>
			<li><a href="' . esc_url( home_url() ) . '/manual/register-flow" target="_blank">登録の流れ</a></li>
			<li><a href="' . esc_url( home_url() ) . '/manual/send-group-information" target="_blank">団体情報を送信する</a></li>
			<li><a href="' . esc_url( home_url() ) . '/manual/how-to-create-event-page" target="_blank">新規イベント情報ページの作り方</a></li>
			<li><a href="' . esc_url( home_url() ) . '/manual/how-to-edit-event-page" target="_blank">イベント情報ページの編集方法</a></li>
			<li><a href="' . esc_url( home_url() ) . '/manual/how-to-edit-group-page" target="_blank">団体ページの編集方法</a></li>
			<li><a href="' . esc_url( home_url() ) . '/manual/how-to-edit-profile" target="_blank">プロフィールの編集方法</a></li>
		</ul>';
}

/**
 * 登録者に送信するメール情報をカスタマイズ
 */
// 送信者名を変更
add_filter( 'wp_mail_from_name', function( $email_from ) {
	return 'WILLSEE運営部';
});

// 送信者メールアドレスを変更
add_filter( 'wp_mail_from', function( $email_address ) {
	return 'hello@willsee.jp';
});

// メール内容を変更
add_filter( 'wp_new_user_notification_email', 'custom_new_user_notification_email', 10, 3 );
function custom_new_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {
	$subject = $blogname . 'から大切なお知らせ';
	$message = '
上記のURLをクリックしてパスワード設定を完了してください。初期パスワードをそのまま利用することも可能です。

また、安全なパスワードを作成したい場合には、こちらのようなパスワード作成サービスもあります。

https://www.graviness.com/app/pwg/

#####
パスワード設定が完了したら・・・
#####
まずはログインをしてみましょう。登録した情報でログインができるか必ず確認をしてください。

https://willsee.jp/login-willsee


#####
ログインが無事に完了したら
#####
管理画面が表示されます。
画面内に以下の項目が表示されているはずです。それぞれご利用の際に活用いただけるものになりますので、必ず確認してください。

１）WILLSEE運営部からのお知らせ
こちらにはWILLSEEブログでお知らせしている情報にアクセスすることができます。随時確認をお願いします。

２）ページを作成・編集
こちらの中には「イベント編集」「団体ページ編集」「プロフィール編集」とあります。
それぞれの利用方法については、以下に紹介するマニュアルページをご確認ください。

３）団体情報登録・お問い合わせ
このメールをお読みになられている方は既に団体登録が完了してみえる方々ですので、団体情報を登録する部分は説明を割愛します。

マニュアルを読んでも分からない点などがある場合にはお問い合わせください。その際にはこちらの「問い合わせをする」ボタンをクリックすると、運営団体であるShiftのお問い合わせページへ移動します。そちらよりお問い合わせ内容を送信してください。

４）操作マニュアル一覧
こちらに用意されている操作マニュアルページへのリンクが用意されています。ご利用に活用してください。


-----
以上、簡単な管理画面の紹介となります。
マニュアルページを参考にしてイベント情報ページなどを作成し活用していただければ幸いです。
不明な点はお問い合わせください。

何卒よろしくお願いいたします。

運営：岐阜市登録市民団体Shift
メール：shift.gifu@gmail.com
ホームページ：https://shift-gifu.org
	';

	$wp_new_user_notification_email['subject'] = $subject;
	$wp_new_user_notification_email['message'] .= $message;
	return $wp_new_user_notification_email;
}
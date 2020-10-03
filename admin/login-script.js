jQuery(function($){
    $( '#nav > a:first-child' ).text( '団体アカウント作成はこちら' );
    $( '#nav > a:last-child' ).text( 'パスワード再発行' );
    $( '#login_error > br' ).hide();
    $( '#login > .message.register' ).text( 'WILLSEEアカウントを新規作成' );
    $( '#registerform > p:first-child > label' ).text( '希望ユーザー名 （半角英数/記号（. _ - @）利用可能）' );
});
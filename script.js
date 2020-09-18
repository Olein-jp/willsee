jQuery(function($){
    $( '.p-search-group > div > ul > li:nth-child(1)' ).addClass( 'c-willsee-search__text' );
    $( '.p-search-group > div > ul > li:nth-child(2)' ).addClass( 'c-willsee-search__checkbox' );
    $( '.p-search-group > div > ul > li:nth-child(3)' ).addClass( 'c-willsee-search__checkbox' );
    $( '.p-search-group > div > ul > li:nth-child(4)' ).addClass( 'c-willsee-search__submit' );

    $( '.p-search-event > div > ul > li:nth-child(1)' ).addClass( 'c-willsee-search__text' );
    $( '.p-search-event > div > ul > li:nth-child(2)' ).addClass( 'c-willsee-search__checkbox' );
    $( '.p-search-event > div > ul > li:nth-child(3)' ).addClass( 'c-willsee-search__submit' );

    // イベントページ作成者プロフィール内のイベントページ一覧へのリンクボタンのテキストを変更
    var eventPostArchiveTitle = $( '.p-event-profile-post-archive-title' ).text();
    var result = eventPostArchiveTitle.replace( '記事一覧', '開催イベント一覧' );
    $( '.p-event-profile-post-archive-title' ).text( result );
});
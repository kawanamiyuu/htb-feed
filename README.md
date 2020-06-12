htb-feed
----

はてなブックマークの新着エントリーを`カテゴリー`と`ブクマ数`でフィルタリングして Feed として配信する Web アプリケーション

## Getting started

1. install dependencies

    ```console
    $ composer install
    ```

1. start server

    ```console
    $ composer serve
    ```
   
1. view feed
    * e.g.) http://localhost:8080/atom?category=it&users=100

1. if you confirm a feed with your Feed Reader, you can temporally publish the feed by [ngrok](https://ngrok.com/) and subscribe to

    ```console
    $ ngrok http 8080
    ```

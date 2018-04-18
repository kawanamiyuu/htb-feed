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
    $ php -S localhost:8080 -t public
    ```

1. publish a feed temporarily using [ngrok](https://ngrok.com/)

    ```console
    $ ngrok http 8080
    ```

1. subscribe to a feed by your favorite Feed Reader (e.g. Feedly)

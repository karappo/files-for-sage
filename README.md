# Karappo Common files for Sage 10.x

- [Sage](https://roots.io/sage/)のプロジェクトで共通で使えるヘルパーなどをまとめて、共通化する目的です。
- プロジェクト特有のHelperはこれまで通り、Sageのhelper.phpに記述してください。(TODO: 確認。Sage 10でなくなった？)
- サブモジュールとしてプロジェクトについて貸して、Sageのファイルから読み込むことで動作します。

**Currentrly support Sage 10.x**

## Getting started

1. Sageのプロジェクトをセットアップした状態から、themeディレクトリ直下にサブモジュールとして追加
  ```
  git submodule add git@github.com:karappo/files-for-sage.git wp/wp-content/themes/<project>/karappo-common
  ```
1. /themes/<project>/functions.php (L:57あたり)に外部ファイルの読み込み設定を追加
  ```
  collect(['setup', 'filters'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });
  ```
  ↓ 先頭の行に`'../karappo-common/app/helpers', `を追記
  ```
  collect(['setup', 'filters', '../karappo-common/app/helpers'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });
  ```
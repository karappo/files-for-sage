# Karappo Common files for Sage 9.x

- [Sage](https://roots.io/sage/)のプロジェクトで共通で使えるヘルパーなどをまとめて、共通化する目的です。
- プロジェクト特有のHelperはこれまで通り、Sageのhelper.phpに記述してください。
- サブモジュールとしてプロジェクトについて貸して、Sageのファイルから読み込むことで動作します。

**Currentrly support Sage 9.x**

## Getting started

1. Sageのプロジェクトをセットアップした状態から、themeディレクトリ直下にサブモジュールとして追加
  ```
  git submodule add git@github.com:karappo/files-for-sage.git wp/wp-content/themes/<project>/karappo-common
  ```
2. resources/functions.php (L:61あたり)に外部ファイルの読み込み設定を追加
  ```
  array_map(function ($file) use ($sage_error) {
      $file = "../app/{$file}.php";
      if (!locate_template($file, true, true)) {
          $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
      }
  }, ['helpers', 'setup', 'filters', 'admin']);
  ```
  ↓ 末尾の行に`'../karappo-common/app/helpers', `を追記
  ```
  array_map(function ($file) use ($sage_error) {
      $file = "../app/{$file}.php";
      if (!locate_template($file, true, true)) {
          $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
      }
  }, ['helpers', '../karappo-common/app/helpers', 'setup', 'filters', 'admin']);
  ```
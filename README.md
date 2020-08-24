# karappo-common

Sageをプロジェクトで共通で使えるヘルパーなどをまとめて、共通化する目的。

## Getting started

1. Sageのプロジェクトをセットアップした状態から、themeディレクトリ直下にサブモジュールとして追加
  ```
  git submodule add git@github.com:karappo/karappo-common.git wp/wp-content/themes/<project>/karappo-common
  ```
2. resources/functions.php (L:61あたり)に外部ファイルの読み込み設定を追加（`'../karappo-common/app/helpers', `）
  ```
  /**
  * Sage required files
  *
  * The mapped array determines the code library included in your theme.
  * Add or remove files to the array as needed. Supports child theme overrides.
  */
  array_map(function ($file) use ($sage_error) {
      $file = "../app/{$file}.php";
      if (!locate_template($file, true, true)) {
          $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
      }
  }, ['helpers', 'setup', 'filters', 'admin']);
  ```
  ↓
  ```
  /**
  * Sage required files
  *
  * The mapped array determines the code library included in your theme.
  * Add or remove files to the array as needed. Supports child theme overrides.
  */
  array_map(function ($file) use ($sage_error) {
      $file = "../app/{$file}.php";
      if (!locate_template($file, true, true)) {
          $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
      }
  }, ['helpers', '../karappo-common/app/helpers', 'setup', 'filters', 'admin']);
  ```
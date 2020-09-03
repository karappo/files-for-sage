<?php

// Karappo Original helpers

namespace App;

use Roots\Sage\Container;

/**
 * 単純なimgタグ
 * @param string $src Path to image
 * @param string $attrs Attributes e.g. 'alt="description of image" data-value="hoge"'
 * @param bool $return Set true if you just want result without echo
 * @return string
 */
function image_tag($src, $attrs = '', $return = false) {
  $src = asset_path("images/$src");

  $res = "<img $attrs src=\"$src\">";
  if($return){
    return $res;
  }
  echo $res;
}
/**
 * PC/SP用の２つのimgタグ（それぞれpc,spクラスを付与）
 * @param string $src Path to image
 * @param string $attrs Attributes e.g. 'alt="description of image" data-value="hoge"'
 * @param bool $return Set true if you just want result without echo
 * @return string or null
 */
function image_tag_sp($src, $attrs = '', $return = false) {

  // attrの中からclassを抜き出す
  $class_val = '';
  if(preg_match('/class="(\w+)"/', $attrs, $match)){
    $class_val = " $match[1]";
    $attrs = preg_replace('/(class="\w+")/', '', $attrs);
  }

  $src_sp = preg_replace('/\.(\w+)$/', '-sp.$1', $src);

  $asset_src = asset_path("images/$src");
  $asset_src_sp = asset_path("images/$src_sp");

  $res =
    "<img class=\"pc$class_val\" $attrs src=\"$asset_src\">
    <img class=\"sp$class_val\" $attrs src=\"$asset_src_sp\">";
  if($return){
    return $res;
  }
  echo $res;
}
/**
 * $srcで指定したパスに自動で@2xをつけてsrcsetを設定する
 * @param string $src Path to image
 * @param string $attrs Attributes e.g. 'alt="description of image" data-value="hoge"'
 * @param bool $return Set true if you just want result without echo
 * @return string or null
 */
function img_tag($src, $attrs = '', $return = false) {
  $src_2x = preg_replace('/(\.\w+)$/', '@2x$1', $src);

  // 画像毎にhash値が違うので注意
  $asset_src = asset_path("images/$src");
  $asset_src_2x = asset_path("images/$src_2x");

  $res = "<img $attrs src=\"$asset_src\" srcset=\"$asset_src_2x 2x\">";
  if($return){
    return $res;
  }
  echo $res;
}
/**
 * $srcで指定したパスに自動で@2xをつけてsrcsetを設定する
 * PC/SP用の２つのimgタグ（それぞれpc,spクラスを付与）
 * @param string $src Path to image
 * @param string $attrs Attributes e.g. 'alt="description of image" data-value="hoge"'
 * @param bool $return Set true if you just want result without echo
 * @return string or null
 */
function img_tag_sp($src, $attrs = '', $return = false) {
  $src_2x = preg_replace('/(\.\w+)$/', '@2x$1', $src);

  // attrの中からclassを抜き出す
  $class_val = '';
  if(preg_match('/class="(\w+)"/', $attrs, $match)){
    $class_val = " $match[1]";
    $attrs = preg_replace('/(class="\w+")/', '', $attrs);
  }

  $src_sp = preg_replace('/\.(\w+)$/', '-sp.$1', $src);
  $src_sp_2x = preg_replace('/(\.\w+)$/', '@2x$1', $src_sp);

  $asset_src = asset_path("images/$src");
  $asset_src_2x = asset_path("images/$src_2x");
  $asset_src_sp = asset_path("images/$src_sp");
  $asset_src_sp_2x = asset_path("images/$src_sp_2x");

  $res = "<img class=\"pc$class_val\" $attrs src=\"$asset_src\" srcset=\"$asset_src_2x 2x\"><img class=\"sp$class_val\" $attrs src=\"$asset_src_sp\" srcset=\"$asset_src_sp_2x 2x\">";
  if($return){
    return $res;

  }
  echo $res;
}

/**
 * 指定した大きさのsvgの矩形を生成して返す
 *
 * @param string $ratio '{width}:{height}', e.g. '16:9'
 * @param string $attrs Attributes e.g. 'alt="description of image" data-value="hoge"'
 * @param bool $return Set true if you just want result without echo
 * @return string or null
 */
function rect_svg_tag($ratio, $attrs = '', $return = false) {
  $ratio = explode(':', $ratio);
  $width = $ratio[0];
  $height = $ratio[1];
  $res = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"$width\" height=\"$height\" viewBox=\"0 0 $width $height\" $attrs><rect width=\"$width\" height=\"$height\" fill=\"#ccc\"/></svg>";

  if($return){
    return $res;
  }
  echo $res;
}

/**
 * PCで16:9、SPで1:1にアスペクト比固定でトリムするdivタグを返す
 *
 * @param string $src Path to image | 相対パスの場合はasset_pathを通す
 * @param string $attrs Attributes e.g. 'alt="description of image" data-value="hoge"'
 * @param string $aspect_ratios [['ratio' => '16:9', 'attrs' => 'class="pc"'],['ratio' => '1:1', 'attrs' => 'class="sp"']]
 * @param bool $return Set true if you just want result without echo
 * @return string or null
 */
function trim_image_tag($src, $aspect_ratios, $attrs = '', $return = false) {

  // attrの中からclassを抜き出す
  $class_val = '';
  if(preg_match('/class="(\w+)"/', $attrs, $match)){
    $class_val = " $match[1]";
    $attrs = preg_replace('/(class="\w+")/', '', $attrs);
  }

  // attrの中からstyleを抜き出す
  $style_val = '';
  if(preg_match('/style="(.*)"/', $attrs, $match2)){
    $style_val = " $match2[1]";
    $attrs = preg_replace('/(style=".*")/', '', $attrs);
  }

  if(!is_absolute($src)){
    $src = asset_path("images/$src");
  }

  // アスペクト比固定用のSVG作成
  $svg_tags = '';
  // もし$aspect_ratiosが単数の場合でも配列可する
  if (isset($aspect_ratios['ratio'])) {
    $aspect_ratios = array($aspect_ratios);
  }
  foreach($aspect_ratios as $data){
    $_ratio = $data['ratio'];
    $_attrs = isset($data['attrs']) ? $data['attrs'] : '';
    $svg_tags .= rect_svg_tag($_ratio, $_attrs.' style="width:100%;height:auto;visibility:hidden;"', true);
  }

  $res = "<div class=\"trimedImage$class_val\" $attrs style=\"position:relative;background-position:center;background-size:cover;background-image:url($src);$style_val\">$svg_tags</div>";
  if($return){
    return $res;
  }
  echo $res;
}
/**
 * 絶対パスかどうかの判定
 * @param string $url
 * @return bool
 */
function is_absolute($url) {
  return (
    $purl=parse_url($url))!==false &&
    isset($purl['scheme']) &&
    isset($purl['host']) &&
    filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED
  );
}

/**
 * スパム対策したメールアドレスリンク
 * @param string $mail_address Mail Address
 * @param bool $return Set true if you just want result without echo
 * @return string or null
 */
function mail_link($mail_address, $return = false) {
  $content = preg_replace('/@/', '@<span style="display: none;">Anti Spam</span>', $mail_address);
  $res = "<a href=\"mailto:$mail_address\" target=\"_blank\" rel=\"noopener noreferrer\">$content</a>";
  if($return){
    return $res;
  }
  echo $res;
}



/**
 * SVGファイルをinline出力
 * @param string $src Path to image
 * @param string $attrs Attributes e.g. 'alt="description of image" data-value="hoge"'
 * @param bool $return Set true if you just want result without echo
 * @return string
 */
function inline_svg($src, $attrs = '', $return = false) {
  $src = get_template_directory()."/assets/images/$src";
  $res = file_get_contents($src);
  if ($attrs != '') {
    // attrをもともとのattributesとマージして置換
    preg_match('/<svg ([^\>]*)>/', $res, $_matches);
    $attr_array = array_merge_recursive(parseAttributes($_matches[1]), parseAttributes($attrs));
    $attrs = '';
    foreach ($attr_array as $key => $value) {
      if (is_array($value)) {
        $value = implode(' ', $value);
      }
      $attrs .= " $key=\"$value\"";
    }
    $res = str_replace($_matches[1], $attrs, $res);
  }
  if($return){
    return $res;
  }
  echo $res;
}
/**
 * HTMLのattributesを配列化
 * @param string $str : 'attr1="hoge" attr2="moge"'
 * @return array : array("attr1"=> "hoge", "attr2"=>"moge")
 */
function parseAttributes($str){
  preg_match_all('/(\w+)=[\'"]([^\'"]*)/', $str, $matches, PREG_SET_ORDER);
  $res = [];
  foreach($matches as $match){
      $attrName = $match[1];
      //parse the string value into an integer if it's numeric,
      // leave it as a string if it's not numeric,
      $attrValue = is_numeric($match[2])? (int)$match[2]: trim($match[2]);
      $res[$attrName] = $attrValue; //add match to results
  }
  return $res;
}

/**
 * 英数字をspan.enで囲って出力
 * @param string $html
 * @return string
 */
function wrap_en($html) {
  return preg_replace('/([0-9a-zA-Z]+)/', '<span class="en">$1</span>', $html);
}

/**
 * 改行を<br>に変換
 * @param string $html
 * @return string
 */
function nl2br($html) {
  return preg_replace('/(\r\n|\n|\r)/', '<br>', $html);
}

/**
 * wrap_en and nl2br
 * @param string $html
 * @return string
 */
function wrap_en_nl2br($html) {
  return nl2br(wrap_en($html));
}

/**
 * アンパサンドをdecode
 * wp_specialchars_decode()は、defaultで'<', '>'をdecodeし、<br>タグに影響するため使わない
 * @param string $text
 * @return string
 */
function not_esc_amp($text) {
  return str_replace('&#038;', '&', $text);
}

/**
 * <br>とアンパサンドがあっても大丈夫な wrap_en
 * @param string $text
 * @return string
 */
function wrap_en_with_br_amp($text) {
  $placeholder = '【【改行】】';
  $text = preg_replace('/<br>/', $placeholder, $text);
  $text = not_esc_amp($text);
  $text = wrap_en($text);
  return preg_replace("/$placeholder/", '<br>', $text);
}

/**
 * wrap_en + \n →　<br>
 * @param string $text
 * @return string
 */
function wrap_en_and_n2br($text) {
  $placeholder = '【【改行】】';
  $text = preg_replace('/\\\n/', $placeholder, $text);
  $text = wrap_en($text);
  return preg_replace("/$placeholder/", '<br>', $text);
}

/**
 * Return class attribute
 *
 * @param string $route
 * @param string $additional_class : dclass names divided by whitespace
 *
 * @return string
 */
function get_class_attr($route, $additional_class = '') {
  $classes = array();
  if ($_SERVER['REQUEST_URI'] === '/') {
    if ($route === '/') {
      $classes[] = 'current';
    }
  } else if(strrpos($route, $_SERVER['REQUEST_URI']) !== false) {
    $classes[] = 'current';
  }
  $classes_str = implode(' ', $classes);
  return "class=\"$classes_str $additional_class\"";
}

/**
 * サイト内URLかどうかを判定し、サイト内URLであれば'target="_blank"'をつけない
 * @param string $href
 * @param bool $return Set true if you just want result without echo
 * @return string
 */
function href_target_rel($href, $return = false) {
  $res = "href=\"$href\"";
  if( !preg_match( '{^' .home_url(). '}', $href ) ) {
    $res .= " target=\"_blank\" rel=\"noopener noreferrer\"";
  }
  if($return){
    return $res;
  }
  echo $res;
}



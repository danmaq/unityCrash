<?php

error_reporting(E_ALL | E_STRICT);

require 'SplClassLoader.php';
$loader = new SplClassLoader('UnityCrash', 'lib/vendors');
$loader->register();

use UnityCrash\State\Context;
use UnityCrash\MyState\ControllerState;

$context = new Context(ControllerState::getInstance());
//$context->loop();

////////// 本番実装は、ここから下をまるっと消す


header('text/html; charset=UTF-8');
header('Pragma: no-cache');
header('Cache-Control: no-cache');

?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja">
  <head>
    <meta charset="UTF-8"/>
    <meta name="author" content="danmaq"/>
    <meta name="viewport" content="width=320"/>
    <title>#Unity落ちた - danmaq</title>
    <link href="http://twitter.com/danmaq" rel="Author"/>
    <!-- 評価中 -->
  </head>
  <body>
  	<h1>Unity落ちた</h1>
  	<a href="https://twitter.com/intent/tweet?button_hashtag=Unity%E8%90%BD%E3%81%A1%E3%81%9F&text=Unity%E8%90%BD%E3%81%A1%E3%81%9F"
  		class="twitter-hashtag-button"
  		data-lang="ja"
  		data-size="large"
  		data-related="danmaq"
  		data-url="http://dmq.cm/unitycrash"
  		data-dnt="true">#Unity落ちた をツイートする</a>
	<script type="text/javascript">
		!function(d,s,id)
		{
			var js,fjs = d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
			if (!d.getElementById(id))
			{
				js = d.createElement(s);
				js.id = id;
				js.src = p+'://platform.twitter.com/widgets.js';
				fjs.parentNode.insertBefore(js,fjs);
			}
		}(document, 'script', 'twitter-wjs');
	</script>
	<h2>TODO</h2>
	<ul>
		<li>ワンクリックでツイート(今は2クリック)</li>
		<li>落ちた回数・生き延びた時間</li>
		<li>デザインとかもうちょっと凝ってみる</li>
		<li>デスクトップ常駐アプリに</li>
		<li>スマホアプリに</li>
	</ul>
	<hr />
<?php
$context->phase();
if (isset($_SERVER['QUERY_STRING']))
{
	echo getcwd() . "\n";
	echo $_SERVER['QUERY_STRING'] . "\n";
}
?>
	<hr />
	<address>by <a href="http://danmaq.com/">danmaq</a></address>
  </body>
</html>

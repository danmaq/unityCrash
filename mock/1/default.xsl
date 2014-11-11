<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:xhtml="http://www.w3.org/1999/xhtml"
	xmlns="http://www.w3.org/1999/xhtml"
	exclude-result-prefixes="xhtml">
	<xsl:output method="xml" encoding="UTF-8" indent="yes" media-type="application/xhtml+xml" />

	<!-- メイン。 -->
	<xsl:template match="/body">

		<!-- HTML5のためのDOCTYPE宣言。 -->
		<xsl:text disable-output-escaping='yes'>
&lt;!DOCTYPE html&gt;
</xsl:text>
		<!-- 出力のインデントが乱れるため、意図して改行しています。 -->

		<html xml:lang="ja">
			<head>
				<meta charset="UTF-8" />
				<xsl:if test="contains(@ua, ' IE ') or contains(@ua, ' MSIE ')">
					<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				</xsl:if>
				<meta name="application-name" content="#Unity落ちた" />
				<meta name="author" content="danmaq" />
				<meta name="viewport" content="width=789" />
				<title>#Unity落ちた</title>
				<link href="default.css" rel="StyleSheet" />
				<link href="http://twitter.com/danmaq" rel="Author" />
				<xsl:comment> 評価中 </xsl:comment>
			</head>
			<body>
				<header>
					<h1>#Unity落ちた</h1>
				</header>
				<section>
					<form method="post" action="./">
						<fieldset>
							<p>
								<input value="Unity落ちた" /> - http://dmq.cm/unitycrash #Unity落ちた
							</p>
							<input type="submit" value="ツイート" />
						</fieldset>
					</form>
				</section>
				<section>
					<h2>TODO</h2>
					<ul>
						<li>ワンクリックでツイート(今は2クリック)</li>
						<li>落ちた回数・生き延びた時間</li>
						<li>デザインとかもうちょっと凝ってみる</li>
						<li>デスクトップ常駐アプリに</li>
						<li>スマホアプリに</li>
					</ul>
				</section>
				<section>
				<h2>HISTORY</h2>
					<ul>
						<li>2014-11-11 大手術開始</li>
						<li>2014-11-04 ツイートボタンの動作がおかしいので最新のスクリプトに置き換えた。が……駄目っ……！</li>
						<li>2014-05-30 サービス開始</li>
					</ul>
				</section>
<!--
				<xsl:apply-templates select="user|search" />
 -->
				<footer>
					<hr />
					<address>by <a href="http://danmaq.com/">danmaq</a></address>
				</footer>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>

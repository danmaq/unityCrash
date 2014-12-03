<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:local="local:local"
	xmlns="http://www.w3.org/1999/xhtml"
	exclude-result-prefixes="local">
	<xsl:output method="xml" encoding="UTF-8" indent="yes" media-type="application/xhtml+xml" />

	<xsl:param name="sourceLanguage">
		<xsl:value-of select="/body/@xml:lang" />
	</xsl:param>

	<xsl:param name="language">
		<xsl:value-of select="/body/@xml:lang" />
	</xsl:param>

	<!-- <xsl:param name="language" select="'zh'" /> -->

	<local:resources>
		<local:resource place="title" lang="ja">#Unity落ちた</local:resource>
		<local:resource place="tweet" lang="ja">ツイート</local:resource>
		<local:resource place="headCause" lang="ja">作った経緯</local:resource>
		<local:resource place="bodyCause" lang="ja">Unity落ちた</local:resource>
		<local:resource place="idea" lang="ja">TODO</local:resource>
		<local:resource place="history" lang="ja">更新履歴</local:resource>

		<local:resource place="title" lang="en">#Unity3DCrashed</local:resource>
		<local:resource place="tweet" lang="en">Tweet now</local:resource>
		<local:resource place="headCause" lang="en">Cause of produced</local:resource>
		<local:resource place="bodyCause" lang="en">Crashed Unity3D.</local:resource>
		<local:resource place="idea" lang="en">Idea [Only Japanese]</local:resource>
		<local:resource place="history" lang="en">History [Only Japanese]</local:resource>

		<local:resource place="title" lang="zh">#Unity3DCrashed</local:resource>
		<local:resource place="tweet" lang="zh">咕噥</local:resource>
		<local:resource place="headCause" lang="zh">產生的原因</local:resource>
		<local:resource place="bodyCause" lang="zh">Crashed Unity3D.</local:resource>
		<local:resource place="idea" lang="zh">計劃 [日語]</local:resource>
		<local:resource place="history" lang="zh">歷史 [日語]</local:resource>
	</local:resources>

	<xsl:param name="title">
		<xsl:value-of select="$resources/local:resource[@place = 'title' and @lang = $language]" />
	</xsl:param>

	<xsl:param name="cause">
		<xsl:value-of select="$resources/local:resource[@place = 'bodyCause' and @lang = $language]" />
	</xsl:param>

	<xsl:variable name="resources" select="document('')/*/local:resources" />

	<!-- メイン。 -->
	<xsl:template match="/body">

		<!-- HTML5のためのDOCTYPE宣言。 -->
		<xsl:text disable-output-escaping='yes'>
&lt;!DOCTYPE html&gt;
</xsl:text>
		<!-- 出力のインデントが乱れるため、意図して改行しています。 -->

		<html>
			<xsl:attribute name="xml:lang">
				<xsl:value-of select="$language" />
			</xsl:attribute>
			<head>
				<meta charset="UTF-8" />
				<xsl:if test="contains(@ua, ' IE ') or contains(@ua, ' MSIE ')">
					<meta http-equiv="X-UA-Compatible" content="IE=edge" />
				</xsl:if>
				<meta name="application-name">
					<xsl:attribute name="content">
						<xsl:value-of select="$title" />
					</xsl:attribute>
				</meta>
				<meta name="author" content="danmaq" />
				<meta name="viewport" content="width=780" />
				<title><xsl:value-of select="$title" /></title>
				<link href="default.css" rel="StyleSheet" />
				<link href="favicon.ico" rel="icon" type="image/vnd.microsoft.icon" />
				<link href="favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
				<link href="http://twitter.com/danmaq" rel="Author" />
			</head>
			<body>
				<div class="bg">&#160;</div>
				<div class="main">
					<header>
						<h1><xsl:value-of select="$title" /></h1>
					</header>
					<section>
						<form method="post" action="#">
							<fieldset>
								<xsl:if test="@message">
									<p>
										<xsl:attribute name="xml:lang">
											<xsl:value-of select="$sourceLanguage" />
										</xsl:attribute>
										<xsl:attribute name="class">
											<xsl:choose>
												<xsl:when test="@result = 'true'">info</xsl:when>
												<xsl:otherwise>warn</xsl:otherwise>
											</xsl:choose>
										</xsl:attribute>
										<xsl:value-of select="@message" />
									</p>
								</xsl:if>
								<p class="textfield">
									<input name="message" id="message" type="text" maxlength="89">
										<xsl:attribute name="xml:lang">
											<xsl:value-of select="$sourceLanguage" />
										</xsl:attribute>
										<xsl:attribute name="value"><xsl:value-of select="@query" /></xsl:attribute>
									</input>
									<label for="message"> http://dmq.cm/unitycrash <xsl:value-of select="$title" /></label>
								</p>
								<p>
									<input type="submit">
										<xsl:attribute name="value">
											<xsl:value-of select="$resources/local:resource[@place = 'tweet' and @lang = $language]" />
										</xsl:attribute>
									</input>
								<!--	<img src="ads.png" width="728" height="90" /> -->
									<script async="async" src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
									<!-- ください -->
									<ins class="adsbygoogle"
									     style="display:inline-block;width:728px;height:90px"
									     data-ad-client="ca-pub-1989949419175195"
									     data-ad-slot="1743217019">&#160;</ins>
									<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
								</p>
							</fieldset>
						</form>
					</section>
					<section>
						<h2><xsl:value-of select="$resources/local:resource[@place = 'headCause' and @lang = $language]" /></h2>
						<ul>
							<li><xsl:value-of select="$cause" /></li>
							<li><xsl:value-of select="$cause" /></li>
							<li><xsl:value-of select="$cause" /></li>
							<li><xsl:value-of select="$cause" /></li>
							<li><xsl:value-of select="$cause" /></li>
						</ul>
					</section>
					<section>
						<h2><xsl:value-of select="$resources/local:resource[@place = 'idea' and @lang = $language]" /></h2>
						<ul xml:lang="ja">
							<li>落ちた回数・生き延びた時間</li>
							<li>デザインとかもうちょっと凝ってみる</li>
							<li>デスクトップ常駐アプリに</li>
							<li>スマホアプリに</li>
						</ul>
					</section>
					<section>
					<h2><xsl:value-of select="$resources/local:resource[@place = 'history' and @lang = $language]" /></h2>
						<ul xml:lang="ja">
							<li>2014-11-11 大手術開始</li>
							<li>2014-11-04 ツイートボタンの動作がおかしいので最新のスクリプトに置き換えた。が……駄目っ……！</li>
							<li>2014-05-30 サービス開始</li>
						</ul>
					</section>
					<footer>
						<hr />
						<address xml:lang="en">
							<p>Copyright © 2014 <a href="http://danmaq.com/" hreflang="ja-JP">danmaq</a> All rights reserved.</p>
							<ul>
								<li>Produced: <a href="https://twitter.com/danmaq" hreflang="ja-JP">Mc (MAKU)</a></li>
								<li>Illustration: <a href="https://twitter.com/RidoCenter" hreflang="ja-JP">Rido</a></li>
							</ul>
						</address>
					</footer>
				</div>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>

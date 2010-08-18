<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />

<xsl:template match="section/multifeed">
<div>
  <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
  <xsl:attribute name="class">section</xsl:attribute>
  <div>
    <xsl:for-each select="../multifeed/head/item">
      <xsl:apply-templates select="." />
    </xsl:for-each>
  </div>
  <div>
    <!-- <xsl:value-of select="./body" /> -->
    <xsl:for-each select="../multifeed/body/item">
      <xsl:sort select="substring(./pubDate,1,4)" data-type="number" order="descending" />
      <xsl:sort select="substring(./pubDate,6,2)" data-type="number" order="descending" />
      <xsl:sort select="substring(./pubDate,9,2)" data-type="number" order="descending" />
      <!-- <xsl:sort select="substring(./title,1,5)" /> -->
      <br />
      <xsl:apply-templates select="." />
    </xsl:for-each>
  </div>
  <!-- <p><xsl:value-of select="./foot" /></p> -->
</div>
</xsl:template>

<xsl:template match="multifeed/head/item">
    <h1>
      <a>
        <xsl:attribute name="href">
          <xsl:value-of select="./link" />
        </xsl:attribute>
        <xsl:value-of select="./title" />
      </a>
    </h1>
    <p>
      <xsl:value-of select="./desc" />
    </p>
</xsl:template>

<xsl:template match="multifeed/body/item">
  <li>
    <xsl:attribute name="class">item</xsl:attribute>
    <span>
      <xsl:attribute name="class">author</xsl:attribute>
      <xsl:value-of select="./author" />
    </span>
    <xsl:if test="pubDate">
      <xsl:value-of select="./pubDate" />
      <xsl:text>: </xsl:text>
    </xsl:if>
    <xsl:choose>
      <xsl:when test="link">
      <a>
        <xsl:attribute name="href">
          <xsl:value-of select="./link" />
        </xsl:attribute>
        <xsl:value-of select="./title" />
      </a>
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="./title" />
      </xsl:otherwise>
    </xsl:choose>
    <xsl:if test="desc">
      <p>
        <xsl:value-of select="./desc" />
      </p>
    </xsl:if>
  </li>
</xsl:template>

<xsl:template match="toggler/multifeed">
<div>
  <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
  <xsl:attribute name="class">toggler</xsl:attribute>
  <a>
    <xsl:attribute name="class">togglerbutton</xsl:attribute>
    <xsl:attribute name="href">?page=<xsl:value-of select="./name" /></xsl:attribute>
    <xsl:element name="img">
      <xsl:attribute name="class">togglericon</xsl:attribute>
      <xsl:attribute name="src">
        <xsl:value-of select="./icon" />
      </xsl:attribute>
    </xsl:element>
    <div class="togglerbuttontext">
      <xsl:value-of select="./head" />
    </div>
  </a>
  <div>
    <xsl:attribute name="class">togglercontent</xsl:attribute>
    <xsl:attribute name="class">hidden</xsl:attribute>
    <div>
      <xsl:attribute name="class">togglercontentbody</xsl:attribute>
	  
      <!-- <xsl:value-of select="./body" /> -->
<!--      <xsl:for-each select="body/item">
        <br />
        <xsl:apply-templates select="." />
      </xsl:for-each> -->
	  <li><img src="gfx/load.gif" /></li>
    </div>
  </div>
</div>
</xsl:template>

<xsl:template match="ajax/multifeed">
	<div>
		<xsl:attribute name="class">togglercontentbody</xsl:attribute>
			<a href="link" id="feedconfig">
				<img src="gfx/icons/pref.png" alt="Prenumerera" id="feedconfig"/>
			</a>
			<xsl:apply-templates select="picker"/>
			<ul id="feedlist">
			 <xsl:for-each select="body/item">
					<br />
					<xsl:apply-templates select="." />
			 </xsl:for-each>
			</ul>
	</div>
</xsl:template>

<xsl:template match="picker">
	<form name="feedform" method="post" action="index.php" id="feedform" class="hidden">
		<ul>
			<xsl:for-each select="box">
				<li>
					<input type="checkbox">
						<xsl:attribute name="name">
							<xsl:value-of select="./name" />
						</xsl:attribute>
						<xsl:if test="checked">
							<xsl:attribute name="checked">
								<xsl:value-of select="./checked" />
							</xsl:attribute>
						</xsl:if>
					</input>
						<span> <xsl:value-of select="./name" /> </span>
				</li>
			</xsl:for-each>
		</ul>
		<xsl:apply-templates select="button" />
	</form>
</xsl:template>

<xsl:template match="button">
		<input name='feedcookie'>
			<xsl:attribute name="value">
				<xsl:value-of select="value"/>
			</xsl:attribute>
			<xsl:attribute name="type">
				<xsl:value-of select="type"/>
			</xsl:attribute>
		</input>
</xsl:template>

<xsl:template match="teaser/multifeed">
<div>
  <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
  <xsl:attribute name="class">teaser</xsl:attribute>
  <h1>
    <xsl:attribute name="class">teasertitle</xsl:attribute>
    <xsl:value-of select="./head" />
  </h1>
  <div>
    <xsl:attribute name="class">teasertext</xsl:attribute>
    <xsl:choose>
      <xsl:when test="body/link">
        <a>
          <xsl:attribute name="href">
            <xsl:value-of select="./body/link" />
          </xsl:attribute>
          <xsl:value-of select="./body/title" />
        </a>
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="./body/title" />
      </xsl:otherwise>
    </xsl:choose>
  </div>
  <a>
    <xsl:attribute name="class">teaserlinktext</xsl:attribute>
    <xsl:attribute name="href">?page=<xsl:value-of select="name" />
      </xsl:attribute>
    <xsl:value-of select="./foot" />
  </a>
</div>
</xsl:template>

</xsl:stylesheet>
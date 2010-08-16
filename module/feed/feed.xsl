<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />

<xsl:template match="section/feed">
<div>
  <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
  <xsl:attribute name="class">section</xsl:attribute>
  <div><xsl:apply-templates select="../feed/head" /></div>
    <!-- <xsl:value-of select="./body" /> -->
    <ul>
      <xsl:for-each select="../feed/body/item">
        <xsl:apply-templates select="." />
      </xsl:for-each>
    </ul>
  <!-- <p><xsl:value-of select="./foot" /></p> -->
</div>
</xsl:template>

<xsl:template match="feed/head">
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

<xsl:template match="feed/body/item">
  <li>
    <xsl:attribute name="class">item</xsl:attribute>
    <xsl:if test="pubDate">
      <span>
        <xsl:attribute name="class">timestamp</xsl:attribute>
        <xsl:value-of select="./pubDate" />
      </span>
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

<xsl:template match="toggler/feed">
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
    <span class="togglerbuttontext">
      <xsl:value-of select="./head" />
    </span>
  </a>
  <div>
    <xsl:attribute name="class">togglercontent</xsl:attribute>
    <xsl:attribute name="class">hidden</xsl:attribute>
    <ul>
      <xsl:attribute name="class">togglercontentbody</xsl:attribute>
      <!-- <xsl:value-of select="./body" /> -->
      <xsl:for-each select="../feed/body/item">
        <xsl:apply-templates select="." />
      </xsl:for-each>
    </ul>
  </div>
</div>
</xsl:template>

<xsl:template match="teaser/feed">
<div>
  <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
  <xsl:attribute name="class">teaser</xsl:attribute>
  <h1>
    <xsl:attribute name="class">teasertitle</xsl:attribute>
    <xsl:value-of select="./head" />
  </h1>
  <div>
    <xsl:attribute name="class">teasertext</xsl:attribute>
    <xsl:if test="./body/pubDate">
      <span>
        <xsl:attribute name="class">timestamp</xsl:attribute>
        <xsl:value-of select="./body/pubDate" />
      </span>
      <xsl:text>: </xsl:text>
    </xsl:if>
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
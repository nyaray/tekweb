<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />

<xsl:template match="section/feed">
<div>
  <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
  <xsl:attribute name="class">section</xsl:attribute>
  <div><xsl:apply-templates select="head" /></div>
  <div>
    <!-- <xsl:value-of select="./body" /> -->
    <xsl:for-each select="body/item">
      <xsl:apply-templates select="." />
    </xsl:for-each>
  </div>
  <!-- <p><xsl:value-of select="./foot" /></p> -->
</div>
</xsl:template>

<xsl:template match="head">
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

<xsl:template match="body/item">
  <div>
    <xsl:attribute name="class">item</xsl:attribute>
    <a>
      <xsl:attribute name="href">
        <xsl:value-of select="./link" />
      </xsl:attribute>
      <xsl:value-of select="./title" />
    </a>
    <p>
      <xsl:value-of select="./desc" />
    </p>
  </div>
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
      <xsl:for-each select="body/item">
        <xsl:apply-templates select="." />
      </xsl:for-each>
    </div>
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
    <a>
      <xsl:attribute name="href">
        <xsl:value-of select="./body/link" />
      </xsl:attribute>
      <xsl:value-of select="./body/title" />
    </a>
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
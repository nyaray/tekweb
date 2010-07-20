<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />

<xsl:template match="section/feed">
<div>
  <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
  <xsl:attribute name="class">section</xsl:attribute>
  <h1><xsl:value-of select="./head" /></h1>
  <p><xsl:value-of select="./feed" /></p>
  <p><xsl:value-of select="./foot" /></p>
</div>
</xsl:template>

<xsl:template match="toggler/feed">
<div>
  <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
  <xsl:attribute name="class">toggler</xsl:attribute>
  <div>
    <xsl:attribute name="class">togglerbutton</xsl:attribute>

      <xsl:element name="img">
        <xsl:attribute name="class">togglericon</xsl:attribute>
        <xsl:attribute name="src"><xsl:value-of select="./icon" />
          </xsl:attribute>
      </xsl:element>

    <div class="togglerbuttontext"><xsl:value-of select="./head" /></div>
  </div>
  <div>
    <xsl:attribute name="class">togglercontent</xsl:attribute>
    <div>
      <xsl:attribute name="class">togglercontentbody</xsl:attribute>
      <xsl:value-of select="./json" />
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
    <xsl:value-of select="./json" />
  </div>
  <a>
    <xsl:attribute name="class">teaserlinktext</xsl:attribute>
    <xsl:attribute name="href">?page=<xsl:value-of select="name" />
      </xsl:attribute>
    <xsl:value-of select="./foot" />
  </a>
</div>
</xsl:template>
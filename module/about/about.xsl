<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />

<xsl:template match="section/about">
<img class="divider" src="./gfx/dot.png" />
<div>
  <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
  <xsl:attribute name="class">section</xsl:attribute>
  <h1>
    <xsl:attribute name="class">head</xsl:attribute>
    <xsl:value-of select="./head" />
  </h1>
  <ul>
    <xsl:for-each select="../about/body/item">
      <li>
        <xsl:attribute name="class">person</xsl:attribute>
        <xsl:apply-templates select="." />
      </li>
    </xsl:for-each>
  </ul>
</div>
</xsl:template>

<xsl:template match="toggler/about">
<div>
  <xsl:attribute name="id"><xsl:value-of select="./name" /></xsl:attribute>
  <xsl:attribute name="class">toggler</xsl:attribute>
  <a>
    <xsl:attribute name="href">?page=<xsl:value-of select="./name" /></xsl:attribute>
    <xsl:attribute name="class">togglerbutton</xsl:attribute>
    <xsl:element name="img">
      <xsl:attribute name="class">togglericon</xsl:attribute>
      <xsl:attribute name="src"><xsl:value-of select="./icon" /></xsl:attribute>
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
      <xsl:for-each select="../about/body/item">
        <li>
          <xsl:apply-templates select="." />
        </li>
      </xsl:for-each>
    </ul>
  </div>
</div>
</xsl:template>

</xsl:stylesheet>
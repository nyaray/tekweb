<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />

<xsl:template match="section/timeedit">
<div>
  <xsl:attribute name="id"><xsl:value-of select="name" /></xsl:attribute>
  <xsl:attribute name="class">section</xsl:attribute>

  <!-- Head -->
  <h1><xsl:value-of select="head" /></h1>

  <!-- Events -->
  <div>
    <xsl:attribute name="class">events</xsl:attribute>

    <ul>
      <xsl:for-each select="events/node()">
        <li>
          <xsl:apply-templates select="." />
        </li>
      </xsl:for-each>
    </ul>
  </div>
</div>
</xsl:template>

<xsl:template match="event">
  <xsl:value-of select="." />
</xsl:template>
</xsl:stylesheet>
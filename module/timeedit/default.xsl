<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output 
  type="none"
  />

<xsl:template match="calendar">
<section>
  <timeedit>
    <head></head>

    <xsl:apply-templates select="events" />

    <xsl:copy-of select="course" />
  </timeedit>
</section>
</xsl:template>

<xsl:template match="events">
  <xsl:copy-of select=".">
    <xsl:apply-templates select="@*|node()"/>
  </xsl:copy-of>
</xsl:template>

<xsl:template match="weekyear">
  <separator>Vecka <xsl:value-of select="." /></separator>
</xsl:template>

<xsl:template match="event">
  <xsl:apply-templates select="." />
</xsl:template>

<xsl:template match="weekday">
  <xsl:choose>
    <xsl:when test="weekday/text()"></xsl:when>
    <xsl:otherwise><xsl:copy-of select="weekday" /></xsl:otherwise> 
  </xsl:choose>
</xsl:template>

<xsl:template match="text()">
  <xsl:value-of select="normalize-space(.)"/>
</xsl:template>
</xsl:stylesheet>
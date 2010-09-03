<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output type="none" />

<xsl:template match="node()|@*">
  <xsl:copy-of select="@*|node()" />
</xsl:template>

<xsl:template match="/">
<section>
  <timeedit>
    <xsl:apply-templates select="calendar/*" />
  </timeedit>
</section>
</xsl:template>

<!-- pass the error through -->
<xsl:template match="calendar/error">
  <xsl:copy-of select="." />
</xsl:template>

<!-- a link to the configuration page -->
<xsl:template match="calendar/conf">
<conf>
  <xsl:copy-of select="*" />
</conf>
</xsl:template>

<!-- Viewing the calendar -->
<xsl:template match="calendar/view">
<view>
  <head><xsl:value-of select="head" /></head>
  <name><xsl:value-of select="name" /></name>
  <xsl:copy-of select="conf" />
  <xsl:apply-templates select="events" />
  <xsl:copy-of select="course" />
</view>
</xsl:template>

<xsl:template match="events">
  <events><xsl:apply-templates select="weekyear|event" /></events>
</xsl:template>

<xsl:template match="weekyear">
  <weekyear>
    <xsl:text>Vecka </xsl:text>
    <xsl:value-of select="." />
  </weekyear>
</xsl:template>

<xsl:template match="event">
  <event>
    <xsl:copy-of select="*" />
  </event>
</xsl:template>


<!-- Viewing the search form -->
<xsl:template match="calendar/search">
<search>
  <xsl:copy-of select="@*|node()" />
</search>
</xsl:template>
</xsl:stylesheet>

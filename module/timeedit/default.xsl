<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output type="none" />

<xsl:template match="node()|@*">
  <xsl:copy-of select="@*|node()" />
</xsl:template>

<xsl:template match="/">
  <xsl:apply-templates select="calendar/*" />
</xsl:template>

<!-- Viewing the calendar -->
<xsl:template match="calendar/view">
<section>
  <timeedit>
    <view>
      <head><xsl:value-of select="head" /></head>
      <xsl:apply-templates select="events" />
      <xsl:copy-of select="course" />
    </view>
  </timeedit>
</section>
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
<section>
  <timeedit>
    <search>
      <xsl:copy-of select="@*|node()" />
    </search>
  </timeedit>
</section>
</xsl:template>
</xsl:stylesheet>
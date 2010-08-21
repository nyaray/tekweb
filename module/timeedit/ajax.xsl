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



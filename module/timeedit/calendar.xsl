<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output
  method="xml"
  disable-output-escaping="yes" />
<!-- <xsl:output
  method="xml"
  encoding="iso-8859-1"
  omit-xml-declaration="no" /> -->

<xsl:strip-space elements="*" />

<xsl:template match="node()|@*">
  <xsl:copy>
    <xsl:apply-templates select="@*|node()" />
  </xsl:copy>
</xsl:template>

<xsl:template match="/">
  <calendar>
    <xsl:apply-templates select="./*" />
  </calendar>
</xsl:template>

<xsl:template match="div">
  <xsl:apply-templates select="./*" />
</xsl:template>

<xsl:template match="div/table">
  <xsl:apply-templates select="./*" />
</xsl:template>

<xsl:template match="div/table/tr">
  <course>
    <code><xsl:value-of select="./td[3]" /></code>
    <name><xsl:value-of select="./td[5]" /></name>
  </course>
</xsl:template>

<xsl:template match="table">
  <events>
    <xsl:for-each select="./tr">
      <xsl:choose>
        <xsl:when test="td/@class='blank'">
        </xsl:when>
        <xsl:otherwise>
          <xsl:choose>
            <!-- FIXME: Explode the week and the year, make some
              sort of header like node -->
            <xsl:when test="substring(td[1], 1, 1) = 'V'">
              <weekyear>
                <xsl:value-of select="substring(., 7, 8)" />
              </weekyear>
            </xsl:when>
            <xsl:otherwise>
              <event>
                <weekday><xsl:value-of select="td[1]" /></weekday>
                <!-- FIXME: when the date is missing, we make it's
                  value 'inherit' -->
                <date><xsl:value-of select="td[2]" /></date>
                <time><xsl:value-of select="td[3]" /></time>
                <programmes><xsl:value-of select="td[4]" /></programmes>
                <name><xsl:value-of select="td[5]" /></name>
                <subcourse><xsl:value-of select="td[6]" /></subcourse>
                <reason><xsl:value-of select="td[7]" /></reason>
                <location><xsl:value-of select="td[8]" /></location>
                <organiser><xsl:value-of select="td[9]" /></organiser>
                <note><xsl:value-of select="td[10]" /></note>
              </event>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:otherwise>
      </xsl:choose>
    </xsl:for-each>
  </events>
</xsl:template>

<xsl:template match="td">
  <xsl:apply-templates select="font" />
</xsl:template>

<xsl:template match="font">
  <xsl:value-of select="." />
</xsl:template>

<xsl:template match="html|body">
  <xsl:apply-templates select="node()" />
</xsl:template>
<xsl:template match="head" />
<xsl:template match="br" />

<xsl:template match="@border" />
<xsl:template match="@style" />
<xsl:template match="@nowrap" />
<xsl:template match="@colspan" />
<xsl:template match="@cellpadding" />
<xsl:template match="@cellspacing" />
</xsl:stylesheet>
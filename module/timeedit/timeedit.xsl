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
  <xsl:apply-templates select="events" />
</div>
</xsl:template>

<xsl:template match="events">
  <ul>
    <xsl:for-each select="weekyear|event">
      <li>
        <xsl:apply-templates select="." />
      </li>
    </xsl:for-each>
  </ul>
</xsl:template>

<xsl:template match="weekyear">
  <xsl:attribute name="class">weekyear</xsl:attribute>
  <h2><xsl:value-of select="." /></h2>
</xsl:template>

<xsl:template match="event">
  <xsl:attribute name="class">event</xsl:attribute>

  <xsl:choose>
  <xsl:when test="weekday|date">
  <div>
    <xsl:attribute name="class">day</xsl:attribute>
      <xsl:if test="weekday">
        <div>
          <xsl:attribute name="class">weekday bold</xsl:attribute>
          <xsl:value-of select="weekday" />
        </div>
      </xsl:if>

      <xsl:if test="date">
        <div>
          <xsl:attribute name="class">date bold</xsl:attribute>
          <xsl:value-of select="date" />
        </div>
      </xsl:if>
  </div>
  </xsl:when>

  <xsl:otherwise>
    <div><xsl:attribute name="class">hiddenBox</xsl:attribute></div>
  </xsl:otherwise>
  </xsl:choose>

  <div>
    <xsl:attribute name="class">eventwrap</xsl:attribute>

    <xsl:if test="name">
      <div>
        <xsl:attribute name="class">name</xsl:attribute>
        <xsl:value-of select="name" />
      </div>
    </xsl:if>

    <xsl:if test="reason">
      <div>
        <xsl:attribute name="class">reason</xsl:attribute>
        <xsl:value-of select="reason" />
      </div>
    </xsl:if>

    <hr />

    <xsl:if test="time|location">
      <div>
        <xsl:attribute name="class">timelocation</xsl:attribute>

        <xsl:if test="time">
          <div>
            <xsl:attribute name="class">time</xsl:attribute>
            <xsl:value-of select="time" />
          </div>
        </xsl:if>

        <xsl:if test="location">
          <div>
            <xsl:attribute name="class">location</xsl:attribute>
            <xsl:value-of select="location" />
          </div>
        </xsl:if>
      </div>
    </xsl:if>

    <xsl:if test="programmes">
      <div>
        <xsl:attribute name="class">programmes</xsl:attribute>
        <xsl:value-of select="programmes" />
      </div>
    </xsl:if>

    <xsl:if test="subcourse">
      <div>
        <xsl:attribute name="class">subcourse</xsl:attribute>
        <xsl:value-of select="subcourse" />
      </div>
    </xsl:if>

    <xsl:if test="organiser">
      <div>
        <xsl:attribute name="class">organiser</xsl:attribute>
        <xsl:value-of select="organiser" />
      </div>
    </xsl:if>

    <xsl:if test="note">
      <div>
        <xsl:attribute name="class">note bold</xsl:attribute>
        <xsl:value-of select="note" />
      </div>
    </xsl:if>
  </div>
</xsl:template>
</xsl:stylesheet>
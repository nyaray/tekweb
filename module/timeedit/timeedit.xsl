<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />

<xsl:template match="section/timeedit">
<div>
  <xsl:apply-templates select="./*" mode="section" />
</div>
</xsl:template>

<xsl:template match="toggler/timeedit">
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
    <span class="togglerbuttontext">
      <xsl:value-of select="./head" />
    </span>
  </a>
  <div>
    <xsl:attribute name="class">togglercontent</xsl:attribute>
    <xsl:attribute name="class">hidden</xsl:attribute>
    <div>
      <xsl:attribute name="class">togglercontentbody</xsl:attribute>
    </div>
  </div>
</div>

</xsl:template>

<xsl:template match="timeedit/conf">
  <xsl:copy-of select="*" />
</xsl:template>

<xsl:template match="ajax/timeedit">
<div>
  <!-- Head -->
  <xsl:if test="head">
    <h1><xsl:value-of select="head" /></h1>
  </xsl:if>

  <!-- Config link -->
  <div><xsl:copy-of select="conf/*" /></div>

  <!-- Events -->
  <xsl:choose>
    <xsl:when test="view/events">
      <xsl:apply-templates select="view/events" />
    </xsl:when>

    <xsl:otherwise>
      Det finns inget schemalagt inom de närmsta
      två-tre veckorna för de valda kurserna.
    </xsl:otherwise>
  </xsl:choose>
</div>
</xsl:template>

<xsl:template match="timeedit/view" mode="section">
<div>
  <xsl:attribute name="id"><xsl:value-of select="name" /></xsl:attribute>
  <xsl:attribute name="class">section</xsl:attribute>

  <!-- Head -->
  <xsl:if test="head">
    <h1><xsl:value-of select="head" /></h1>
  </xsl:if>

  <!-- Events -->
  <xsl:choose>
    <xsl:when test="events">
      <xsl:apply-templates select="events" />
    </xsl:when>

    <xsl:otherwise>
      Det finns inget schemalagt inom de närmsta
      två-tre veckorna för de valda kurserna.
    </xsl:otherwise>
  </xsl:choose>
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

<xsl:template match="timeedit/search" mode="section">
<div>
  <xsl:attribute name="id"><xsl:value-of select="name" /></xsl:attribute>
  <h1><xsl:value-of select="head" /></h1>

  <!-- <xsl:apply-templates select="form/instructions" /> -->
  <form>
    <xsl:attribute name="method">
      <xsl:value-of select="form/@method" />
    </xsl:attribute>
    <xsl:attribute name="name">timeeditform</xsl:attribute>

    <xsl:copy-of select="input[@name = 'view' or @name = 'page']" />
    <fieldset>
      <legend>Sökning och val</legend>
      <xsl:apply-templates select="form/basket" />
      <xsl:apply-templates select="form/details" />
    </fieldset>
    <fieldset>
      <legend>Sökresultat</legend>
      <xsl:apply-templates select="form/searchresult" />
    </fieldset>
  </form>
  <!-- <xsl:copy-of select="form/" /> -->

</div>
</xsl:template>

<xsl:template match="basket">
<div>
  <xsl:attribute name="class">timeeditbasket</xsl:attribute>

  <xsl:copy-of select="input" />
  <div><xsl:value-of select="head" /></div>
  <xsl:if test="item">
  <ul>
    <xsl:attribute name="class">timeeditbasketitems</xsl:attribute>
    <xsl:for-each select="item">
      <li>
        <xsl:attribute name="class">timeeditbasketitem</xsl:attribute>

        <xsl:copy-of select="input" />
        <xsl:copy-of select="a" />
        <xsl:value-of select="name/long" /> <small>
          (<xsl:value-of select="name/short" />)</small>
      </li>
    </xsl:for-each>
  </ul>
  </xsl:if>
</div>
</xsl:template>

<xsl:template match="details">
<div id="timeeditdetails">
  <xsl:copy-of select="@*|node()" />
  <!--<xsl:copy-of select="//input[@name= 'save']" />-->
</div>
</xsl:template>

<xsl:template match="searchresult">
<div>
  <xsl:attribute name="class">
    timeeditsearchresult
  </xsl:attribute>

  <div><xsl:value-of select="description/b" /></div>
  
  <xsl:if test="results/result">
  <ul>
    <xsl:for-each select="results/result">
      <li>
        <xsl:attribute name="class">timeeditresultitem</xsl:attribute>

        <xsl:copy-of select="a" />
        <xsl:value-of select="name/long" /> <small>
          (<xsl:value-of select="name/short" />)</small>
      </li>
    </xsl:for-each>
  </ul>
  </xsl:if>
  
</div>
</xsl:template>

<xsl:template match="instructions">
  <ul>
    <li>
      <h2>Instruktioner</h2>
    </li>
  <xsl:for-each select="instruction">
    <li>
        <xsl:value-of select="number" />
        <xsl:value-of select="desc" />
    </li>
  </xsl:for-each>
  </ul>
</xsl:template>
</xsl:stylesheet>

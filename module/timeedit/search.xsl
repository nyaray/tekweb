<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:exsl="http://exslt.org/common" >
<xsl:output method="xml"
  disable-output-escaping="yes" />


<xsl:template match="node()|@*">
  <!-- <xsl:copy-of select="@*|node()"> -->
    <xsl:apply-templates select="@*|node()" />
  <!-- </xsl:copy-of> -->
</xsl:template>


<xsl:template match="/">
<calendar>
  <b0rk0rz />
  <search>
    <!-- <script src="/js/timeedit.js"></script> -->
    <xsl:apply-templates select="html/body/table" />
  </search>
</calendar>
</xsl:template>

<!-- Tear the main table apart -->
<xsl:template match="body/table">
  <xsl:apply-templates select="tr[2]/td[1]/form" />

  <links>
    <xsl:copy-of select="tr[3]/td[1]/small/*" />
  </links>
</xsl:template>

<xsl:template match="form">
  <form>
    <xsl:attribute name="method">get</xsl:attribute>

    <xsl:if test="table[2]/tr[3]/td[4]/table[1]/tr[1]/td[1]/*">
      <basket>
        <xsl:copy-of select="table[2]/tr[3]/td[4]/table[1]/tr[1]/td[1]/input[1]" />

        <head>
          <!-- <label> -->
            <xsl:value-of select="table[2]/tr[3]/td[4]/table[1]/tr[1]/td[1]/table[1]/tr[2]/td[1]/small[1]" />
          <!-- </label> -->
        </head>

        <xsl:for-each select="table[2]/tr[3]/td[4]/table[1]/tr[1]/td[1]/table[1]/tr[2]/following-sibling::tr">
          <xsl:call-template name="parsebasket">
            <xsl:with-param name="item" select="*" />
          </xsl:call-template>
        </xsl:for-each>
      </basket>
    </xsl:if>

    <details> <!-- Resource type selector, input field and search button-->
      <xsl:copy-of select="table[2]/tr[1]/td[1]/table[1]/tr[1]/td[3]/table[1]/tr[1]/td[1]/select" />
      <xsl:copy-of select="table[2]/tr[1]/td[1]/table[1]/tr[3]/td[3]/input[2]" />
      <xsl:copy-of select="table[2]/tr[1]/td[1]/table[1]/tr[3]/td[3]/input[3]" />
    </details>

    <weeks> <!-- Start week selector, stop week selector -->
      <!-- <xsl:copy-of select="table[2]/tr[1]/td[5]/table[1]/tr[1]/td[1]/table[1]/tr[3]/td[1]/select[1]" />
      <xsl:copy-of select="table[2]/tr[1]/td[5]/table[1]/tr[1]/td[1]/table[1]/tr[3]/td[3]/select[1]" /> -->
    </weeks>

    <instructions>
      <xsl:call-template name="parseinstructions">
        <xsl:with-param name="instructions" select="table[2]/tr[1]/td[7]/table[1]/tr[1]/td[1]/table[1]/tr" />
      </xsl:call-template>
    </instructions>

    <searchresult>
      <!-- Two hidden fields, wv_first and wv_addObj -->
      <xsl:copy-of select="table[2]/tr[3]/td[1]/input[1]" />
      <xsl:copy-of select="table[2]/tr[3]/td[1]/input[2]" />

      <xsl:if test="table[2]/tr[3]/td[1]/table[1]/tr/following-sibling::tr[2]">
        <description>
          <!-- Result count -->
          <xsl:copy-of select="table[2]/tr[3]/td[1]/table[1]/tr[1]/td[1]/b[1]" />
          <!-- How to add... -->
          <xsl:copy-of select="table[2]/tr[3]/td[1]/table[1]/tr[2]/td[1]/small[1]" />
        </description>

        <results>
          <xsl:for-each select="table[2]/tr[3]/td[1]/table[1]/tr/following-sibling::tr[2]">
            <xsl:call-template name="parseresult">
              <xsl:with-param name="result" select="./td" />
            </xsl:call-template>
          </xsl:for-each>
        </results>
      </xsl:if>
    </searchresult>

    <!-- 'Show schedule' button  -->
    <xsl:copy-of select="input[@name = 'wv_text']">
      <xsl:attribute name="value">Visa Schema</xsl:attribute>
    </xsl:copy-of>
  </form>
</xsl:template>

<xsl:template name="parseinstructions">
  <xsl:param name="instructions" />

  <xsl:for-each select="exsl:node-set($instructions)">
    <instruction>
      <number><xsl:value-of select="td[1]/small" /></number>
      <desc><xsl:value-of select="td[2]/small" /></desc>
    </instruction>
  </xsl:for-each>
</xsl:template>

<xsl:template name="parseresult">
  <xsl:param name="result" />

  <xsl:choose>
    <xsl:when test="count(exsl:node-set($result)) = 1"></xsl:when>
    <xsl:otherwise>
      <result>
        <xsl:copy-of select="exsl:node-set($result)[1]/a" />
        <name>
          <short><xsl:value-of select="exsl:node-set($result)[3]" /></short>
          <long><xsl:value-of select="exsl:node-set($result)[5]" /></long>
        </name>

        <xsl:if test="exsl:node-set($result)[7]/select[1]">
          <subresult>
            <xsl:copy-of select="exsl:node-set($result)[7]/select[1]" />
          </subresult>
        </xsl:if>
      </result>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="parsebasket">
  <xsl:param name="item" />

  <xsl:if test="exsl:node-set($item)">
    <item>
      <xsl:copy-of select="exsl:node-set($item)[1]/input[1]" />
      <xsl:copy-of select="exsl:node-set($item)[1]/a[1]" />
      <type><xsl:value-of select="exsl:node-set($item)[3]" /></type>
      <name>
        <short><xsl:value-of select="exsl:node-set($item)[5]" /></short>
        <long><xsl:value-of select="exsl:node-set($item)[7]" /></long>
      </name>
      <subgroup><xsl:value-of select="exsl:node-set($item)[9]" /></subgroup>
    </item>
  </xsl:if>
</xsl:template>

<xsl:template match="script">
  <script src="/js/timeedit.js"></script>
</xsl:template>
</xsl:stylesheet>
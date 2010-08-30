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
  <search>
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

    <!-- <xsl:copy-of select="//input" />
    <xsl:copy-of
      select="//select[@name != 'wv_startWeek' and @name != 'wv_stopWeek']" /> -->

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

    <!-- Resource type selector, input field, search button, wv_first and wv_addObj.
         wv_first and wv_addObj are hidden.-->
    <details>
      <xsl:call-template name="stripresourceselector">
        <xsl:with-param name="selector" select="table[2]/tr[1]/td[1]/table[1]/tr[1]/td[3]/table[1]/tr[1]/td[1]/select" />
      </xsl:call-template>
      <xsl:copy-of select="table[2]/tr[1]/td[1]/table[1]/tr[3]/td[3]/input[2]" />
      <xsl:copy-of select="table[2]/tr[1]/td[1]/table[1]/tr[3]/td[3]/input[3]" />
      <xsl:copy-of select="table[2]/tr[3]/td[1]/input[1]" />
      <xsl:copy-of select="table[2]/tr[3]/td[1]/input[2]" />
    </details>

    <!-- Start week selector, stop week selector -->
    <!-- <weeks> -->
      <!-- <xsl:copy-of select="table[2]/tr[1]/td[5]/table[1]/tr[1]/td[1]/table[1]/tr[3]/td[1]/select[1]" />
      <xsl:copy-of select="table[2]/tr[1]/td[5]/table[1]/tr[1]/td[1]/table[1]/tr[3]/td[3]/select[1]" /> -->
    <!-- </weeks> -->

    <instructions>
      <xsl:call-template name="parseinstructions">
        <xsl:with-param name="instructions" select="table[2]/tr[1]/td[7]/table[1]/tr[1]/td[1]/table[1]/tr" />
      </xsl:call-template>
    </instructions>

    <xsl:if test="table[2]/tr[3]/td[1]/table[1]/tr/following-sibling::tr[2]">
      <searchresult>

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
      </searchresult>
    </xsl:if>

    <!-- 'Show schedule' button  -->
    <xsl:copy-of select="input[@name = 'wv_text']">
      <xsl:attribute name="value">Visa Schema</xsl:attribute>
    </xsl:copy-of>
  </form>
</xsl:template>

<xsl:template name="stripresourceselector">
  <xsl:param name="selector" />

  <select>
    <xsl:copy-of select="exsl:node-set($selector)/@*" />
    <xsl:copy-of select="exsl:node-set($selector)/option[@value=3 or @value=4]" />
  </select>
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
        <a>
          <xsl:attribute name="href">
            <xsl:value-of select="exsl:node-set($result)[1]/a[1]/@href" />
          </xsl:attribute>
          <img src="gfx/plus.gif" />
        </a>
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
      <a>
        <xsl:attribute name="href">
          <xsl:value-of select="exsl:node-set($item)[1]/a[1]/@href" />
        </xsl:attribute>
        <img>
          <xsl:attribute name="src">
            <xsl:text>gfx/minus.gif</xsl:text>
          </xsl:attribute>
        </img>
      </a>
      <!--<xsl:copy-of select="exsl:node-set($item)[1]/a[1]" />-->
      <type><xsl:value-of select="exsl:node-set($item)[3]" /></type>
      <name>
        <short><xsl:value-of select="exsl:node-set($item)[5]" /></short>
        <long><xsl:value-of select="exsl:node-set($item)[7]" /></long>
      </name>
      <subgroup><xsl:value-of select="exsl:node-set($item)[9]" /></subgroup>
    </item>
  </xsl:if>
</xsl:template>
</xsl:stylesheet>

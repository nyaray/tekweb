<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : empsearch.xsl
    Created on : July 27, 2010, 2:11 PM
    Author     : mange
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />

    <!-- TODO customize transformation rules 
         syntax recommendation http://www.w3.org/TR/xslt 
    -->
    <xsl:template match="/section/empsearch/employeelist">
        <xsl:for-each select="employee">
            <div class="employee">
                <span>
                    <xsl:value-of select="commonname"/>
                </span><br />
                <xsl:if test="titleatdep">
                    <span class="titleatdep">
                        <xsl:value-of select="titleatdep"/>
                    </span>
                </xsl:if>
                <xsl:if test="visitingaddress">
                    <span class="visitingaddress">
                        <xsl:value-of select="visitingaddress"/>
                    </span>
                </xsl:if>
                <xsl:if test="roomnumber">
                    <span class="roomnumber">
                        <xsl:value-of select="roomnumber"/>
                    </span>
                </xsl:if>
            </div>
        </xsl:for-each>
    </xsl:template>
</xsl:stylesheet>

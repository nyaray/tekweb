<?xml version="1.0" encoding="UTF-8"?>
<!-- <PROGRAM_NAME>
 Copyright (C) 2010 Magnus Söderling (magnus.soderling@gmail.com)

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" />

    <xsl:template match="form">
        <form>
            <xsl:attribute name="action">
                <xsl:value-of select="action" />
            </xsl:attribute>
            <xsl:attribute name="method">
                <xsl:value-of select="method" />
            </xsl:attribute>
            <input type="text">
                <xsl:attribute name="name">
                    <xsl:value-of select="name" />
                </xsl:attribute>
            </input>
        </form>
    </xsl:template>

    <xsl:template match="message">
        <div class="message">
            <xsl:value-of select="."/>
        </div>
    </xsl:template>
    
    <xsl:template match="employeelist">
        <xsl:for-each select="employee">
            <!-- "sorted"(ÄÅÖ) same as uu.se-->
            <xsl:sort select="surname" lang="sv"/>
            <xsl:sort select="givenname" lang="sv"/>
            <div class="employee">
                <span>
                    <xsl:value-of select="givenname"/>
                    <xsl:text>&#160;</xsl:text>
                    <xsl:value-of select="surname"/>
                </span>
                <br />
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

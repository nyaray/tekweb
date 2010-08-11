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
        <ul class="employees">
            <xsl:for-each select="employee">
            <!-- "sorted"(ÄÅÖ) same as uu.se-->
                <xsl:sort select="surname" lang="sv"/>
                <xsl:sort select="givenname" lang="sv"/>
                <li>
                    <xsl:value-of select="givenname"/>
                    <xsl:text>&#160;</xsl:text>
                    <xsl:value-of select="surname"/>
                    <ul>
                        <xsl:if test="titleatdep">
                            <li class="titleatdep">
                                <xsl:value-of select="titleatdep"/>
                            </li>
                        </xsl:if>
                        <xsl:if test="visitingaddress">
                            <li class="visitingaddress">
                                <xsl:text>Besöksadress: </xsl:text>
                                <xsl:value-of select="visitingaddress"/>
                            </li>
                        </xsl:if>
                        <xsl:if test="roomnumber">
                            <li class="roomnumber">
                                <xsl:text>RUM: </xsl:text>
                                <xsl:value-of select="roomnumber"/>
                            </li>
                        </xsl:if>
                        <xsl:if test="mail">
                            <li class="mail">
                                <a>
                                    <xsl:attribute name="href">
                                        <xsl:text>mailto:</xsl:text>
                                        <xsl:value-of select="mail"/>
                                    </xsl:attribute>
                                    <xsl:text>E-post: </xsl:text>
                                    <xsl:value-of select="mail"/>
                                </a>
                            </li>
                        </xsl:if>
                        <xsl:if test="phonenumber">
                            <li class="phonenumber">
                                <a>
                                    <xsl:attribute name="href">
                                        <xsl:text>tel:0</xsl:text>
                                        <xsl:value-of select="substring(phonenumber, 4)"/>
<!--                                        <xsl:value-of select="phonenumber"/>-->
                                    </xsl:attribute>
                                    <xsl:text>TEL: 0</xsl:text>
                                    <xsl:value-of select="substring(phonenumber, 4, 2)"/>
                                    <xsl:text>&#160;</xsl:text>
                                    <xsl:value-of select="substring(phonenumber, 6, 3)"/>
                                    <xsl:text>&#160;</xsl:text>
                                    <xsl:value-of select="substring(phonenumber, 9)"/>
                                </a>
                            </li>
                        </xsl:if>
                        <xsl:if test="mobilenumber">
                            <li class="mobilenumber">
                                <a>
                                    <xsl:attribute name="href">
                                        <xsl:text>tel:0</xsl:text>
                                        <xsl:value-of select="substring(mobilenumber, 4)"/>
<!--                                        <xsl:text>tel:</xsl:text>
                                        <xsl:value-of select="mobilenumber"/>-->
                                    </xsl:attribute>
                                    <xsl:text>TEL: 0</xsl:text>
                                    <xsl:value-of select="substring(mobilenumber, 4, 2)"/>
                                    <xsl:text>&#160;</xsl:text>
                                    <xsl:value-of select="substring(mobilenumber, 6, 3)"/>
                                    <xsl:text>&#160;</xsl:text>
                                    <xsl:value-of select="substring(mobilenumber, 9)"/>
                                </a>
                            </li>
                        </xsl:if>
                        <xsl:if test="faxnumber">
                            <li class="faxnumber">
                                <xsl:text>FAX: 0</xsl:text>
                                <xsl:value-of select="substring(faxnumber, 4, 2)"/>
                                <xsl:text>&#160;</xsl:text>
                                <xsl:value-of select="substring(faxnumber, 6, 3)"/>
                                <xsl:text>&#160;</xsl:text>
                                <xsl:value-of select="substring(faxnumber, 9)"/>
                            </li>
                        </xsl:if>
                    </ul>
                </li>
            </xsl:for-each>
        </ul>
    </xsl:template>
</xsl:stylesheet>
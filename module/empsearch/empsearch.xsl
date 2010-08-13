<?xml version="1.0"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" encoding="utf-8" omit-xml-declaration="yes" />
    <xsl:template match="section/empsearch">
        <div id="empsearch" class="section">
            <xsl:apply-templates select="form"/>
            <xsl:apply-templates select="message"/>
            <xsl:apply-templates select="employeelist"/>
        </div>
    </xsl:template>

    <xsl:template match="toggler/empsearch">
        <div>
            <xsl:attribute name="id">
                <xsl:value-of select="name" />
            </xsl:attribute>
            <xsl:attribute name="class">toggler</xsl:attribute>
            <a class="togglerbutton">
                <xsl:attribute name="href">
                    <xsl:text>?page=</xsl:text>
                    <xsl:value-of select="name" />
                </xsl:attribute>
                <xsl:element name="img">
                    <xsl:attribute name="class">togglericon</xsl:attribute>
                    <xsl:attribute name="src">
                        <xsl:value-of select="./icon" />
                    </xsl:attribute>
                </xsl:element>
                <div class="togglerbuttontext">
                    <xsl:value-of select="head" />
                </div>
            </a>
            <div>
                <xsl:attribute name="class">hidden</xsl:attribute>
                <div>
                    <xsl:attribute name="class">togglercontent</xsl:attribute>
                    <xsl:apply-templates select="form"/>
                    <xsl:apply-templates select="message"/>
                    <xsl:apply-templates select="employeelist"/>
                </div>
            </div>
        </div>
    </xsl:template>
    <xsl:template match="form">
        <form id="empform">
            <xsl:attribute name="action">
                <xsl:value-of select="action"/>
            </xsl:attribute>
            <xsl:attribute name="method">
                <xsl:value-of select="method"/>
            </xsl:attribute>
            <input type="text">
                <xsl:attribute name="name">
                    <xsl:value-of select="name"/>
                </xsl:attribute>
                <xsl:attribute name="value">
                    <xsl:value-of select="value"/>
                </xsl:attribute>
            </input>
            <xsl:apply-templates select="button"/>
            <xsl:apply-templates select="page"/>
        </form>
    </xsl:template>
    <xsl:template match="button">
        <input>
            <xsl:attribute name="value">
                <xsl:value-of select="value"/>
            </xsl:attribute>
            <xsl:attribute name="type">
                <xsl:value-of select="type"/>
            </xsl:attribute>
        </input>
    </xsl:template>
    <xsl:template match="message">
        <div class="message">
            <xsl:value-of select="."/>
        </div>
    </xsl:template>
    <xsl:template match="page">
        <input type="hidden" name="page">
            <xsl:attribute name="value">
                <xsl:value-of select="value"/>
            </xsl:attribute>
        </input>
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
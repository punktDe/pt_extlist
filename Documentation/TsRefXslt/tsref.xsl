<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xd="http://www.oxygenxml.com/ns/doc/xsl"
    exclude-result-prefixes="xd"
    version="1.0" xmlns="http://docbook.org/ns/docbook">
    <xd:doc scope="stylesheet">
        <xd:desc>
            <xd:p><xd:b>Created on:</xd:b> Oct 19, 2010</xd:p>
            <xd:p><xd:b>Author:</xd:b> ry21</xd:p>
            <xd:p></xd:p>
        </xd:desc>
    </xd:doc>

    <xsl:template match="/">
        <article version="5.0">
            <title>pt_extlist</title>
            <subtitle>TypoSript Reference</subtitle>
            <info/>
            <xsl:apply-templates select="/TSREF/ENTRY" />
        </article>
    </xsl:template>
    
    <xsl:template match="ENTRY">
        <refentry>
            <refmeta>
                <refentrytitle>
                    <xsl:value-of select="@KEY"/>
                </refentrytitle>
            </refmeta>
            <refnamediv>
                <refname><xsl:value-of select="@KEY"/></refname>
                <refpurpose><xsl:value-of select="ENTRY/DESCRIPTION"/></refpurpose>
            </refnamediv>
            <refsection>
                <title>
                    <anchor>
                        <xsl:attribute name="xml:id">tsref.<xsl:value-of select="@KEY"/></xsl:attribute>
                    </anchor>
                    description
                </title>
                <para><xsl:value-of select="DESCRIPTION"/></para>
            </refsection>
            <xsl:if test="count(CHILDREN/ENTRY) > 0">
                <refsection>
                    <title>child elements</title>
                    <xsl:for-each select="CHILDREN/ENTRY/@KEY" >
                        <para>
                            <link>
                                <xsl:attribute name="linkend">tsref.<xsl:value-of select="."/></xsl:attribute><xsl:value-of select="."/>
                            </link>
                        </para>
                    </xsl:for-each>
                </refsection>
                <refsection>
                        <title>Children of <xsl:value-of select="@KEY"/>:</title>
                        <xsl:apply-templates select="CHILDREN" />
                </refsection>
            </xsl:if>
        </refentry>
    </xsl:template>
    
</xsl:stylesheet>
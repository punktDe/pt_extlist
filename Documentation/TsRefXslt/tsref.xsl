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
        <section xmlns="http://docbook.org/ns/docbook" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xi="http://www.w3.org/2001/XInclude" xmlns:svg="http://www.w3.org/2000/svg" xmlns:m="http://www.w3.org/1998/Math/MathML" xmlns:html="http://www.w3.org/1999/xhtml" xmlns:db="http://docbook.org/ns/docbook" version="5.0">
            <title>pt_extlist</title>
            <subtitle>TypoSript Reference</subtitle>
            <info/>
            <xsl:apply-templates select="/TSREF/ENTRY" />
        </section>
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
                    Description
                </title>
                <para><xsl:value-of select="DESCRIPTION"/></para>
            </refsection>
			<refsection> <segmentedlist>
				 <?dbfo list-presentation="list"?>   
				 <segtitle>Datatype</segtitle>
				 <segtitle>Posible values</segtitle>
				 <segtitle>Default</segtitle>
				 <segtitle>StdWrap</segtitle>
				 <segtitle>Prototype</segtitle>
				 
					<seglistitem>
						
						<seg>
							<xsl:value-of select="DATATYPE"/>
						</seg>
						<seg>
							<xsl:value-of select="POSIBLEVALUES"/>
						</seg>
						<seg>
							<xsl:value-of select="DEFAULT"/>
						</seg>
						<seg>
							<xsl:value-of select="STDWRAP"/>
						</seg>
						<seg>
							<xsl:value-of select="PROTOTYPE"/>
						</seg>
					</seglistitem>
			
				</segmentedlist>
			</refsection>
			<refsection>
				<title>
                    Example
                </title>
                <para>
					<programlisting>
						<xsl:value-of select="EXAMPLE"/>
					</programlisting>
				</para>
			</refsection>
            <xsl:if test="count(CHILDREN/ENTRY) > 0">
                <refsection>
                    <title>Child elements</title>
                    <xsl:for-each select="CHILDREN/ENTRY/@KEY" >
						<link text-decoration="underline" color="blue">
							<xsl:attribute name="linkend">tsref.<xsl:value-of select="."/></xsl:attribute><xsl:value-of select="."/>
						</link>,
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
			 
				<!-- <variablelist>
					<varlistentry>
						<term>
							Datatype
						</term>
						<listitem>
							<xsl:value-of select="DATATYPE"/>
						</listitem>
					</varlistentry>
					<varlistentry>
						<term>
							Posible Values
						</term>
						<listitem>
							<xsl:value-of select="POSIBLEVALUES"/>
						</listitem>
					</varlistentry>
					<varlistentry>
						<term>
							Default
						</term>
						<listitem>
							<xsl:value-of select="DEFAULT"/>
						</listitem>
					</varlistentry>
					<varlistentry>
						<term>
							StdWrap
						</term>
						<listitem>
							<xsl:value-of select="STDWRAP"/>
						</listitem>
					</varlistentry>
					<varlistentry>
						<term>
							Prototype
						</term>
						<listitem>
							<xsl:value-of select="PROTOTYPE"/>
						</listitem>
					</varlistentry>
				</variablelist>
			</refsection>
			<refsection>
				<title>
                    Example
                </title>
                <para>
					<programlisting>
						<xsl:value-of select="EXAMPLE"/>
					</programlisting>
				</para>
			</refsection>
            <xsl:if test="count(CHILDREN/ENTRY) > 0">
                <refsection>
                    <title>Child elements</title>
                    <xsl:for-each select="CHILDREN/ENTRY/@KEY" >
						<link>
							<xsl:attribute name="linkend">tsref.<xsl:value-of select="."/></xsl:attribute><xsl:value-of select="."/>
						</link>,
                    </xsl:for-each>
                </refsection>
                <refsection>
                        <title>Children of <xsl:value-of select="@KEY"/>:</title>
                        <xsl:apply-templates select="CHILDREN" />
                </refsection>
            </xsl:if>
        </refentry>
    </xsl:template>
    
</xsl:stylesheet>-->
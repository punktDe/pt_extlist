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
            <xsl:apply-templates select="/DOC/TSREF/ENTRY" >
            	<xsl:with-param name="parentKey" select="'tsref'" />
            </xsl:apply-templates>
        </section>
    </xsl:template>
    
    
    <xsl:template match="TYPEREF">
    	<xsl:param name="parentKey" />
    	<xsl:variable name="selectedKey" select="@REF" />
    	<xsl:apply-templates select="/DOC/DATATYPES/ENTRY[@REF=$selectedKey]" >
    		<xsl:with-param name="parentKey" select="$parentKey" />
    	</xsl:apply-templates>
    </xsl:template>
    
        
        
	<xsl:template match="ENTRY">
		<xsl:param name="parentKey" />
		<xsl:variable name="currentKey" select="concat($parentKey, '.', @KEY)" />
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
                    	<xsl:attribute name="xml:id"><xsl:value-of select="$currentKey" /></xsl:attribute>
                    </anchor>
                    Description
                </title>
                <para><xsl:value-of select="DESCRIPTION"/></para>
            </refsection>
        	
        	
        	
			<refsection> 
				<segmentedlist>
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
        	
        	
        	
        	<xsl:if test="count(VARIANT/*) > 0">
	        	<refsection>
	        		<title>Variants</title>
	        		<xsl:for-each select="VARIANT/*/@KEY" >
	        			<link text-decoration="underline" color="blue">
	        				<xsl:attribute name="linkend"><xsl:value-of select="$currentKey" />.<xsl:value-of select="."/></xsl:attribute><xsl:value-of select="."/>
	        			</link>,
	        		</xsl:for-each>
	        		
	        		<refsection>
	        			<title>Variants of <xsl:value-of select="@KEY"/>:</title>
	        			<xsl:apply-templates select="VARIANT/*" >
	        				<xsl:with-param name="parentKey" select="$currentKey" />
	        			</xsl:apply-templates>
	        		</refsection>
	        	</refsection>
			</xsl:if>	
        	
        	
        	
            <xsl:if test="count(CHILDREN/*) > 0">
                <refsection>
                    <title>Child elements</title>
                	<xsl:for-each select="CHILDREN/*/@KEY" >
                		<link text-decoration="underline" color="blue">
                			<xsl:attribute name="linkend"><xsl:value-of select="$currentKey" />.<xsl:value-of select="."/></xsl:attribute><xsl:value-of select="."/>
                		</link>,
                	</xsl:for-each>
                </refsection>
                <refsection>
                        <title>Children of <xsl:value-of select="@KEY"/>:</title>
                        <xsl:apply-templates select="CHILDREN/*" >
                        	<xsl:with-param name="parentKey" select="$currentKey" />
                        </xsl:apply-templates>
                </refsection>
            </xsl:if>
        	
        	
        </refentry>
    </xsl:template>
    
</xsl:stylesheet>
			 

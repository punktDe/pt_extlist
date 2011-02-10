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



	<!-- Template for ROOT -->
    <xsl:template match="/">
        <section xmlns="http://docbook.org/ns/docbook" 
        		 xmlns:xlink="http://www.w3.org/1999/xlink" 
        		 xmlns:xi="http://www.w3.org/2001/XInclude" 
        		 xmlns:svg="http://www.w3.org/2000/svg" 
        		 xmlns:m="http://www.w3.org/1998/Math/MathML" 
        		 xmlns:html="http://www.w3.org/1999/xhtml" 
        		 xmlns:db="http://docbook.org/ns/docbook" version="5.0">
            <title>pt_extlist</title>
            <subtitle>TypoSript Reference</subtitle>
            <info/>
            <xsl:apply-templates select="/DOC/TSREF/ENTRY" >
            	<xsl:with-param name="parentKey" select="'tsref'" />
            	<!-- <xsl:with-param name="parentKey" select="'plugin'" /> -->
            </xsl:apply-templates>
        </section>
    </xsl:template>
    
    
    
    <!-- Template for TYPEREF -->
    <xsl:template match="TYPEREF">
    	<xsl:param name="parentKey" />
    	<xsl:variable name="selectedKey" select="@REF" />
    	<xsl:apply-templates select="/DOC/DATATYPES/ENTRY[@REF=$selectedKey]" >
    		<xsl:with-param name="parentKey" select="$parentKey" />
    	</xsl:apply-templates>
    </xsl:template>
    
        
        
    <!-- Template for a TS Entry -->    
	<xsl:template match="ENTRY">
		<xsl:param name="parentKey" />
		<xsl:variable name="currentKey" select="concat($parentKey, '.', @KEY)" />
        <refentry>
        	
       	
        	
        	<!-- Rendering title of ENTRY-->
            <refmeta>
                <refentrytitle>
                    <xsl:value-of select="@KEY"/>
                </refentrytitle>
            </refmeta>
        	
        	
        	
        	<!-- Rendering Title -->
        	<refnamediv>
        		<refname><xsl:value-of select="@KEY"/></refname>
        		<refpurpose><xsl:value-of select="ENTRY/DESCRIPTION"/></refpurpose>
        	</refnamediv>
        	
        	 
        	
        	<!-- Rendering TS Key -->
        	<refsection>
        		<title>TS-Key:</title>
        		<para>
        			<xsl:call-template name="parent-key-navigation">
        				<xsl:with-param name="parentKey" select="$currentKey" />
        				<xsl:with-param name="previous" select="''" />
        			</xsl:call-template>
        		</para>
        	</refsection>
        	
        	
        	
        	<!-- Rendering description -->
            <refsection>
                <title>
                    <anchor> <!-- Creating anchor for jump links to current ENTRY -->
                    	<!-- since '[' and ']' are no valid characters for an identifier, they are replaced with 'l' and 'r' -->
                    	<xsl:attribute name="xml:id"><xsl:value-of select="translate(translate(translate($currentKey,'[', 'l'), ']', 'r'), ',', '-')" /></xsl:attribute>
                    </anchor>
Description
                </title>
                <para><xsl:value-of select="DESCRIPTION"/></para>
            </refsection>
        	
        	
        	
        	<!-- Rendering properties block of ENTRY -->
			<refsection> 
				<segmentedlist>
				    <?dbfo list-presentation="list"?>   
					<xsl:if test="DATATYPE != ''"><segtitle>Datatype</segtitle></xsl:if>
					<xsl:if test="POSIBLEVALUES != ''"><segtitle>Posible values</segtitle></xsl:if>
					<xsl:if test="DEFAULT != ''"><segtitle>Default</segtitle></xsl:if>
					<segtitle>CObject</segtitle>
					<xsl:if test="PROTOTYPE != ''"><segtitle>Prototype</segtitle></xsl:if>
					<seglistitem>
						<xsl:if test="DATATYPE != ''"><seg><xsl:value-of select="DATATYPE"/></seg></xsl:if>
						<xsl:if test="POSIBLEVALUES != ''"><seg><xsl:value-of select="POSIBLEVALUES"/></seg></xsl:if>
						<xsl:if test="DEFAULT != ''"><seg><xsl:value-of select="DEFAULT"/></seg></xsl:if>
						<xsl:choose>
							<xsl:when test="COBJ='1'"><seg>YES</seg></xsl:when>
							<xsl:otherwise><seg>NO</seg></xsl:otherwise>
						</xsl:choose>
						<xsl:if test="PROTOTYPE != ''"><seg><xsl:value-of select="PROTOTYPE"/></seg></xsl:if>
					</seglistitem>
				</segmentedlist>
			</refsection>
        	
        	
        	
        	<!-- Rendering example block of ENTRY -->
        	<xsl:if test="EXAMPLE != ''">
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
        	</xsl:if>
        	
        	
        	
        	<!-- Rendering variants block of ENTRY -->
        	<xsl:if test="count(VARIANT/*) > 0">
	        	<refsection>
	        		<title>Variants</title>
	        		<xsl:for-each select="VARIANT/*/@KEY" >
	        			<link text-decoration="underline" color="blue">
	        				<!-- since '[' and ']' are no valid characters for an identifier, they are replaced with 'l' and 'r' -->
	        				<xsl:attribute name="linkend"><xsl:value-of select="translate(translate(translate($currentKey,'[', 'l'), ']', 'r'), ',', '-')" />.<xsl:value-of select="translate(translate(translate(.,'[', 'l'), ']', 'r'), ',', '-')"/></xsl:attribute><xsl:value-of select="."/>
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
        	
        	
        	
        	<!-- Rendering children block of ENTRY -->
            <xsl:if test="count(CHILDREN/*) > 0">
                <refsection>
                    <title>Child elements</title>
                	<xsl:for-each select="CHILDREN/*/@KEY" >
                		<link text-decoration="underline" color="blue">
                			<!-- since '[' and ']' are no valid characters for an identifier, they are replaced with 'l' and 'r' -->
                			<xsl:attribute name="linkend">
                				<xsl:value-of select="translate(translate(translate($currentKey,'[', 'l'), ']', 'r'), ',', '-')" />.<xsl:value-of select="translate(translate(translate(.,'[', 'l'), ']', 'r'), ',', '-')"/>
                			</xsl:attribute><xsl:value-of select="."/>
                		</link>,
                	</xsl:for-each>
                </refsection>
                <refsection>
                        <!-- <title>Children of <xsl:value-of select="@KEY"/>:</title> -->
                        <xsl:apply-templates select="CHILDREN/*" >
                        	<xsl:with-param name="parentKey" select="$currentKey" />
                        </xsl:apply-templates>
                </refsection>
            </xsl:if>
        	
        	
        </refentry>
	</xsl:template>
	
	
	
	<!-- Create navigation for parent TS-Keys by splitting TS-Key on '.' and creating links to corresponding anchors 
		For a reference on how I did this see: http://www.abbeyworkshop.com/howto/xslt/xslt-split-values/index.html -->
	<xsl:template name="parent-key-navigation">
		<xsl:param name="parentKey" />
		<xsl:param name="previous" />
		<xsl:variable name="current" select="substring-before($parentKey, '.')" />
		<xsl:variable name="remaining" select="substring-after($parentKey, '.')" />
		
		<!-- Rendering link for current key -->
		<xsl:if test="$current != 'tsref'">
			<xsl:choose>
				<xsl:when test="$current">
					<link text-decoration="underline" color="blue">
						<!-- since '[' and ']' are no valid characters for an identifier, they are replaced with 'l' and 'r' -->
						<xsl:attribute name="linkend">
							<xsl:choose>
								<xsl:when test="$previous">
									<xsl:value-of select="translate(translate(translate(concat($previous, '.', $current),'[', 'l'), ']', 'r'), ',', '-')" />
								</xsl:when>
								<xsl:otherwise>
									<xsl:value-of select="translate(translate(translate($current,'[', 'l'), ']', 'r'), ',', '-')" />
								</xsl:otherwise>
							</xsl:choose>
						</xsl:attribute>
						<xsl:value-of select="$current"/><xsl:if test="$remaining"><!-- Mind this dot :-) -->.</xsl:if>
					</link>
				</xsl:when>
				<xsl:otherwise>
					<link text-decoration="underline" color="blue">
						<!-- since '[' and ']' are no valid characters for an identifier, they are replaced with 'l' and 'r' -->
						<xsl:attribute name="linkend">
							<xsl:value-of select="translate(translate(translate(concat($previous, '.', $parentKey),'[', 'l'), ']', 'r'), ',', '-')" />
						</xsl:attribute>
						<xsl:value-of select="$parentKey"/>
					</link>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:if>
		
		<!-- doing recursive call, if there are more parent keys to process -->
		<xsl:if test="$remaining">
			<xsl:choose>
				<xsl:when test="$previous">
					<xsl:variable name="newPrevious" select="concat($previous, '.', $current)" />
					<xsl:call-template name="parent-key-navigation">
						<xsl:with-param name="parentKey" select="$remaining" />
						<xsl:with-param name="previous" select="$newPrevious" />
					</xsl:call-template>
				</xsl:when>
				<xsl:otherwise>
					<xsl:call-template name="parent-key-navigation">
						<xsl:with-param name="parentKey" select="$remaining" />
						<xsl:with-param name="previous" select="$current" />
					</xsl:call-template>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:if>
	</xsl:template>
    
</xsl:stylesheet>
			 
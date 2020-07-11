<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"> 
<xsl:output method="xml" indent="yes"/> 
<xsl:template match="investment"> 
  <xsl:element name="{type}"> 
    <xsl:attribute name="name" ><xsl:value-of select="name"/></xsl:attribute>
    <xsl:for-each select="price"> 
      <xsl:attribute name="{@type}" ><xsl:value-of select="."/></xsl:attribute>
    </xsl:for-each> 
  </xsl:element> 
</xsl:template> 
</xsl:stylesheet> 

<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output  method="html" indent="yes" version="4.0" />   
  <xsl:template match="/">
    <table border="1">
      <xsl:for-each select="//hotel[city='Paris']">
        <tr ><td><xsl:value-of select="name"/></td>
        <xsl:choose>
          <xsl:when test="type='Budget'">
                 <td style="background:red"><xsl:value-of select="price"/></td>
          </xsl:when>
          <xsl:when test="type='Luxury'">
                 <td style="background:lightblue"><xsl:value-of select="price"/></td>
          </xsl:when>
          <xsl:otherwise>
                <td><xsl:value-of select="price"/></td>
          </xsl:otherwise>
        </xsl:choose>
        </tr>
      </xsl:for-each>
    </table>
    <br />Total: <xsl:value-of select="count(//hotel[city='Paris'])"/>
    <br />Average Price: <xsl:value-of select="sum(//hotel[city='Paris']/price) div count(//hotel[city='Paris'])"/>
  </xsl:template>
</xsl:stylesheet>

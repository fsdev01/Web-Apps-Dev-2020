<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                version="1.0">
 
    <xsl:output method="html"/>

    <xsl:template match="/">
        <HTML>
            <HEAD>
                <TITLE>Home Phone List</TITLE>
            </HEAD>
            <BODY bgcolor="{/FitnessCenter/Member/FavoriteColor}">
            Members' home phone numbers:
                <BR/>
                <TABLE border="1" width="25%">
                    <TR><TH>Name</TH><TH>Number</TH></TR>
                    <xsl:for-each select="FitnessCenter/Member">
                        <xsl:variable name="mname" select="Name"/>
                        <xsl:for-each select="Phone[@type='home']">
                            <TR>
                                <TD><xsl:value-of select="$mname"/></TD>
                                <TD><xsl:value-of select="."/></TD>
                            </TR>
                        </xsl:for-each>
                    </xsl:for-each>
                </TABLE>
            </BODY>
        </HTML>
    </xsl:template>

</xsl:stylesheet>
 
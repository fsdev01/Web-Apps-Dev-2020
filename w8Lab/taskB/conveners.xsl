<?xml version="1.0"?><!-- DWXMLSource="results.xml" -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<!-- format output as HTML -->
	<xsl:output method="html"/> 

	<!-- match with root/document node (context node)-->
	<xsl:template match="/">
	<HTML> 
		<HEAD>
			<TITLE>Units</TITLE> 
		</HEAD>

		<BODY>
			<!-- (5) IN HERE WRITE XSL THAT WILL GET THE FACULTY, GROUP AND TITLE OF ALL UNITS WITH MORE THAN 12.5 CREDIT POINTS -->
			Units with More than 12.5 Credit Points :<BR/><BR/>
			<xsl:if test="count(Units/Unit[points &gt; 12.5])">
				<table border="1">
					<tr>
						<th>FACULTY</th>
						<th>GROUP</th>
						<th>TITLE</th>
					</tr>

					<xsl:for-each select="Units/Unit[points &gt; 12.5]">
						<tr>
							<td> <xsl:value-of select="Faculty"/> </td>
							<td> <xsl:value-of select="group"/> </td>
							<td> <xsl:value-of select="title"/> </td>
						</tr>
					</xsl:for-each>
				</table>
			</xsl:if>
			<BR/>
			----------------------------------------------------------------------------------
			<BR/>
			<BR/>
			<!-- (6) IN HERE WRITE XSL THAT WILL WRITE THE NUMBER OF UNITS FOR THE CSSE GROUP -->
			Number of Units Offered by CSSE (regardless of points): <xsl:value-of select="count(Units/Unit[group='CSSE'])" />
		</BODY> 
	</HTML>
	</xsl:template>

</xsl:stylesheet>
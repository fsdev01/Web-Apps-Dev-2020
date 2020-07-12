<?xml version="1.0" encoding="UTF-8"?>

<!-- Reference: Based on Cart.xsl Week 8 Lecture Example -->
<!-- xsl:stylesheet defines XSLT stylesheet, XSLT namespace and XSLT version -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
	<!-- xsl:output Defines format of output document: html with indention -->
	<xsl:output method="html" indent="yes" version="4.0"/>


	<!-- xsl:template - match with root element (i.e. document as context node) -->
	<xsl:template match="/">
		<table id="processTable">
			<thead>
				<tr>
					<th>Item Number</th>
					<th>Item Name</th>
					<th>Unit Price</th>
					<th>Quantity Available</th>
					<th>Quantity on Hold</th>
					<th>Quantity Sold</th>
				</tr>
			</thead>

			<tbody>
			<!-- Loop through every item: Filter/Predicate where qty > 0 -->
			<xsl:for-each select="goods/item[qtysold &gt; 0]">
				<tr>
					<td><xsl:value-of select="id"/></td>
					<td><xsl:value-of select="name"/></td>
					<td><xsl:value-of select="format-number(price, '#.00')"/></td>
					<td><xsl:value-of select="qtyavailable"/></td>
					<td><xsl:value-of select="qtyonhold"/></td>
					<td><xsl:value-of select="qtysold"/></td>
				</tr>
			</xsl:for-each>
			<!-- Display Special Message if there are zero items for processing -->
			<xsl:if test="count(goods/item[qtysold &gt; 0]) = 0 ">
				<tr id='emptyProcessTable'>
					<td colspan="6" ><em>Zero Items Available for Processing</em></td>
				</tr>
			</xsl:if> 

			<xsl:if test="count(goods/item[qtysold &gt; 0]) > 0 ">
				<tr id='processTableMenu'>
					<td colspan="6" >
						<button onclick='processItems()'>Process</button></td>
				</tr>
			</xsl:if>
			</tbody>
		</table>
	</xsl:template>
</xsl:stylesheet>
<?xml version="1.0" encoding="UTF-8"?>

<!-- Reference: Based on Cart.xsl Week 8 Lecture Example -->
<!-- xsl:stylesheet defines XSLT stylesheet, XSLT namespace and XSLT version -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
	<!-- xsl:output Defines format of output document: html with indention -->
	<xsl:output method="html" indent="yes" version="4.0"/>


	<!-- xsl:template - match with root element (i.e. document as context node) -->
	<xsl:template match="/">
		<h3> Shopping Catalogue </h3>
		<table id="catalogueTable">
			<thead>
				<tr>
					<th>Item Number </th>
					<th>Name</th>
					<th>Description</th>
					<th>Unit Price</th>
					<th>Quantity</th>
					<th>Add</th>
				</tr>
			</thead>

			<tbody>
			<!-- Loop through every item: Filter/Predicate where qty > 0 -->
			<xsl:for-each select="goods/item[qtyavailable &gt; 0]">
				<tr>
					<td><xsl:value-of select="id"/></td>
					<td><xsl:value-of select="name"/></td>
					<!-- Select first 20 characters of the string . substring(string,startIndex,length) -->
					<td><xsl:value-of select="substring(description,1,20)"/></td>
					<!-- Reference: https://www.w3schools.com/xml/func_formatnumber.asp -->
					<td><xsl:value-of select="format-number(price, '#.00')"/></td>
					<td><xsl:value-of select="qtyavailable"/></td>
					<!-- Pass id of the product to the addItem() JS onclick function -->
					<td><button onclick="addRemoveItem('add',{id})">Add one to cart</button></td>
				</tr>
			</xsl:for-each>
			<xsl:if test="count(goods/item[qtyavailable &gt; 0]) = 0 ">
				<tr id='emptyCatalogue'>
					<td colspan="6" style="text-align: center">Zero Items Available for Sale</td>
				</tr>
			</xsl:if>
			</tbody>
		</table>
	</xsl:template>
</xsl:stylesheet>
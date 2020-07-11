<?xml version="1.0" encoding="UTF-8"?>

<!-- xsl:stylesheet defines XSLT stylesheet, XSLT namespace and XSLT version -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<!-- xsl:output Defines format of output document: html with indention -->
	<xsl:output method="html" indent="yes" version="4.0"/>

	<!-- xsl:template - match with root element (i.e. document as context node) -->
	<xsl:template match="/">


		<!-- xsl:for-each - Loop through each book (predicate/filter <$30)-->
		<xsl:for-each select="collection/book[price &lt; 30]">
				<!-- Extract tile,first author and price of every book that is (1) <$30 and (2) fiction -->
				<!-- METHODD 1: IF TEST check that the book is fiction  and <$30 -->
				<xsl:if test="title[@category='fiction'] and price &lt; 30">
					<!-- Title -->
					<span id="title" style="color:blue;font-weight:bold">
						<xsl:value-of select="title"/>
					</span>
					<br/>

					<!-- Author -->
					<span id="author" style="color:red;font-style:italic">
						<!-- Switch Statement: Add "et. al " if more than 1 author -->
						<xsl:choose>
							<xsl:when test="count(author) > 1">
								<!-- select the first author -->
								<xsl:value-of select="author[1]"/> et. al
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="author"/>
							</xsl:otherwise>
						</xsl:choose>
					</span>
					<br/>

					<!-- Price -->
					<span id="price">
						<xsl:value-of select="price"/>
					</span>
					<br/><br/>
				</xsl:if>
		</xsl:for-each>


		<!-- Total Price -->
		<span id="totalprice" style="font-size: 30px">
		<!-- METHOD 2: PREDICATE/FILTER - the book is fiction  and <$30 -->
		Total Price: $<xsl:value-of select="sum(collection/book[price &lt; 30 and title[@category='fiction']]/price)"/>
		</span>

	</xsl:template>
</xsl:stylesheet>
<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

 <!-- Format of the Output -->
  <xsl:output 
    method="html" 
    indent="yes" 
    version="4.0"
    doctype-public="-//W3C//DTD HTML 4.01//EN"
    doctype-system="http://www.w3.org/TR/html4/strict.dtd"/>
  
  <!-- Execute Template for nodes that match the "root"-->
  <xsl:template match="/">
    <!-- Create HTML Table -->
    <table id="shoppingcart">

      <!-- Execute another template called "DisplayCart" on current context note (e.g. root)-->
      <xsl:call-template name="DisplayCart"></xsl:call-template>

    </table> 
  </xsl:template>


  <xsl:template name="DisplayCart">
        <!-- HEADER of the CART: Shopping Basket Title with logo -->
        <tr class="head">
          <td colspan="4" align="center">Shopping Basket <img src="sbasket.gif"></img>
          </td>
        </tr>

    <!-- Condiitonal: Display the header row only if qty is greater than 0 -->
    <xsl:if test="number(//book/Quantity)>0">
      <tr>
        <td class="border">Item</td>
        <td class="border">Qty</td>
        <td class="border">Price</td>
        <td></td>
      </tr>
    </xsl:if>


    <!-- Loop: Visit every book -->
    <xsl:for-each select="//book">
      <tr>
        <td class="border2" width="75px">
          <xsl:value-of select="Title"/>
        </td>
      
        <td class="border2" align="center">
          <xsl:value-of select="Quantity"/>
        </td>
        <td class="border2">
          <!-- Calculate the total price for that book title (qty * price) in XPath Expression-->
          $<xsl:value-of select="Price * Quantity"/> 
        </td>
        <td class="border2">
          <a href="javascript:AddRemoveItem('Remove');">
            <img src='button.jpg'/>
          </a>
        </td>
      </tr>
    </xsl:for-each>
    
    <!-- Empty row: seperate cart items and total -->
    <tr >
      <td colspan='4' class="border2"> </td>
    </tr>

    <!-- SWITCH Statement: -->
    <xsl:choose>
      <!-- Display Total only if qty is > 0 -->
      <xsl:when test="sum(//book/Quantity)&gt;0">
        <tr>
          <td colspan="2" class="border2">Total:</td>
          <td class="border">
            $ <!-- display total for that given book -->
            <xsl:value-of select="(//Total)"/>
          </td>
          <td class="border2"> </td>
        </tr>
      </xsl:when>
      <!-- EMTPY CART -->
      <xsl:otherwise>
        <tr>
          <td colspan = "4" class="border2">Your Basket Is Empty</td>
        </tr>
      </xsl:otherwise>
    </xsl:choose>
    <tr >
      <td colspan="4" class="border2"> </td>
     
    </tr>
  </xsl:template>
</xsl:stylesheet>


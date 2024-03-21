<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
  <head>
    <link rel="stylesheet" type="text/css" href="Lab1_Part1.css" />
  </head>
  <body>
    <xsl:apply-templates/>
  </body>
  </html>
</xsl:template>

<xsl:template match="employees">
  <xsl:apply-templates select="employee"/>
</xsl:template>

<xsl:template match="employee">
  <div class="employee">
    <h3><xsl:value-of select="name"/></h3>
    <p class="email">Email: <xsl:value-of select="email"/></p>
    <p class="phones">Phones: 
      <xsl:for-each select="phones/phone">
        <span class="phone">
          <xsl:value-of select="@type"/>: <xsl:value-of select="."/>
        </span>
        <xsl:if test="position() != last()">, </xsl:if>
      </xsl:for-each>
    </p>
    <p class="address">Address: 
      <xsl:for-each select="address/*">
        <span class="address-item">
          <xsl:value-of select="name()"/>: <xsl:value-of select="."/>
        </span>
        <xsl:if test="position() != last()">, </xsl:if>
      </xsl:for-each>
    </p>
  </div>
</xsl:template>

</xsl:stylesheet>
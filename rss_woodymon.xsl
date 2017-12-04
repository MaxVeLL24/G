<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.1" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" />
<xsl:variable name="title" select="/rss/channel/title"/>

<xsl:template match="/">
 <html>
   <head>
     <title><xsl:value-of select="//channel/description"/>
     </title>
     <link rel="stylesheet" href="/rss_xls.css" type="text/css" />
   </head>
   <body>
     <xsl:apply-templates select="rss/channel"/>
   </body>
 </html>
</xsl:template>

<xsl:template match="channel">
    <div style="font-weight: bold; font-family: Verdana, arial, sans-serif">
      <a href="{link}" target="_blank">
         <xsl:value-of select="title"/>
      </a>
    </div>
   <br />
    <div style="font-family: Verdana, arial, sans-serif">
      <xsl:apply-templates select="item"/>
    </div>
   
    <div style="font-weight: bold; font-size: small">
    <xsl:value-of select="copyright"/>
    </div>
   
</xsl:template>

<xsl:template match="item">
    <div style="font-weight: bold; font-family: Verdana, arial, sans-serif">
    <a href="{link}" target="_blank">
      <xsl:value-of select="title"/>
    </a>
 </div>
 <xsl:variable name="url" select="enclosure/@url" />
 <xsl:variable name="title" select="title" />
 <a href="{link}" target="_blank" class="item">
   <img src="{$url}" class="item" title="{$title}" alt="{$title}"/>
 </a>
       <br />
    <div style=" font-size: 12px; font-family: Verdana, arial, sans-serif">
  <xsl:variable name="description" select="description" />
  <xsl:value-of select="substring($description, '1', '200')" />...
 </div>
 <br />
 <hr/>
</xsl:template>
</xsl:stylesheet>
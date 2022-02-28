<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="container-outer">
            <div class="container-inner">
                <h1 class="title">Profil von <xsl:value-of select="xml/fullname"></xsl:value-of></h1>
                <div class="body">
                    
                    <div class="form-group">
                        <p class="text">Herzlich Wilkommen, <xsl:value-of select="xml/fullname"></xsl:value-of></p>
                    </div>
                    <div class="form-group">
                        <h1 class="text">Kontakt: </h1>
                        <h2 class="value">E-Mail: <xsl:value-of select="xml/email"></xsl:value-of></h2>
                        <h2 class="value">Telefon: <xsl:value-of select="xml/phone"></xsl:value-of></h2>
                    </div>
                    <div class="form-group">
                        <h1 class="text">Wohnort: </h1>
                        <h2 class="value"><xsl:value-of select="xml/fulladdress"></xsl:value-of></h2>
                        <h2 class="value"><xsl:value-of select="xml/fullresidence"></xsl:value-of></h2>
                    </div>
                    <div class="form-group">
                        <h1 class="text">Erstelldatum: </h1>
                        <h2 class="value"><xsl:value-of select="xml/creation_date"></xsl:value-of></h2>
                    </div>

                    <div class="hr"/>

                    <div class="form-group">
                        <a href="/account/changepassword" class="button input">Passwort ändern</a>
                        <a href="/account/deleteaccount" class="button input red">Profil löschen</a>
                    </div>


                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

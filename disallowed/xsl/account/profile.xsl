<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="profile-container-outer">
            <div class="profile-container-inner">
                <h1 class="profile-title">Profil von <xsl:value-of select="xml/fullname"></xsl:value-of></h1>
                <div class="profile-body">
                    
                    <div class="profile-form-group">
                        <p class="profile-text">Herzlich Wilkommen, <xsl:value-of select="xml/fullname"></xsl:value-of></p>
                    </div>
                    <div class="profile-form-group">
                        <h1 class="profile-text">Kontakt: </h1>
                        <h2 class="profile-value">E-Mail: <xsl:value-of select="xml/email"></xsl:value-of></h2>
                        <h2 class="profile-value">Telefon: <xsl:value-of select="xml/phone"></xsl:value-of></h2>
                    </div>
                    <div class="profile-form-group">
                        <h1 class="profile-text">Wohnort: </h1>
                        <h2 class="profile-value"><xsl:value-of select="xml/fulladdress"></xsl:value-of></h2>
                        <h2 class="profile-value"><xsl:value-of select="xml/fullresidence"></xsl:value-of></h2>
                    </div>

                    <div class="hr"/>

                    <div class="profile-form-group">
                        <a href="/account/changepassword" class="profile-button profile-input">Passwort ändern</a>
                        <a href="/account/deleteaccount" class="profile-button profile-input red">Profil löschen</a>
                    </div>


                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

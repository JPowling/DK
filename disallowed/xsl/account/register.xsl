<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="container-outer">
            <div class="container-inner">
                <h1 class="title">Neuen Benutzer Anlegen</h1>
                <div class="body">
                    <form class="form" action="/account/register" method="post">

                        <div class="form-group">
                            <input type="text" name="forename" class="input {//xml/forename_red}" value="{//xml/forename}" placeholder="Vorname" required="" />
                            <input type="text" name="surname" class="input {//xml/surname_red}" value="{//xml/surname}" placeholder="Nachname" required="" />
                        </div>
                        <div class="form-group">
                            <input type="text" name="email" class="input {//xml/email_red}" value="{//xml/email}" placeholder="E-Mail Addresse" required="" />
                            <input type="text" name="email2" class="input {//xml/email_red}" value="{//xml/email2}" placeholder="E-Mail Addresse (Wdh.)" required="" />
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="input {//xml/password_red}" value="{//xml/password}" placeholder="Kennwort" required="" />
                            <input type="password" name="password2" class="input {//xml/password_red}" value="{//xml/password2}" placeholder="Kennwort (Wdh.)" required="" />
                        </div>
                        <div class="form-group">
                            <input type="number" name="phone" class="input {//xml/phone_red}" value="{//xml/phone}" placeholder="Telefonnummer" required="" />
                        </div>
                        <div class="form-group">
                            <input type="text" name="street" class="input" value="{//xml/street}" placeholder="Straße" required="" />
                            <input type="text" name="house" class="input" value="{//xml/house}" placeholder="Hausnummer" required="" />
                        </div>
                        <div class="form-group">
                            <input type="number" name="postal" class="input {//xml/postal_red}" value="{//xml/postal}" placeholder="PLZ" required="" />
                            <input type="text" name="residence" class="input" value="{//xml/residence}" placeholder="Wohnort" required="" />
                        </div>

                        <xsl:for-each select="xml/message">
                            <p class="message">
                                <xsl:value-of select="." />
                            </p>
                        </xsl:for-each>


                        <div class="form-group">
                            <button type="submit" class="button input">Registrieren</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

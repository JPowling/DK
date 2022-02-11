<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="register-container-outer">
            <div class="register-container-inner">
                <h1 class="register-title">Neuen Benutzer Anlegen</h1>
                <div class="register-body">
                    <form class="register-form" action="/account/register" method="post">

                        <div class="register-form-group">
                            <input type="text" name="forename" class="register-input {//xml/register/forename_red}" value="{//xml/register/forename}" placeholder="Vorname" required="" />
                            <input type="text" name="surname" class="register-input {//xml/register/surname_red}" value="{//xml/register/surname}" placeholder="Nachname" required="" />
                        </div>
                        <div class="register-form-group">
                            <input type="text" name="email" class="register-input {//xml/register/email_red}" value="{//xml/register/email}" placeholder="E-Mail Addresse" required="" />
                            <input type="text" name="email2" class="register-input {//xml/register/email_red}" value="{//xml/register/email2}" placeholder="E-Mail Addresse (Wdh.)" required="" />
                        </div>
                        <div class="register-form-group">
                            <input type="password" name="password" class="register-input {//xml/register/password_red}" value="{//xml/register/password}" placeholder="Kennwort" required="" />
                            <input type="password" name="password2" class="register-input {//xml/register/password_red}" value="{//xml/register/password2}" placeholder="Kennwort (Wdh.)" required="" />
                        </div>
                        <div class="register-form-group">
                            <input type="number" name="phone" class="register-input {//xml/register/phone_red}" value="{//xml/register/phone}" placeholder="Telefonnummer" required="" />
                        </div>
                        <div class="register-form-group">
                            <input type="number" name="postal" class="register-input {//xml/register/postal_red}" value="{//xml/register/postal}" placeholder="PLZ" required="" />
                            <input type="text" name="residence" class="register-input" value="{//xml/register/residence}" placeholder="Wohnort" required="" />
                        </div>
                        <div class="register-form-group">
                            <input type="text" name="street" class="register-input" value="{//xml/register/street}" placeholder="Straße" required="" />
                            <input type="text" name="house" class="register-input" value="{//xml/register/house}" placeholder="Hausnummer" required="" />
                        </div>

                        <xsl:for-each select="xml/register/message">
                            <p class="register-message">
                                <xsl:value-of select="." />
                            </p>
                        </xsl:for-each>


                        <div class="register-form-group">
                            <button type="submit" class="register-button register-input">Registrieren</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

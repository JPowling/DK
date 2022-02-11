<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="register-container-outer">
            <div class="register-container-inner">
                <h1 class="register-title">Account Löschen?</h1>
                <div class="register-body">
                    <form class="register-form" action="/account/deleteaccount" method="post">
                        <input type="password" name="password" class="register-input onlyone" value="{//xml/deleteaccount/password}" placeholder="Passwort" required="" />

                        <p class="register-message"><xsl:value-of select="xml/login/message"/></p>

                        <xsl:for-each select="xml/deleteaccount/message">
                            <p class="register-message">
                                <xsl:value-of select="." />
                            </p>
                        </xsl:for-each>

                        <div class="register-form-group">
                            <button type="submit" class="register-button register-input red redtext">Account Löschen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

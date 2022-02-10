<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="register-container-outer">
            <div class="register-container-inner">
                <h1 class="register-title">Anmelden</h1>
                <div class="register-body">
                    <form class="register-form" action="/account/login" method="post">
                        <input type="hidden" name="id" value="login" />

                        <div class="register-form-group">
                            <input type="email" name="email" class="register-input" value="{//xml/login/email}" placeholder="E-Mail" required="" />
                            <input type="password" name="password" class="register-input" value="{//xml/login/password}" placeholder="Passwort" required="" />
                        </div>

                        <p class="register-message"><xsl:value-of select="xml/login/message"></xsl:value-of></p>

                        <div class="register-form-group">
                            <button type="submit" class="register-button register-input">Einloggen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

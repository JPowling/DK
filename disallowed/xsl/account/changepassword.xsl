<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="register-container-outer">
            <div class="register-container-inner">
                <h1 class="register-title">Passwort ändern</h1>
                <div class="register-body">
                    <form class="register-form" action="/account/changepassword" method="post">
                        <input type="password" name="password_old" class="register-input" value="{//xml/changepassword/password_old}" placeholder="Altes Passwort" required="" />

                        <div class="register-form-group">
                            <input type="password" name="password2" class="register-input" value="{//xml/changepassword/password}" placeholder="Neues Passwort" required="" />
                            <input type="password" name="password" class="register-input" value="{//xml/changepassword/password2}" placeholder="Neues Passwort (Wdh.)" required="" />
                        </div>

                        <p class="register-message"><xsl:value-of select="xml/login/message"></xsl:value-of></p>

                        <xsl:for-each select="xml/changepassword/message">
                            <p class="register-message">
                                <xsl:value-of select="." />
                            </p>
                        </xsl:for-each>

                        <div class="register-form-group">
                            <button type="submit" class="register-button register-input">Passwort ändern</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

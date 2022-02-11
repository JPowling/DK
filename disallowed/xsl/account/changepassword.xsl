<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="container-outer">
            <div class="container-inner">
                <h1 class="title">Passwort ändern</h1>
                <div class="body">
                    <form class="form" action="/account/changepassword" method="post">
                        <input type="password" name="password_old" class="input" value="{//xml/password_old}" placeholder="Altes Passwort" required="" />

                        <div class="form-group">
                            <input type="password" name="password2" class="input" value="{//xml/password}" placeholder="Neues Passwort" required="" />
                            <input type="password" name="password" class="input" value="{//xml/password2}" placeholder="Neues Passwort (Wdh.)" required="" />
                        </div>

                        <xsl:for-each select="xml/message">
                            <p class="message">
                                <xsl:value-of select="." />
                            </p>
                        </xsl:for-each>

                        <div class="form-group">
                            <button type="submit" class="button input">Passwort ändern</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

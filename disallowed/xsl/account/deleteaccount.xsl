<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="container-outer">
            <div class="container-inner">
                <h1 class="title">Account Löschen?</h1>
                <div class="body">
                    <form class="form" action="/account/deleteaccount" method="post">
                        <input type="password" name="password" class="input onlyone" value="{//xml/password}" placeholder="Passwort" required="" />

                        <xsl:for-each select="xml/message">
                            <p class="message">
                                <xsl:value-of select="." />
                            </p>
                        </xsl:for-each>

                        <div class="form-group">
                            <button type="submit" class="button input red redtext">Account Löschen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

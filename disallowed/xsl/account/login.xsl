<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="container-outer">
            <div class="container-inner">
                <h1 class="title">Anmelden</h1>
                <div class="body">
                    <form class="form" action="/account/login" method="post">

                        <div class="form-group">
                            <input type="email" name="email" class="input" value="{//xml/email}" placeholder="E-Mail" required="" />
                            <input type="password" name="password" class="input" value="{//xml/password}" placeholder="Passwort" required="" />
                        </div>

                        <p class="message"><xsl:value-of select="xml/message"></xsl:value-of></p>

                        <div class="form-group">
                            <button type="submit" class="button input">Einloggen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

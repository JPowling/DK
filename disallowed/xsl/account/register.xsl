<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="register-container-outer">
            <div class="register-container-inner">
                <h1 class="register-title">Neuen Benutzer Anlegen</h1>
                <div class="register-body">
                    <form class="register-form" action="/index.php" method="post" id="login">
                        <div class="register-form-group">
                            <input type="text" name="forename" class="register-input" placeholder="Vorname" required="" />
                            <input type="text" name="surname" class="register-input" placeholder="Nachname" required="" />
                        </div>
                        <div class="register-form-group">
                            <input type="email" name="email" class="register-input" placeholder="E-Mail Addresse" required="" />
                            <input type="email" name="email" class="register-input" placeholder="E-Mail Addresse (Wdh.)" required="" />
                        </div>
                        <div class="register-form-group">
                            <input type="password" name="password" class="register-input" placeholder="Kennwort" required="" />
                            <input type="password" name="password" class="register-input" placeholder="Kennwort (Wdh.)" required="" />
                        </div>
                        <div class="register-form-group">
                            <input type="number" name="phone" class="register-input" placeholder="Telefonnummer" required="" />
                        </div>
                        <div class="register-form-group">
                            <input type="number" name="postal" class="register-input" placeholder="PLZ" required="" />
                            <input type="text" name="residence" class="register-input" placeholder="Wohnort" required="" />
                        </div>
                        <div class="register-form-group">
                            <input type="number" name="housenumber" class="register-input" placeholder="Hausnummer" required="" />
                        </div>

                        <div class="register-form-group">
                            <button type="submit" class="register-button register-input">Registrieren</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

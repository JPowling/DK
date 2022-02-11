<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="profile-container-outer">
            <div class="profile-container-inner">
                <h1 class="profile-title">Profil</h1>
                <div class="profile-body">
                    
                    <div class="profile-form-group">
                        <a href="/account/changepassword" class="profile-button profile-input">Passwort ändern</a>
                    </div>

                    <a href="/account/deleteaccount" class="profile-button profile-input red">Profil löschen</a>

                </div>
            </div>
        </div>

    </xsl:template>

</xsl:stylesheet>

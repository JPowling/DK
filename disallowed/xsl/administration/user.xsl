<?xml version="1.0" encoding="UTF-8"?>
<!-- Paul -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

        <div class="container-outer">
            <div class="container-inner">
                <h1 class="title">
                    Profil von
                    <xsl:value-of select="xml/fullname"></xsl:value-of>
                </h1>
                <div class="body">

                    <div class="form-group">
                        <p class="text">
                            Du stalkst jetzt: 
                            <xsl:value-of select="xml/fullname"></xsl:value-of>
                        </p>
                    </div>
                    <div class="form-group">
                        <h1 class="text">Kontakt: </h1>
                        <h2 class="value">
                            E-Mail:
                            <xsl:value-of select="xml/email"></xsl:value-of>
                        </h2>
                        <h2 class="value">
                            Telefon:
                            <xsl:value-of select="xml/phone"></xsl:value-of>
                        </h2>
                    </div>
                    <div class="form-group">
                        <h1 class="text">Wohnort: </h1>
                        <h2 class="value">
                            <xsl:value-of select="xml/fulladdress"></xsl:value-of>
                        </h2>
                        <h2 class="value">
                            <xsl:value-of select="xml/fullresidence"></xsl:value-of>
                        </h2>
                    </div>
                    <div class="form-group">
                        <h1 class="text">Erstelldatum: </h1>
                        <h2 class="value">
                            <xsl:value-of select="xml/creation_date"></xsl:value-of>
                        </h2>
                    </div>

                    <div class="hr" />

                    <div class="form-group">
                        <h1 class="text change_rank">Rang ändern:</h1>
                        <a href="/administration/user?id={//xml/id}&amp;newrole=user" class="button input">
                            <xsl:attribute name="class">
                                button input
                                <xsl:if test="xml/rank = 'User'">green</xsl:if>
                            </xsl:attribute>
                            Benutzer
                        </a>
                        <a href="/administration/user?id={//xml/id}&amp;newrole=mod" class="button input">
                            <xsl:attribute name="class">
                                button input
                                <xsl:if test="xml/rank = 'Moderator'">green</xsl:if>
                            </xsl:attribute>
                            Moderator
                        </a>
                        <a href="/administration/user?id={//xml/id}&amp;newrole=admin" class="button input">
                            <xsl:attribute name="class">
                                button input
                                <xsl:if test="xml/rank = 'Admin'">green</xsl:if>
                            </xsl:attribute>
                            Administrator
                        </a>

                        
                    </div>


                </div>
            </div>
        </div>

        <a href="/administration/user?id={//xml/id}&amp;delete=yes" class="button input ALARM">
            !Profil Löschen!
        </a>
    </xsl:template>

</xsl:stylesheet>

<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/" mode="mode">

    <form id="regsister" action="/register" method="post">
        <fieldset>
          <legend>Neues Benutzerkonto</legend>
          <div class="field">
            <input name="LoginName" id="LoginName" type="text" maxlength="32" required="" />
            <label for="LoginName">Benutzername</label>
          </div>
          <div class="field">
            <input name="Password1" id="Password1" type="password" maxlength="256" required="" />
            <label for="Password1">Passwort</label>
          </div>
          <div class="field">
            <input name="Password2" id="Password2" type="password" maxlength="256" required="" />
            <label for="Password2">Passwort (Wdh.)</label>
          </div>
          <div class="field">
            <input name="EMailAddr" id="EMailAddr" type="email" required="" />
            <label for="EMailAddr">E-Mail-Adresse</label>
          </div>
          <div class="field">
            <input name="LastName" id="LastName" type="text" maxlength="128" />
            <label for="LastName">Nachname</label>
          </div>
          <div class="field">
            <input name="FirstName" id="FirstName" type="text" maxlength="128" />
            <label for="FirstName">Vorname</label>
          </div>
        </fieldset>
        <button id="Submit" name="Submit">Registrieren</button>
      </form>


    </xsl:template>

</xsl:stylesheet>

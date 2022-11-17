# Informationen

Wir haben in folgenden Ordner, die ToDo's des Kunden umgesetzt. Hier sind die Links:
https://github.com/FIA-GSO/Lehrerkalender-FI009/tree/main/packages/chanathale_gso

Staging-Seite: https://lehrerkalender.thescape.de/

Staging-Seite (Loginbereich für Benutzer:in): https://lehrerkalender.thescape.de/login

Staging-Seite Abteilungsleiter-Login: https://lehrerkalender.thescape.de/typo3

---

### Beipiel - Zugangsdaten mit Testdaten für https://lehrerkalender.thescape.de/login

Benutzername: dev

Passwort: dev

---

### Beispiel - Zugangsdaten für Abteilungsleiter https://lehrerkalender.thescape.de/typo3

Benutzername: alexander.faller

Passwort: in

---

### Hier ist das HTML des Lehrerkalender und die JavaScript - Modul dazu.

Template: https://github.com/FIA-GSO/Lehrerkalender-FI009/blob/main/packages/chanathale_gso/Resources/Private/Templates/Calendar/Show.html

JavaScript
ES6-Modul: https://github.com/FIA-GSO/Lehrerkalender-FI009/blob/main/packages/chanathale_customer/Resources/Private/Assets/JavaScript/components/teachercalendar/teachercalendar.js

Der Kalender wurde über fullcalender implementiert und über npm installiert.

---
Als Frontend-Framework verwenden wir vanilla Bootstrap 5.2 das über npm installiert wurde.

---

### Warum haben wir uns für TYPO3 und php entschieden?

- Wir haben vier php-Entiwckler in unsere Gruppe (Max, Aphisit, Martin, und Hendrik). Es in eine unbekannte / nicht
  gewohnte Programmiersprache umzusetzen würde den Aufwand und Zeit erhöhen.
- TYPO3 bietet bereits ein Frontend-Login Bereich an, und dadurch müssen wir nicht selbst ein Loginlogik programmieren,
  und dadurch sehr viel Zeit bei der Umsetzung spart.
- Es bietet bereits das Verwalten der benötigen Datensätzen (Raum, Leistungs, Schüler , Klasse usw...) wodurch wir keine
  SQL-Statement selber schreiben müssen, da es von TYPO3 bereits welche zu Verfügung gibt. Der Vorteil dadurch ist das
  wir Zeit sparen und mehr Puffer haben für Testing und anderen Features,
- PHP eignete sich gut für Webtools wie der Lehrerkalender.



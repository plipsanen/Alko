# Alkon tuotekatalogi

##  Yleiskuvaus:
Tämä on PHP‑pohjainen web‑sovellus, joka näyttää Alkon tuotekatalogin taulukkomuodossa.  
Käyttäjä voi:
- Suodattaa tuotteita tyypin, valmistusmaan, pullokoon, hinnan ja energiasisällön perusteella.
- Selailla tuotteita sivutuksen avulla (25 riviä per sivu).
- Nähdä hinnaston viimeisimmän päivityspäivän.

---

## Päivitysprosessi:
Palvelu hakee hinnaston **suoraan Alkon sivuilta** [Excel‑tiedostona](
https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx).

Excel muunnetaan automaattisesti CSV‑muotoon (`data/alkon-hinnasto-ascii.csv`), jota sovellus käyttää datan näyttämiseen.

Kun käyttäjä avaa sivun (`index.php`), ensimmäisenä kutsutaan `updateAlkoDataFromWeb();`

---

## Sivu:
- Lataa Excel‑tiedoston Alkon sivuilta cURL:lla.
- Tallentaa sen väliaikaiseen tiedostoon.
- Parsii sisällön SimpleXLSX‑kirjastolla.
- Kirjoittaa rivit CSV‑tiedostoon (alkon-hinnasto-ascii.csv).

Hinnasto päivittyy automaattisesti Alkon sivuilta aina kun sivu avataan ja päivämäärä kertoo aina, milloin hinnasto on viimeksi päivitetty.

## Koodit

- [index.php](https://github.com/plipsanen/Alko/blob/main/index.php)
- [controller.php](https://github.com/plipsanen/Alko/blob/main/controller.php)
- [config.php](https://github.com/plipsanen/Alko/blob/main/config.php)
- [model.php](https://github.com/plipsanen/Alko/blob/main/model.php)
- [view.php](https://github.com/plipsanen/Alko/blob/main/view.php)
- [style.css](https://github.com/plipsanen/Alko/blob/main/styles.css)

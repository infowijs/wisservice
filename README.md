# Somtoday WISService SDK for PHP

This library will allow you to connect with Somtoday's SOAP webservice, which contains information about
students, teachers, parents, grades, absence and schedules.


## Authentication and credentials

To ease the testing of the examples, all examples use the same credentials file. To get started, copy or
rename the `credentails.example.json` file to `credentials.json`, and fill it with your own credentails:

#### `portal`
The portal name of the school's Somtoday installation. To find this, sign in on [somtoday.com](http://somtoday.com).

After signing in, check the URL. Example: if the URL is `https://bc-oop.somtoday.nl`, your `portal` value should be `bc`.

#### `brin`
The BRIN number of the school (or _deelschool_) you wish to connect with. This is usually a 4-digit code that consists of letters and numbers. You can look these up on the [DUO website](https://duo.nl/open_onderwijsdata/databestanden/vo/adressen/).

#### `username` and `password`
To get access to the WISService, you need to create a user in Somtoday with the appropriate roles and permissions. Instructions [can be found here](https://docs.includable.com/user-manual-nl/integraties/somtoday/integreren-met-somtoday/).


## Running the examples

Run `composer install` in the `php` subdirectory. After doing this, you can run the examples in the
`examples` directory, either through the CLI or through a web browser.

Note that when executing the examples via CLI, you need to `cd` into the actual examples directory:
`cd php && php examples/get_grades.php` won't work, but `cd php/examples && php get_grades.php` will!

The examples should be clear enough to get started writing your own scripts with.


## Exploring endpoints

As there is no official documentation about the parameters accepted by each SOAP call endpoint, you can use a
tool like [SOAPUI](https://www.soapui.org/downloads/soapui.html). This is that same tool that Topicus uses
internally to develop and test the WISService endpoints.

### Autocompletion

The library is set up to autocomplete these function names in an IDE that supports PHPDoc.

### Notes on field formats

* Fields that require a date (e.g. `peilDatum`), all use ISO 8601 formatting.

### Endpoints

<details><summary>**View the full list of endpoints**</summary>

The following endpoints are available:

* getLeerling
* getMedewerkerMetPasfoto
* getVerzorger
* saveVerzorger
* getExamenOpdracht
* getCijferOverzicht
* getPreCacheVerzorgers
* getDocentVestigingen
* getStamgroepenlijst
* getLeerlingPlaatsing
* getVerzorgerLeerlingen
* getOpleidingLestijden
* getMedewerkers
* getLeerlingAbsentieConstateringen
* getVestigingen
* schrijfInVoorKeuzeWerkTijd
* getExamenDossier
* getOpleidingVakken
* getLeerlingRelaties
* getLeerlingAbsentieMeldingen
* getMedewerkerRooster
* saveMedewerker
* getLesgroepenlijst
* getVestigingDocenten
* getKlasLeerlingenBijStamgroep
* getLeerlingRooster
* getBevoegdGezag
* getVestigingLestijden
* getMedewerkerByUsername
* getLeerlingVakkenpakket
* getBetrokkenDocentenBijLeerling
* getLeerlingMentoren
* getMedewerkersJarigInMaand
* saveToestemmingLeerling
* getKlasLeerlingenBijLesgroep
* getOpleidingen
* getInstelling
* getMedewerker
* schrijfUitVoorKeuzeWerkTijd
* saveLeerling
* getVestigingVakken
* getLeerlingMetPasfoto
* getLeerlingAbsentieTotalen

</details>

## Credits

This library is developed by [Infowijs](https://infowijs.nl/?utm_source=github), an agency focussed on
buidling tools to improve communication in education and governments.

Contributors:

* Thomas Schoffelen, [@tschoffelen](https://twitter.com/tschoffelen)

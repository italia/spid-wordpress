# SPID-WordPress

Plugin per integrare il sistema di autenticazione SPID all'interno di WordPress.

Sotto il cofano il plugin utilizza la libreria [spid-sp-simplesamlphp](https://github.com/italia/spid-sp-simplesamlphp).

## TODO

* Domare [spid-sp-simplesamlphp](https://github.com/italia/spid-sp-simplesamlphp/issues/12).

## Contenuto

La struttura del repository contiene i seguenti file:
* [/spid-wordpress](spid-wordpress) - Il codice del plugin vero e proprio
* [Vagrantfile](Vagrantfile) - Descrive il contenuto della vagrant box
* [/scripts](scripts) - Contiene i file di configurazione per la vagrant box
* [/doc](doc) - Documentazione del plugin

## Installazione

**ATTENZIONE**: Questo plugin è formalmente pronto ma **NON È STATO MAI TESTATO**.

Da un'installazione funzionante di WordPress, installare il plugin copiando la directory `spid-wordpress` fra i plugin di WordPress e lanciare `composer install`:

    cp -R /my/home/spid-wordpress/spid-wordpress /path/to/wordpress/wp-content/plugins
    cd /path/to/wordpress/wp-content/plugins/spid-wordpress
    composer install

Poiché [italia/spid-sp-simplesamlphp](https://github.com/italia/spid-sp-simplesamlphp/) (che si chiama anche italia/spid-simplesamlphp)
non è presente su Packagist, è necessario installarne le dipendenze a mano:

    cd /path/to/wordpress/wp-content/plugins/spid-wordpress/vendor/italia/spid-simplespidphp
    composer install
    cd /path/to/wordpress/wp-content/plugins/spid-wordpress
    composer dump-autoload

In seguito, da WordPress, abilitare il plugin.

## Configurazione

Alcuni comportamenti inerenti WordPress sono configurabili direttamente nel menù `Impostazioni` > `SPID login`.

Il comportamento dell'autenticazione SPID è gestito dalla libreria [SPID SP SimpleSamlPHP](https://github.com/italia/spid-sp-simplesamlphp). Rifarsi alla sua documentazione.

## Hacking

Riteniamo che sia più semplice installare una LAMP + WordPress, che installare [Vagrant](https://www.vagrantup.com/) :) In ogni caso potete lanciare `vagrant up` dalla directory principale del repositoy per ottenere una LAMP con WordPress con plugin installato, accessibile all'indirizzo [http://localhost:8080](http://localhost:8080).

Lo sviluppo di questo repository è attualmente mantenuto dall'Italian Linux Society ed è pubblicato dall'Agenzia per l'Italia digitale. Ogni contributo esterno è assolutamente bene accetto sotto forma di issue e/o pull request. Ogni contributo deve avvenire nel rispetto dei termini della licenza. Sono particolarmente apprezzate le pull request composte da piccoli commit atomici (scoraggiando mega commit monolitici).

## Licenza

Copyright (C) 2016-2017 Italian Linux Society, Valerio Bozzolan, Ludovico Pavesi.

Questo programma è software libero: puoi redistribuirlo e/o modificarlo rispettando le condizioni della [GNU General Public license](LICENSE.md) pubblicata dalla Free Software Foundation. Si considera sia la versione 3 della Licenza, o (a tua discrezione) qualsiasi versione successiva. Questo programma è distribuito nella speranza che sia utile ma SENZA ALCUNA GARANZIA; senza neppure qualsiasi implicità garanzia di COMMERCIABILITÀ o di IDONEITÀ AD UN PARTICOLARE SCOPO. Vedi la GNU General Public License per ulteriori dettagli.

Dovresti aver ricevuto una [copia](LICENSE.md) della GNU General Public License insieme a questo programma. In caso contrario, visita <http://www.gnu.org/licenses/>.

# WordPress plugin per SPID

Plugin per integrare il sistema di autenticazione SPID all'interno di WordPress.

## Contenuto

La struttura del repository contiene i seguenti file:
* `README.md` - Guida alla struttura del repository
* [`CHANGELOG.md`](/CHANGELOG.md) - Contiene la traccia delle modifiche fatte al plugin.
* [`doc/`](/doc) - Documentazione del plugin
* [`spid-wordpress/`](/spid-wordpress/) - Il codice del plugin
* `scripts` - Contiene i file di configurazione per la vagrant box
* `Vagrantfile` - Descrive il contenuto della vagrant box

## Installazione

Per installare il plugin è necessario copiare la directory `spid-wordpress` all'interno della cartella dei plugin:

`cp -R /my/home/spid-wordpress/spid-wordpress /path/to/wordpress/wp-content/plugins`

**WORK IN PROGRESS: NON INSTALLATE QUESTO PLUGIN**

## Configurazione
*TODO*

## Sviluppo

Per agevolare lo sviluppo del plugin è stato creato un `Vagrantfile` per creare una VM sul proprio ambiente di sviluppo pronta per l'utilizzo del plugin.
Per poter creare l'ambiente basta digitare nella directory del repository:

   `vagrant up`

Finito il processo di provisioning potete procedere con l'installazione di wordpress tramite il link:

   `http://localhost:8080/wordpress`



## Licenza

=======
>>>>>>> 3163bee2d37fe4aefd89c35f2fab6f637c51fccf
GNU GPL v3+.

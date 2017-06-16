# SPID-WordPress
Connette WordPress e SPID, in qualche modo. Per ora non connette nulla, a dire il vero.

**Work in progress**

## Hacking

    git clone --recursive git@github.com:ItalianLinuxSociety/spid-wordpress.git

O almeno dare `git submodule update` dopo il clone, per scaricare anche i submodule.

In realtà i submodule non servono più a nulla e forse li toglieremo, però bisogna fare `composer install`
che scarica l'intero repo di simplespidphp e viene bloccato a metà dal rate limiting delle API di Github
e bisogna andare a creare un token e serve l'account su Github e direi che è un metodo che non scala,
ma proprio per nulla.

Diciamo che è tutto altamente **Work in progress**, in caso non fosse chiaro.

## TODO

### Parti base
- [X] Scegliere una licenza consona (GPLv3+, MIT, o Apache 2.0?)
- [ ] Capire come funziona SPID
- [X] Capire come si scrive un plugin WordPress
- [X] Utilizzare la libreria [SimpleSpidphp](https://github.com/dev4pa/simplespidphp)
    - [ ] ~~Utilizzare `require_once plugin_dir_path( dirname( __FILE__ ) ) . 'simplespidphp/...';` dove sarà necessario~~
    - [ ] Capire come includere correttamente SimpleSpidphp
        - [ ] Capire come includere versioni che non siano la v2.0, se servisse (simplespidphp-1.0 non è una versione valida, per Composer)
        - [ ] Capire perché tra le dipendenze ci sia uno specifico commit, o forse in generale l'ultimo commit sul master, di saml2, invece che una versione stabile
- [X] Trarre ispirazione da [SPID-Drupal](https://github.com/dev4pa/spid-drupal)
- [X] Scrivere il codice.
    - [X] scrivere e cancellare molti var_dump()
    - [X] Capire come funzionano i `submodule`s
    - [X] Capire se non siamo denunciati se usiamo il materiale di Poste SIELTE & compagnia cantante
        - [X] Aggiunto un submodule con il materiale legale recuperato da Commons se che non va bene andate a denunciare Wikimedia Foundation e non noi
- [X] contattare gente da contattare
- [ ] Finire di aggiungere immagini degli IDP
    - [ ] Capire come gestire il fatto che in futuro possano esserci nuovi IDP (= monitorare la situazione e aggiungerli a mano?)

### Test, debugging, prove
- [ ] Attendere gli ambienti di test
    - [ ] Chiederli a @salvorapi?
    - [X] procurarsi esempi di risposta XML
- [ ] Capire in via teorica come faranno le università a cablare cose tra IDEM e SPID, visto che SPID non può essere una federazione di federazioni e IDEM è un'altra federazione
- [ ] Chiedere a @salvorapi come configurare la libreria SimpleSPIDPhp, come suggerito da @umbros

### Altre feature e considerazioni di sicurezza
- [ ] Loggare gli "scontrini" SPID (risposte XML o qualcosa del genere di autenticazione)
    - [ ] Cacciare tutto in una tabella del db, per capire, tracciare, memorizzare, "certificare" che "Tizio si è loggato con SPID col provider X"
        - [ ] Rendersi conto che ciò andrebbe associato alle operazioni compiute dopo, ma è complicato...
    - [ ] Controllare che ciò non vada contro le leggi contro il tracciamento (e.g. cookie law, non si applica strettamente ai cookie)
- [X] Tenere presente che le PA devono consentire accesso **solo** con SPID o CNS-CIE, l'opzione per consentire altri metodi *non* va utilizzata dalle PA!
    - [ ] Informarsi su quale parte del CAD stia buttando il login on accreditamento locale [cit. @valerio-bozzolan, non so che vuol dire]

## Riferimenti interessanti
* https://codex.wordpress.org/Function_Reference/wp_insert_user
* https://codex.wordpress.org/Function_Reference/wp_signon
* https://codex.wordpress.org/Function_Reference/wp_insert_user
* https://wordpress.stackexchange.com/questions/53503/can-i-programmatically-login-a-user-without-a-password/128445#128445
* https://gist.github.com/raewrites/233ad31432cb112f8bef
* https://plugins.svn.wordpress.org/openid/trunk/login.php
* https://github.com/italia/spid-graphics

Da https://wordpress.org/plugins/openid/:
```
    // Per utenti già esistenti
    $user = new WP_User( $user_id );
    $credentials = [
        'user_login' => $user->user_login,
        'user_password' => $user_data['user_pass'],
        'remember' => true
    ];
    if( ! wp_signon( $credentials ) ) {
        // Error bug asd
    } else {
        // Logged
    }
````

## Sicurezza

### Limitazione all'accesso
Dato che qualunque sistemista che lavora per TIM, Poste, Info Cert, Sielte, ecc.,
ha la potenziale possibilità di loggarsi nel mio account universitario o in
qualsiasi altro servio con supporto a SPID, questo plugin per WordPress comprenderà
una pagina di impostazioni per permettere al singolo utente di disabilitare l'accesso
SPID in todo, o da certi provider.

### Tracciamento operazioni
È utile che venga memorizzata data e ora di ogni login con SPID, e anche la data e ora
di fine della sessione (= logout, o login con altro metodo, o altro login con SPID),
in modo da poter "dimostrare" che qualcuno si è loggato con un account e ha fatto cosa,
per casi di account rubati, sistemisti maligni che si divertono a usare account di altri,
etc...

Bisognerebbe tracciare le operazioni compiute, ma poi il plugin diventa abnorme e
complicatissimo: allora si memorizza data e ora, e sarà cura del resto del sito memorizzare
data e ora delle operazioni, in modo da poter verificare o smentire l'appartenenza di
queste operazioni all'intervallo di una sessione.

Ciò implica che se un utente fa qualsiasi altra operazione di login/logout dopo il
login con SPID, la sessione autenticata con SPID va terminata con la forza, altrimenti
uno fa il login con SPID e poi con username e password e fa cose da lì e risultano fatte
con SPID, che non sarebbe vero.

## Licenza
GPLv3 o successive.

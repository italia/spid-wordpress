# SPID-Wordpress
Connette Wordpress e SPID, in qualche modo. Per ora non connette nulla, a dire il vero.

**Work in progress**

## Hacking

    git clone --recursive git@github.com:ItalianLinuxSociety/spid-wordpress.git

O almeno dare `git submodule update` dopo il clone, per scaricare anche i submodule.


## TODO
- [X] Scegliere una licenza consona (GPLv3+, MIT, o Apache 2.0?)
- [ ] Capire come funziona SPID
- [X] Capire come si scrive un plugin Wordpress
- [X] Utilizzare la libreria [SimpleSpidphp](https://github.com/dev4pa/simplespidphp)
    - [ ] Utilizzare `require_once plugin_dir_path( dirname( __FILE__ ) ) . 'simplespidphp/...';` dove sarà necessario
    - [ ] Capire come includere correttamente SimpleSpidphp
- [X] Trarre ispirazione da [SPID-Drupal](https://github.com/dev4pa/spid-drupal)
- [X] Scrivere il codice.
    - [X] scrivere e cancellare molti var_dump()
    - [X] Capire come funzionano i `submodule`s
    - [X] Capire se non siamo denunciati se usiamo il materiale di Poste SIELTE & compagnia cantante
        - [X] Aggiunto un submodule con il materiale legale recuperato da Commons se che non va bene andate a denunciare Wikimedia Foundation e non noi
- [X] contattare gente da contattare

## Riferimenti interessanti
* https://codex.wordpress.org/Function_Reference/wp_insert_user
* https://codex.wordpress.org/Function_Reference/wp_signon
* https://codex.wordpress.org/Function_Reference/wp_insert_user
* https://wordpress.stackexchange.com/questions/53503/can-i-programmatically-login-a-user-without-a-password/128445#128445
* https://gist.github.com/raewrites/233ad31432cb112f8bef
* https://plugins.svn.wordpress.org/openid/trunk/login.php

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
Dato che qualunque sistemista che lavora per TIM, Poste, Info Cert, Sielte, ecc.,
ha la potenziale possibilità di loggarsi nel mio account universitario o in
qualsiasi altro servio con supporto a SPID, questo plugin per WordPress comprenderà
una pagina di impostazioni per permettere al singolo utente di disabilitare l'accesso
SPID in todo, o da certi provider.

## Licenza
GPLv3 o successive.

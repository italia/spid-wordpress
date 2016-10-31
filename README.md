# SPID-Wordpress
Connette Wordpress e SPID, in qualche modo. Per ora non connette nulla, a dire il vero.

**Work in progress**

## TODO
- [X] Scegliere una licenza consona (GPLv3+, MIT, o Apache 2.0?)
- [ ] Capire come funziona SPID
- [ ] Capire come si scrive un plugin Wordpress
- [X] Utilizzare la libreria [SimpleSpidphp](https://github.com/dev4pa/simplespidphp)
- [ ] Trarre ispirazione da [SPID-Drupal](https://github.com/dev4pa/spid-drupal)
- [ ] Scrivere il codice.

## Riferimenti interessanti
* https://codex.wordpress.org/Function_Reference/wp_insert_user
* https://codex.wordpress.org/Function_Reference/wp_signon
* https://codex.wordpress.org/Function_Reference/wp_insert_user

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
Dato che qualunque sistemista che lavora per TIM, Poste, Info Cert, Sielte, ecc., ha la potenziale possibilità di loggarsi nel mio account universitario o in qualsiasi altro servio con supporto a SPID, questo plugin per WordPress comprenderà una pagina di impostazioni per permettere al singolo utente di disabilitare l'accesso SPID in todo, o da certi provider.

## Licenza
GPLv3 o successive.

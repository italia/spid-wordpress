# SPID-WordPress flow 

1. An user visits the page of a WordPress site with SPID-Wordpress plugin enabled
2. Requiring [/spid-wordpress.php](spid-wordpress/spid-wordpress.php)
3. Requiring [/includes/class-spid-wordpress.php](spid-wordpress/includes/class-spid-wordpress.php) providing `Spid_Wordpress` class
4. Calling `Spid_Wordpress::factory()->run()`:
    * [includes/class-spid-wordpress-loader.php](spid-wordpress/includes/class-spid-wordpress-loader.php) - manages actions and filters
    * [includes/class-spid-wordpress-i18n.php](spid-wordpress/includes/class-spid-wordpress-i18n.php) - loads internationalization
    * [admin/class-spid-wordpress-admin.php](spid-wordpress/admin/class-spid-wordpress-admin.php) - registers backend pages
    * [includes/class-spid-wordpress-settings.php](spid-wordpress/admin/class-spid-wordpress-settings.php) - manages settings
    * [includes/class-spid-wordpress-login.php](spid-wordpress/includes/class-spid-wordpress-login.php) - handles SPID requests
    * [includes/class-spid-wordpress-user-meta.php](spid-wordpress/includes/class-spid-wordpress-user-meta.php) - manages user options
    * [includes/class-spid-wordpress-shortcodes.php](spid-wordpress/includes/class-spid-wordpress-shortcodes.php) - registers shortcodes
        1. Is the user arriving from a button click? (e.g. `?init_spid_login=1&idp=foo`)
            * Calling `Spid_Wordpress_Login::factory()->spid_startsso( $_GET['idp'] );`
                1. WordPress die redirecting to the IDP login page
                2. User logins into the IDP
                3. The IDP redirects back to WordPress (e.g. `?return_from_sso=1`)
        2. Is the user arriving from the IDP login? (e.g. `?return_from_sso=1`)
            * See `SimpleSAML_Auth_Simple` [documentation](https://simplesamlphp.org/docs/stable/simplesamlphp-sp-api).

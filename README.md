Gurme - keep your calories on track
========================

Po "git clone" reikia atlikti šias komandas:

    composer install

    php app/console doctrine:database:create

    php app/console doctrine:schema:update --force

    php app/console fos:user:create

    bower install   // visa frontend'a instaliuoti

    npm install     // reikalingas package.json paketams sudiegti

    grunt           // nenaudojamt!

    gulp            // generuojam css ir js

Pasiūlymai
-------------------------------------
- My menu, kad sudarytų programą, nes žmogus atėjęs pirma kartą nežino, kiek jis norės kalorijų suvalgyti
- Gal nebūtinai įrašyti produktą, bet pasirinkti, iš esamų produktų sarašo

Notes:
-------------------------------------
marvel app - wireframe'ams

() Konfiguracija
-------------------------------------

'php app/check.php'
Rodo WARNING'us...

'http://localhost/path/to/symfony/app/web/config.php'
Rpdp WARNING'us...

Reikia ištaisyt.

() Kaip taisyklingai ištrinti ACME bundle.
-------------------------------

  * delete the `src/Acme` directory;

  * remove the routing entry referencing AcmeDemoBundle in `app/config/routing_dev.yml`;

  * remove the AcmeDemoBundle from the registered bundles in `app/AppKernel.php`;

  * remove the `web/bundles/acmedemo` directory;

  * remove the `security.providers`, `security.firewalls.login` and
    `security.firewalls.secured_area` entries in the `security.yml` file or
    tweak the security configuration to fit your needs.

Naudingos nuorodos:
-------------------------------

- http://symfony.com/doc/2.4/book/installation.html
- http://getcomposer.org/
- http://symfony.com/download
- http://symfony.com/doc/2.4/quick_tour/the_big_picture.html
- http://symfony.com/doc/2.4/index.html
- http://symfony.com/doc/2.4/bundles/SensioFrameworkExtraBundle/index.html
- http://symfony.com/doc/2.4/book/doctrine.html
- http://symfony.com/doc/2.4/book/templating.html
- http://symfony.com/doc/2.4/book/security.html
- http://symfony.com/doc/2.4/cookbook/email.html
- http://symfony.com/doc/2.4/cookbook/logging/monolog.html
- http://symfony.com/doc/2.4/cookbook/assetic/asset_management.html
- http://symfony.com/doc/2.4/bundles/SensioGeneratorBundle/index.html
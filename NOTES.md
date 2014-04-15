------------------------
Best practice database schema -> entity

Padaro visos db lenteliu entity'cius į entity direktorija su annotation
    php app/console doctrine:mapping:import GurmeMainBundle annotation
Sutvarko getters and setter ir dar kažką
    php app/console doctrine:generate:entities Gurme/MainBundle
------------------------
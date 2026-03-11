<?php

return [

    /*
     *
     * Shared translations.
     *
     */
    'title' => config('installer.item_name') . ' Installateur',
    'next' => 'Étape suivante',
    'back' => 'Précédent',
    'finish' => 'Installer',
    'forms' => [
        'errorTitle' => 'Les erreurs suivantes sont survenues :',
    ],

    /*
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'templateTitle' => 'Bienvenue',
        'title'   => config('installer.item_name') . ' Installateur',
        'message' => 'Assistant d\'installation et de configuration simple.',
        'next'    => 'Vérifier les prérequis',
    ],

    /*
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Étape 1 | Prérequis serveur',
        'title' => 'Prérequis serveur',
        'next'    => 'Vérifier les permissions',
    ],

    /*
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'templateTitle' => 'Étape 2 | Permissions',
        'title' => 'Permissions',
        'next' => 'Configurer l\'environnement',
    ],

    /*
     *
     * License page translations.
     *
     */
    'license' => [
        'templateTitle' => 'Étape 3 | Vérification de licence',
        'title' => 'Vérification de licence',
        'next' => 'Vérifier',
    ],

    /*
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu' => [
            'templateTitle' => 'Étape 3 | Paramètres d\'environnement',
            'title' => 'Paramètres d\'environnement',
            'desc' => 'Veuillez sélectionner la manière de configurer le fichier <code>.env</code>.',
            'wizard-button' => 'Assistant de formulaire',
            'classic-button' => 'Éditeur de texte classique',
        ],
        'wizard' => [
            'templateTitle' => 'Étape 4 | Configuration de l\'environnement et de la base',
            'title' => 'Configuration de l\'environnement et de la base',
            'tabs' => [
                'environment' => 'Environnement',
                'database' => 'Base de données',
                'application' => 'Application',
            ],
            'form' => [
                'name_required' => 'Un nom d\'environnement est requis.',
                'app_name_label' => 'Nom de l\'application',
                'app_name_placeholder' => 'Nom de l\'application',
                'app_environment_label' => 'Environnement de l\'application',
                'app_environment_label_local' => 'Local',
                'app_environment_label_developement' => 'Développement',
                'app_environment_label_qa' => 'QA',
                'app_environment_label_production' => 'Production',
                'app_environment_label_other' => 'Autre',
                'app_environment_placeholder_other' => 'Entrez votre environnement...',
                'app_debug_label' => 'Débogage de l\'application',
                'app_debug_label_true' => 'Vrai',
                'app_debug_label_false' => 'Faux',
                'app_log_level_label' => 'Niveau de journalisation',
                'app_log_level_label_debug' => 'debug',
                'app_log_level_label_info' => 'info',
                'app_log_level_label_notice' => 'notice',
                'app_log_level_label_warning' => 'warning',
                'app_log_level_label_error' => 'error',
                'app_log_level_label_critical' => 'critical',
                'app_log_level_label_alert' => 'alert',
                'app_log_level_label_emergency' => 'emergency',
                'app_url_label' => 'URL de l\'application',
                'app_url_placeholder' => 'URL de l\'application',
                'db_connection_failed' => 'Impossible de se connecter à la base de données.',
                'db_connection_label' => 'Connexion à la base de données',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Hôte de la base de données',
                'db_host_placeholder' => 'Hôte de la base de données',
                'db_port_label' => 'Port de la base de données',
                'db_port_placeholder' => 'Port de la base de données',
                'db_name_label' => 'Nom de la base de données',
                'db_name_placeholder' => 'Nom de la base de données',
                'db_username_label' => 'Nom d\'utilisateur de la base',
                'db_username_placeholder' => 'Nom d\'utilisateur de la base',
                'db_password_label' => 'Mot de passe de la base de données',
                'db_password_placeholder' => 'Mot de passe de la base de données',

                'app_tabs' => [
                    'more_info' => 'Plus d\'infos',
                    'broadcasting_title' => 'Diffusion, cache, session et file d\'attente',
                    'broadcasting_label' => 'Pilote de diffusion',
                    'broadcasting_placeholder' => 'Pilote de diffusion',
                    'cache_label' => 'Pilote de cache',
                    'cache_placeholder' => 'Pilote de cache',
                    'session_label' => 'Pilote de session',
                    'session_placeholder' => 'Pilote de session',
                    'queue_label' => 'Pilote de file d\'attente',
                    'queue_placeholder' => 'Pilote de file d\'attente',
                    'redis_label' => 'Pilote Redis',
                    'redis_host' => 'Hôte Redis',
                    'redis_password' => 'Mot de passe Redis',
                    'redis_port' => 'Port Redis',

                    'mail_label' => 'E-mail',
                    'mail_driver_label' => 'Pilote mail',
                    'mail_driver_placeholder' => 'Pilote mail',
                    'mail_host_label' => 'Hôte mail',
                    'mail_host_placeholder' => 'Hôte mail',
                    'mail_port_label' => 'Port mail',
                    'mail_port_placeholder' => 'Port mail',
                    'mail_username_label' => 'Nom d\'utilisateur mail',
                    'mail_username_placeholder' => 'Nom d\'utilisateur mail',
                    'mail_password_label' => 'Mot de passe mail',
                    'mail_password_placeholder' => 'Mot de passe mail',
                    'mail_encryption_label' => 'Chiffrement mail',
                    'mail_encryption_placeholder' => 'Chiffrement mail',

                    'pusher_label' => 'Pusher',
                    'pusher_app_id_label' => 'ID d\'application Pusher',
                    'pusher_app_id_palceholder' => 'ID d\'application Pusher',
                    'pusher_app_key_label' => 'Clé d\'application Pusher',
                    'pusher_app_key_palceholder' => 'Clé d\'application Pusher',
                    'pusher_app_secret_label' => 'Secret d\'application Pusher',
                    'pusher_app_secret_palceholder' => 'Secret d\'application Pusher',
                ],
                'buttons' => [
                    'setup_database' => 'Configurer la base et l\'environnement',
                    'setup_application' => 'Configurer l\'application',
                    'install' => 'Installer',
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => 'Étape 3 | Paramètres d\'environnement | Éditeur classique',
            'title' => 'Éditeur d\'environnement classique',
            'save' => 'Enregistrer .env',
            'back' => 'Utiliser l\'assistant',
            'install' => 'Enregistrer et installer',
        ],
        'success' => 'Les paramètres du fichier .env ont été enregistrés.',
        'errors' => 'Impossible d\'enregistrer le fichier .env. Veuillez le créer manuellement.',
    ],

    'install' => 'Installer',

    /*
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => config('installer.item_name') . ' installé avec succès le ',
    ],

    /*
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'Installation terminée',
        'templateTitle' => 'Installation terminée',
        'finished' => 'L\'application a été installée avec succès.',
        'migration' => 'Sortie de la migration &amp; seed :',
        'console' => 'Sortie console de l\'application :',
        'log' => 'Entrée du journal d\'installation :',
        'env' => 'Fichier .env final :',
        'exit' => 'Cliquez ici pour quitter',
    ],

    /*
     *
     * Update specific translations
     *
     */
    'updater' => [
        /*
         *
         * Shared translations.
         *
         */
        'title' => 'Mise à jour Laravel',

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'title'   => 'Bienvenue dans l\'outil de mise à jour',
            'message' => 'Bienvenue dans l\'assistant de mise à jour.',
        ],

        /*
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'   => 'Aperçu',
            'message' => 'Il y a 1 mise à jour.|Il y a :number mises à jour.',
            'install_updates' => 'Installer les mises à jour',
        ],

        /*
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'Terminé',
            'finished' => 'La base de données de l\'application a été mise à jour avec succès.',
            'exit' => 'Cliquez ici pour quitter',
        ],

        'log' => [
            'success_message' => 'Laravel Installer mis à jour avec succès le ',
        ],
    ],
];

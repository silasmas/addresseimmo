<?php

/**
 * Configuration du système d'installation AddressImmo.
 */
return [

  /*
  |--------------------------------------------------------------------------
  | Version applicative
  |--------------------------------------------------------------------------
  */
  'version' => env('APP_INSTALL_VERSION', '1.0.0'),

  /*
  |--------------------------------------------------------------------------
  | Rôle super administrateur
  |--------------------------------------------------------------------------
  */
  'admin_role' => 'Administrateur',

  /*
  |--------------------------------------------------------------------------
  | Fichier marqueur d'installation
  |--------------------------------------------------------------------------
  */
  'installed_file' => storage_path('app/installed.json'),

  /*
  |--------------------------------------------------------------------------
  | Extensions PHP requises
  |--------------------------------------------------------------------------
  */
  'required_extensions' => [
    'pdo',
    'mbstring',
    'openssl',
    'tokenizer',
    'xml',
    'ctype',
    'json',
    'bcmath',
  ],

  /*
  |--------------------------------------------------------------------------
  | Dossiers devant être inscriptibles
  |--------------------------------------------------------------------------
  */
  'writable_paths' => [
    storage_path(),
    storage_path('app'),
    storage_path('framework'),
    storage_path('logs'),
    base_path('bootstrap/cache'),
  ],

];

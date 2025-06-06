<?php

use Examyou\RestAPI\Facades\ApiRoute;
use Illuminate\Support\Facades\Route;

$appType = app_type();
$routeArray = [
    'namespace' => 'App\Http\Controllers\Api\Common',
];

if ($appType == 'saas') {
    $routeArray['prefix'] = 'superadmin';
}

if ($appType == 'non-saas') {
    $routeArray['middleware'] = ['api.permission.check', 'api.auth.check', 'license-expire'];
}

Route::get('langs/download/{xid?}', ['as' => 'api.extra.langs.download', 'uses' => 'Api\Common\LangsController@downloadLang']);

// If appType is saas version
// Then we only define routes
// for send email settings not email settings
if ($appType == 'saas') {
    ApiRoute::group(['namespace' => 'App\Http\Controllers\Api\Common', 'prefix' => 'settings', 'middleware' => ['api.permission.check', 'api.auth.check']], function () {
        ApiRoute::post('email/send-mail-settings', ['as' => 'api.settings.send-mail-settings', 'uses' => 'SettingsController@sendMailSettings']);
        ApiRoute::get('email', ['as' => 'api.settings.email.index', 'uses' => 'SettingsController@getEmailSetting']);
    });

    // For SAAS version this endpoint always exists
    ApiRoute::group(['namespace' => 'App\Http\Controllers\Api\Common', 'middleware' => ['api.permission.check', 'api.auth.check']], function () {
        $options = [
            'as' => 'api'
        ];

        ApiRoute::resource('currencies', 'CurrencyController', $options);
    });
}

// Routes which are available
// according to app_type
ApiRoute::group($routeArray, function () {

    ApiRoute::post('translations/import', ['as' => 'api.translations.import', 'uses' => 'TranslationsController@import']);
    ApiRoute::post('translations/refetch', ['as' => 'api.translations.refetch', 'uses' => 'TranslationsController@refetchTranslations']);
    ApiRoute::resource('translations', 'TranslationsController', ['as' => 'api', 'only' => ['update']]);

    $options = [
        'as' => app_type() == 'non-saas' ? 'api' : 'api.superadmin'
    ];

    ApiRoute::resource('langs', 'LangsController', $options);
    ApiRoute::resource('currencies', 'CurrencyController', $options);

    ApiRoute::group(['prefix' => 'settings'], function () {
        ApiRoute::post('storage/update', ['as' => 'api.settings.storage.update', 'uses' => 'SettingsController@updateStorage']);
        ApiRoute::get('storage', ['as' => 'api.settings.storage.index', 'uses' => 'SettingsController@getStorage']);
        ApiRoute::post('email/send-test-mail', ['as' => 'api.settings.email.send-test-mail', 'uses' => 'SettingsController@sendTestMail']);
        ApiRoute::post('email/send-mail-settings', ['as' => 'api.settings.send-mail-settings', 'uses' => 'SettingsController@sendMailSettings']);
        ApiRoute::post('email/update', ['as' => 'api.settings.email.update', 'uses' => 'SettingsController@updateEmailSetting']);
        ApiRoute::get('email', ['as' => 'api.settings.email.index', 'uses' => 'SettingsController@getEmailSetting']);
    });

    // Database backup
    ApiRoute::get('download-backups/{id}', ['as' => 'api.settings.download-backups', 'uses' => 'DatabaseBackupController@downloadBackups']);

    ApiRoute::post('database-backups', ['as' => 'api.settings.database-backups', 'uses' => 'DatabaseBackupController@databaseBackups']);
    ApiRoute::post('generate-backups', ['as' => 'api.settings.generate-backups', 'uses' => 'DatabaseBackupController@generateBackups']);
    ApiRoute::post('delete-backup', ['as' => 'api.settings.delete-backup', 'uses' => 'DatabaseBackupController@deleteBackup']);
});

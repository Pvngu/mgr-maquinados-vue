<?php

use Examyou\RestAPI\Facades\ApiRoute;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Broadcasting\BroadcastController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ClientController;

// Admin Routes
ApiRoute::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    ApiRoute::get('all-langs', ['as' => 'api.extra.all-langs', 'uses' => 'AuthController@allEnabledLangs']);
    ApiRoute::get('lang-trans', ['as' => 'api.extra.lang-trans', 'uses' => 'AuthController@langTrans']);
    ApiRoute::post('change-theme-mode', ['as' => 'api.extra.change-theme-mode', 'uses' => 'AuthController@changeThemeMode']);
    ApiRoute::get('all-users', ['as' => 'api.extra.all-users', 'uses' => 'AuthController@allUsers']);
    ApiRoute::get('user/{userXId}/call-log', ['as' => 'api.extra.user-call-log', 'uses' => 'UsersController@UserCallLog']);
    ApiRoute::get('get-alphabetical-users', ['as' => 'api.extra.get-alphabetical-users', 'uses' => 'AuthController@getAlphabeticalUsers']);

    // Check visibility of module according to subscription plan
    ApiRoute::post('check-subscription-module-visibility', ['as' => 'api.extra.check-subscription-module-visibility', 'uses' => 'AuthController@checkSubscriptionModuleVisibility']);
    ApiRoute::post('visible-subscription-modules', ['as' => 'api.extra.visible-subscription-modules', 'uses' => 'AuthController@visibleSubscriptionModules']);

    ApiRoute::group(['middleware' => ['api.auth.check']], function () {
        ApiRoute::post('dashboard', ['as' => 'api.extra.dashboard', 'uses' => 'AuthController@dashboard']);
        ApiRoute::post('upload-file', ['as' => 'api.extra.upload-file', 'uses' => 'AuthController@uploadFile']);
        ApiRoute::post('profile', ['as' => 'api.extra.profile', 'uses' => 'AuthController@profile']);
        ApiRoute::post('user', ['as' => 'api.extra.user', 'uses' => 'AuthController@user']);
        ApiRoute::get('timezones', ['as' => 'api.extra.user', 'uses' => 'AuthController@getAllTimezones']);
        // ApiRoute::post('/broadcasting/authh', [BroadcastController::class, 'authenticate']);
    });
    Broadcast::routes([
        'middleware' => ['api.auth.check'],
        'prefix' => 'api/v1',
    ]);

    //webhook routes
    ApiRoute::post('signeasy/webhook', ['as' => 'api.signeasy.webhook', 'uses' => 'DocumentController@signeasyWebhook']);

    // Routes Accessable to thouse user who have permissions realted to route
    ApiRoute::group(['middleware' => ['api.permission.check', 'api.auth.check', 'license-expire']], function () {
        $options = [
            'as' => 'api'
        ];

        // Imports
        ApiRoute::post('users/import', ['as' => 'api.users.import', 'uses' => 'UsersController@import']);

        // Update Status
        ApiRoute::post('email-templates/update-status', ['as' => 'api.email-templates.update-status', 'uses' => 'EmailTemplateController@updateStatus']);
        ApiRoute::post('forms/update-status', ['as' => 'api.forms.update-status', 'uses' => 'FormController@updateStatus']);
        ApiRoute::post('form-field-names/update-status', ['as' => 'api.form-field-name.update-status', 'uses' => 'FormFieldNameController@updateStatus']);

        // All Forms
        ApiRoute::get('forms/all', ['as' => 'api.forms.all', 'uses' => 'FormController@allForms']);
        ApiRoute::resource('forms', 'FormController', $options);

        ApiRoute::get('email-templates/all', ['as' => 'api.email-templates.all', 'uses' => 'EmailTemplateController@allEmailTemplates']);
        ApiRoute::resource('email-templates', 'EmailTemplateController', $options);

        ApiRoute::get('form-field-names/all', ['as' => 'api.form-field-names.all', 'uses' => 'FormFieldNameController@allFormFieldNames']);
        ApiRoute::resource('form-field-names', 'FormFieldNameController', $options);

        ApiRoute::delete('deleteFile/{fileName}', ['as' => 'api.document.deleteFile', 'uses' => 'DocumentController@deleteFile']);
        ApiRoute::post('documents/generate-pdf', ['as' => 'api.documents.generate-pdf', 'uses' => 'DocumentController@generatePDF']);
        ApiRoute::post('documents/send-envelope', ['as' => 'api.documents.send-for-signature', 'uses' => 'DocumentController@sendEnvelope']);
        ApiRoute::get('documents/download-envelope/{id}/{status}/{certificate}', ['as' => 'api.documents.download-envelope', 'uses' => 'DocumentController@downloadEnvelope']);
        ApiRoute::post('documents/void-envelope', ['as' => 'api.documents.void-signature', 'uses' => 'DocumentController@voidEnvelope']);
        ApiRoute::post('documents/send-reminder', ['as' => 'api.documents.send-reminder', 'uses' => 'DocumentController@sendReminder']);
        ApiRoute::get('documents/fetch-envelopes/{individualXid}', ['as' => 'api.documents.', 'uses' => 'DocumentController@fetchEnvelopes']);
        ApiRoute::resource('documents', 'DocumentController', $options);

        // Create Menu Update
        ApiRoute::post('companies/update-create-menu', ['as' => 'api.companies.update-create-menu', 'uses' => 'CompanyController@updateCreateMenu']);

        ApiRoute::get('users/notifications', ['as' => 'api.users.notifications', 'uses' => 'UsersController@getUserNotifications']);
        ApiRoute::get('users/export', ['as' => 'api.users.export', 'uses' => 'UsersController@export']);
        ApiRoute::resource('users', 'UsersController', $options);
        
        ApiRoute::resource('companies', 'CompanyController', ['as' => 'api', 'only' => ['update']]);
        ApiRoute::resource('permissions', 'PermissionController', ['as' => 'api', 'only' => ['index']]);
        ApiRoute::resource('roles', 'RolesController', $options);

        ApiRoute::get('select-options/{group}', ['as' => 'api.select-options.group', 'uses' => 'SelectOptionController@getGroupOptions']);
        
        ApiRoute::resource('categories', CategoryController::class, $options);
        ApiRoute::resource('suppliers', SupplierController::class, $options);
        ApiRoute::resource('products', ProductController::class, $options);
        ApiRoute::resource('clients', ClientController::class, $options);
    });
});

<?php

namespace App\Providers;

use App\Models\Form;
use App\Models\Role;
use App\Models\User;
use App\Models\Currency;
use App\Models\Document;
use App\Models\Settings;
use App\Models\EmailTemplate;
use App\Models\FormFieldName;
use App\Observers\FormObserver;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use App\Observers\SettingObserver;
use App\Observers\CurrencyObserver;
use App\Observers\EmailTemplateObserver;
use App\Observers\FormFieldNameObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Don't run observer when
        // we run command using
        if (!app()->runningInConsole()) {
            User::observe(UserObserver::class);
            Settings::observe(SettingObserver::class);
            Currency::observe(CurrencyObserver::class);
            Role::observe(RoleObserver::class);
            EmailTemplate::observe(EmailTemplateObserver::class);
            Form::observe(FormObserver::class);
            FormFieldName::observe(FormFieldNameObserver::class);
        }
    }
}

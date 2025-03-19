<?php

namespace App\Classes;

use Carbon\Carbon;
use App\Models\Lang;
use App\Models\User;
use App\Models\Currency;
use App\Models\Settings;
use App\Models\StaffMember;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Examyou\RestAPI\Exceptions\ApiException;


class Common
{
    public static function getFolderPath($type = null)
    {
        $paths = [
            'companyLogoPath' => 'companies',
            'userImagePath' => 'users',
            'langImagePath' => 'langs',
            'expenseBillPath' => 'expenses',
            'productLogoPath' => 'product',
            'audioFilesPath' => 'audio',
            'websiteImagePath' => 'website',
            'offlineRequestDocumentPath' => 'offline-requests',
            'docFilesPath' => 'documents',
        ];

        return ($type == null) ? $paths : $paths[$type];
    }

    public static function uploadFile($request)
    {
        $folder = $request->folder;
        $folderString = "";

        if ($folder == "user") {
            $folderString = "userImagePath";
        } else if ($folder == "company") {
            $folderString = "companyLogoPath";
        } else if ($folder == "langs") {
            $folderString = "langImagePath";
        } else if ($folder == "expenses") {
            $folderString = "expenseBillPath";
        } else if ($folder == "product") {
            $folderString = "productLogoPath";
        } else if ($folder == "website") {
            $folderString = "websiteImagePath";
        } else if ($folder == "offline-requests") {
            $folderString = "offlineRequestDocumentPath";
        } else if ($folder == "documents") {
            $folderString = "docFilesPath";
        }

        $folderPath = self::getFolderPath($folderString);

        if ($request->hasFile('image') || $request->hasFile('file')) {
            $largeLogo  = $request->hasFile('image') ? $request->file('image') : $request->file('file');

            $fileName   = $folder . '_' . strtolower(Str::random(20)) . '.' . $largeLogo->getClientOriginalExtension();
            $largeLogo->storePubliclyAs($folderPath, $fileName);
        }

        return [
            'file' => $fileName,
            'file_url' => self::getFileUrl($folderPath, $fileName),
        ];
    }

    public static function checkFileExists($folderString, $fileName)
    {
        $folderPath = self::getFolderPath($folderString);

        $fullPath = $folderPath . '/' . $fileName;

        return Storage::exists($fullPath);
    }

    public static function getFileUrl($folderPath, $fileName)
    {
        if (config('filesystems.default') == 's3') {
            $path = $folderPath . '/' . $fileName;

            return Storage::url($path);
        } else {
            $path =  'uploads/' . $folderPath . '/' . $fileName;

            return asset($path);
        }
    }

    public static function moduleInformations()
    {
        $allModules = Module::all();
        $allEnabledModules = Module::allEnabled();
        $installedModules = [];
        $enabledModules = [];

        foreach ($allModules as $key => $allModule) {
            $modulePath = $allModule->getPath();
            $versionFileName = 'version.txt';
            $version = File::get($modulePath . '/' . $versionFileName);

            $installedModules[] = [
                'verified_name' => $key,
                'current_version' => preg_replace("/\r|\n/", "", $version)
            ];
        }

        foreach ($allEnabledModules as $allEnabledModuleKey => $allEnabledModule) {
            $enabledModules[] = $allEnabledModuleKey;
        }

        return [
            'installed_modules' => $installedModules,
            'enabled_modules' => $enabledModules,
        ];
    }

    public static function getIdFromHash($hash)
    {
        if ($hash != "") {
            $convertedId = Hashids::decode($hash);
            $id = $convertedId[0];

            return $id;
        }

        return $hash;
    }

    public static function getHashFromId($id)
    {
        $id = Hashids::encode($id);

        return $id;
    }

    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public static function calculateTotalUsers($companyId, $update = false)
    {
        $totalUsers =  StaffMember::withoutGlobalScope(CompanyScope::class)
            ->where('company_id', $companyId)
            ->count('id');

        if ($update) {
            DB::table('companies')
                ->where('id', $companyId)
                ->update([
                    'total_users' => $totalUsers
                ]);
        }


        return $totalUsers;
    }

    public static function addWebsiteImageUrl($settingData, $keyName)
    {
        if ($settingData && array_key_exists($keyName, $settingData)) {
            if ($settingData[$keyName] != '') {
                $imagePath = self::getFolderPath('websiteImagePath');

                $settingData[$keyName . '_url'] = Common::getFileUrl($imagePath, $settingData[$keyName]);
            } else {
                $settingData[$keyName] = null;
                $settingData[$keyName . '_url'] = asset('images/website.png');
            }
        }

        return $settingData;
    }

    public static function convertToCollection($data)
    {
        $data = collect($data)->map(function ($item) {
            return (object) $item;
        });

        return $data;
    }

    public static function addCurrencies($company)
    {
        $newCurrency = new Currency();
        $newCurrency->company_id = $company->id;
        $newCurrency->name = 'Dollar';
        $newCurrency->code = 'USD';
        $newCurrency->symbol = '$';
        $newCurrency->position = 'front';
        $newCurrency->is_deletable = false;
        $newCurrency->save();

        $enLang = Lang::where('key', 'en')->first();

        $company->currency_id = $newCurrency->id;
        $company->lang_id = $enLang->id;
        $company->save();

        return $company;
    }

    public static function checkSubscriptionModuleVisibility($itemType)
    {
        $visible = true;

        return $visible;
    }

    public static function allVisibleSubscriptionModules()
    {
        $visibleSubscriptionModules = [];

        if (self::checkSubscriptionModuleVisibility('user')) {
            $visibleSubscriptionModules[] = 'user';
        }

        return $visibleSubscriptionModules;
    }

    public static function insertInitSettings($company)
    {       
        $company->is_global = 0;
        $local = new Settings();
        $local->company_id = $company->id;
        $local->setting_type = 'storage';
        $local->name = 'Local';
        $local->name_key = 'local';
        $local->status = true;
        $local->is_global = $company->is_global;
        $local->save();

        $aws = new Settings();
        $aws->company_id = $company->id;
        $aws->setting_type = 'storage';
        $aws->name = 'AWS';
        $aws->name_key = 'aws';
        $aws->credentials = [
            'driver' => 's3',
            'key' => '',
            'secret' => '',
            'region' => '',
            'bucket' => '',

        ];
        $aws->is_global = $company->is_global;
        $aws->save();

        $smtp = new Settings();
        $smtp->company_id = $company->id;
        $smtp->setting_type = 'email';
        $smtp->name = 'SMTP';
        $smtp->name_key = 'smtp';
        $smtp->credentials = [
            'from_name' => '',
            'from_email' => '',
            'host' => '',
            'port' => '',
            'encryption' => '',
            'username' => '',
            'password' => '',

        ];
        $smtp->is_global = $company->is_global;
        $smtp->save();

        $sendMailSettings = new Settings();
        $sendMailSettings->company_id = $company->id;
        $sendMailSettings->setting_type = 'send_mail_settings';
        $sendMailSettings->name = 'Send mail to company';
        $sendMailSettings->name_key = 'company';
        $sendMailSettings->credentials = [];
        $sendMailSettings->save();

        // Create Menu Setting
        $setting = new Settings();
        $setting->company_id = $company->id;
        $setting->setting_type = 'shortcut_menus';
        $setting->name = 'Add Menu';
        $setting->name_key = 'shortcut_menus';
        $setting->credentials = [
            'user',
            'currency',
            'language',
            'role',
        ];
        $setting->status = 1;
        $setting->save();

        // Seed for quotations
        NotificationSeed::seedAllModulesNotifications($company->id);
    }
}

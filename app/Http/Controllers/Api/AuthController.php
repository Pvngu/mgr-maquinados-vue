<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Form;
use App\Models\Lang;
use App\Models\User;
use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use App\Classes\Common;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Salesman;
use App\Models\Settings;
use Carbon\CarbonPeriod;
use App\Models\StaffMember;
use Illuminate\Http\Request;
use Examyou\RestAPI\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Auth\LoginRequest;
use Examyou\RestAPI\Exceptions\ApiException;
use App\Http\Requests\Api\Auth\ProfileRequest;
use App\Http\Requests\Api\Auth\UploadFileRequest;
use App\Http\Requests\Api\Auth\RefreshTokenRequest;

class AuthController extends ApiBaseController
{
    public function companySetting()
    {
        $settings = Company::first();

        return ApiResponse::make('Success', [
            'global_setting' => $settings,
        ]);
    }

    public function emailSettingVerified()
    {
        $emailSettingVerified = Settings::where('setting_type', 'email')
            ->where('status', 1)
            ->where('verified', 1)
            ->count();

        return $emailSettingVerified > 0 ? 1 : 0;
    }

    public function app()
    {
        $company = company(true);
        $addMenuSetting = $company ? Settings::where('setting_type', 'shortcut_menus')->first() : null;
        $totalCurrencies = Currency::count();

        return ApiResponse::make('Success', [
            'app' => $company,
            'shortcut_menus' => $addMenuSetting,
            'email_setting_verified' => $this->emailSettingVerified(),
            'total_currencies' => $totalCurrencies,
        ]);
    }

    public function checkSubscriptionModuleVisibility()
    {
        $request = request();
        $itemType = $request->item_type;

        $visible = Common::checkSubscriptionModuleVisibility($itemType);

        return ApiResponse::make('Success', [
            'visible' => $visible,
        ]);
    }

    public function visibleSubscriptionModules()
    {
        $visibleSubscriptionModules = Common::allVisibleSubscriptionModules();

        return ApiResponse::make('Success', $visibleSubscriptionModules);
    }

    public function allEnabledLangs()
    {
        $allLangs = Lang::select('id', 'name', 'key', 'image')->where('enabled', 1)->get();

        return ApiResponse::make('Success', [
            'langs' => $allLangs
        ]);
    }

    public function allForms()
    {
        $allForms = Form::select('id', 'name', 'form_fields')->get();

        return ApiResponse::make('Success', [
            'forms' => $allForms
        ]);
    }

    public function allUsers()
    {
        $request = request();

        if ($request->has('log_type') && $request->log_type == 'salesman_bookings') {
            $users = Salesman::select('id', 'name', 'profile_image')->get();
        }
        elseif ($request->has('log_type') && $request->log_type == 'staff_members') {
            $users = User::join('roles', 'users.role_id', '=', 'roles.id')
                        ->select('users.id', 'users.name', 'users.profile_image', 'users.status', 'roles.display_name as role')
                        ->withCount('assignedSales')
                        ->get()
                        ->groupBy('role');
        } else {
            $users = StaffMember::select('id', 'name', 'profile_image')->get();
        }

        return ApiResponse::make('Success', [
            'users' => $users
        ]);
    }

    protected function getAlphabeticalUsers()
    {
        $user = user();

        $users = cache()->remember('alphabetical_users', 60 * 60, function () use ($user) {
            return StaffMember::join('roles', 'roles.id', '=', 'role_id')
                ->where('users.id', '!=', $user->id)
                ->select(
                    'users.id',
                    'users.name',
                    'users.profile_image',
                    DB::raw('roles.name as role')
                )
                ->orderBy('users.name', 'asc')
                ->get()
                ->groupBy(function ($item) {
                    return strtoupper(substr($item->name, 0, 1));
                });
        });

        return ApiResponse::make('Success', [
            'users' => $users
        ]);
    }

    public function login(LoginRequest $request)
    {
        // Removing all sessions before login
        session()->flush();

        $phone = "";
        $email = "";

        $credentials = [
            'password' =>  $request->password
        ];

        if (is_numeric($request->get('email'))) {
            $credentials['phone'] = $request->email;
            $phone = $request->email;
        } else {
            $credentials['email'] = $request->email;
            $email = $request->email;
        }

        // For checking user
        $user = User::select('*');
        if ($email != '') {
            $user = $user->where('email', $email);
        } else if ($phone != '') {
            $user = $user->where('phone', $phone);
        }
        $user = $user->first();

        // Adding user type according to email/phone
        if ($user) {
            $credentials['user_type'] = 'staff_members';
            $credentials['is_superadmin'] = 0;
            $userCompany = Company::where('id', $user->company_id)->first();
        }

        $message = 'Loggged in successfull';
        $status = 'success';

        if (!$token = auth('api')->attempt($credentials)) {
            $status = 'fail';
            $message = 'These credentials do not match our records.';
        } else if ($userCompany->status === 'pending') {
            $status = 'fail';
            $message = 'Your company not verified.';
        } else if ($userCompany->status === 'inactive') {
            $status = 'fail';
            $message = 'Company account deactivated.';
        } else if (auth('api')->user()->status === 'waiting') {
            $status = 'fail';
            $message = 'User not verified.';
        } else if (auth('api')->user()->status === 'disabled') {
            $status = 'fail';
            $message = 'Account deactivated.';
        }

        if ($status == 'success') {
            $company = company();
            $response = $this->respondWithToken($token);
            $addMenuSetting = Settings::where('setting_type', 'shortcut_menus')->first();
            $response['app'] = $company;
            $response['shortcut_menus'] = $addMenuSetting;
            $response['email_setting_verified'] = $this->emailSettingVerified();
            $response['visible_subscription_modules'] = Common::allVisibleSubscriptionModules();
        }
        $response['status'] = $status;
        $response['message'] = $message;

        return ApiResponse::make($message, $response);
    }

    protected function respondWithToken($token)
    {
        $user = user();

        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Carbon::now()->addMinutes(480),
            'user' => $user
        ];
    }

    public function logout()
    {
        $request = request();

        if (auth('api')->user() && $request->bearerToken() != '') {
            auth('api')->logout();
        }

        session()->flush();

        return ApiResponse::make(__('Session closed successfully'));
    }

    public function user()
    {
        $user = auth('api')->user();
        $user = $user->load('role', 'role.perms');

        session(['user' => $user]);

        return ApiResponse::make('Data successfull', [
            'user' => $user
        ]);
    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        $newToken = auth('api')->refresh();

        $response = $this->respondWithToken($newToken);

        return ApiResponse::make('Token fetched successfully', $response);
    }

    public function uploadFile(UploadFileRequest $request)
    {
        $result = Common::uploadFile($request);

        return ApiResponse::make('File Uploaded', $result);
    }

    public function profile(ProfileRequest $request)
    {
        $user = auth('api')->user();

        // In Demo Mode
        if (env('APP_ENV') == 'production') {
            $request = request();

            if ($request->email == 'admin@example.com' && $request->has('password') && $request->password != '') {
                throw new ApiException('Not Allowed In Demo Mode');
            }
        }

        $user->name = $request->name;
        if ($request->has('profile_image')) {
            $user->profile_image = $request->profile_image;
        }
        if ($request->password != '') {
            $user->password = $request->password;
        }
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        return ApiResponse::make('Profile updated successfull', [
            'user' => $user->load('role', 'role.perms')
        ]);
    }

    public function langTrans()
    {
        $langs = Lang::with('translations')->get();

        return ApiResponse::make('Langs fetched', [
            'data' => $langs
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = user();
        $query = [];
        
        if ($request->has('dates') && $request->dates != null && count($request->dates) > 0) {
            $dates = $request->dates;
            $startDate = $dates[0];
            $endDate = $dates[1];
            
            $query['startDate'] = $startDate;
            $query['endDate'] = $endDate;
        } else {
            $query['startDate'] = date('Y-m-d', strtotime('-30 days'));
            $query['endDate'] = date('Y-m-d');
        }
        
        // Total Orders
        $totalOrders = Order::when(isset($query['startDate']), function ($q) use ($query) {
                return $q->whereDate('order_date', '>=', $query['startDate'])
                    ->whereDate('order_date', '<=', $query['endDate']);
            })->count();
            
        // Total Revenue
        $totalRevenue = Order::when(isset($query['startDate']), function ($q) use ($query) {
                return $q->whereDate('order_date', '>=', $query['startDate'])
                    ->whereDate('order_date', '<=', $query['endDate']);
            })->sum('total_amount');
            
        // Total Clients
        $totalClients = Client::count();
        
        // Total Products
        $totalProducts = Product::count();
        
        // Products with low stock
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)->count();
        
        // Recent Orders
        $recentOrders = Order::with('client')
            ->orderBy('order_date', 'desc')
            ->limit(5)
            ->get();
            
        // Sales by day for chart
        $salesByDay = [];
        if (isset($query['startDate'])) {
            $period = CarbonPeriod::create($query['startDate'], $query['endDate']);
            
            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                $sales = Order::whereDate('order_date', $dateStr)->sum('total_amount');
                $salesByDay[] = [
                    'date' => $dateStr,
                    'amount' => $sales
                ];
            }
        }

        return ApiResponse::make('Data fetched', [
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalClients' => $totalClients,
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
            'recentOrders' => $recentOrders,
            'salesByDay' => $salesByDay,
        ]);
    }

    public function changeThemeMode(Request $request)
    {
        $mode = $request->has('theme_mode') ? $request->theme_mode : 'light';

        session(['theme_mode' => $mode]);

        if ($mode == 'dark') {
            $company = company();
            $company->left_sidebar_theme = 'light';
            $company->save();

            $updatedCompany = company(true);
        }

        return ApiResponse::make('Success', [
            'status' => "success",
            'themeMode' => $mode,
            'themeModee' => session()->all(),
        ]);
    }

    public function getAllTimezones()
    {
        $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

        return ApiResponse::make('Success', [
            'timezones' => $timezones,
            'date_formates' => [
                'd-m-Y' => 'DD-MM-YYYY',
                'm-d-Y' => 'MM-DD-YYYY',
                'Y-m-d' => 'YYYY-MM-DD',
                'd.m.Y' => 'DD.MM.YYYY',
                'm.d.Y' => 'MM.DD.YYYY',
                'Y.m.d' => 'YYYY.MM.DD',
                'd/m/Y' => 'DD/MM/YYYY',
                'm/d/Y' => 'MM/DD/YYYY',
                'Y/m/d' => 'YYYY/MM/DD',
                'd/M/Y' => 'DD/MMM/YYYY',
                'd.M.Y' => 'DD.MMM.YYYY',
                'd-M-Y' => 'DD-MMM-YYYY',
                'd M Y' => 'DD MMM YYYY',
                'd F, Y' => 'DD MMMM, YYYY',
                'D/M/Y' => 'ddd/MMM/YYYY',
                'D.M.Y' => 'ddd.MMM.YYYY',
                'D-M-Y' => 'ddd-MMM-YYYY',
                'D M Y' => 'ddd MMM YYYY',
                'd D M Y' => 'DD ddd MMM YYYY',
                'D d M Y' => 'ddd DD MMM YYYY',
                'dS M Y' => 'Do MMM YYYY',
            ],
            'time_formates' => [
                "hh:mm A" => '12 Hours hh:mm A',
                'hh:mm a' => '12 Hours hh:mm a',
                'hh:mm:ss A' => '12 Hours hh:mm:ss A',
                'hh:mm:ss a' => '12 Hours hh:mm:ss a',
                'HH:mm ' => '24 Hours HH:mm:ss',
                'HH:mm:ss' => '24 Hours hh:mm:ss',
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Classes\Common;
use App\Traits\UserTraits;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\User\IndexRequest;
use App\Http\Requests\Api\User\StoreRequest;
use Examyou\RestAPI\Exceptions\ApiException;
use App\Http\Requests\Api\User\DeleteRequest;
use App\Http\Requests\Api\User\UpdateRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UsersController extends ApiBaseController
{
    use UserTraits;

    protected $model = User::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function __construct()
    {
        parent::__construct();

        $this->userType = "staff_members";
    }

    public function UserCallLog($userXId) {
        $userId = $this->getIdFromHash($userXId);

        $now = Carbon::now()->setTimezone('America/Los_Angeles');
        $startOfWeek = $now->startOfWeek();
        $startOfMonth = $now->startOfMonth();
        $endOfMonth = $now->endOfMonth();
        $startOfDay = $now->startOfDay();
    }

    public function getUserNotifications() {
        $user = user();

        $notifications = $user->notifications()
            ->with([
                'document',
                'sender' => function ($query) {
                    $query->select('id', 'name', 'profile_image');
                },
                'individual' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                },
                'individual.sale',
            ])
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json([
            'data' => $notifications,
        ], 200);
    }

    public function export(): BinaryFileResponse
    {
        $request = request();
        $user = user();
        
        $columns = (array) $request->columns;
        $format = $request->format;
        $startDate = "";
        $endDate = "";
        $selectedRowKeys = (array) $request->selectedRowKeys;
        $roleIds = [];

        if($request->has('roleXIds') && $request->roleXIds != "") {
            $roleIds = $this->getIdArrayFromHash($request->roleXIds);
        }

        if ($request->has('dates') && $request->dates != "") {
            $dates = $request->dates;
            $startDate = $dates[0];
            $endDate = $dates[1];
        }

        if(!$user->ability('admin', 'reports_view')) {
            throw new ApiException("Not Allowed");
        }

        return Excel::download(new UsersExport($columns, $startDate, $endDate, $selectedRowKeys, $roleIds), "staff_members.$format");
    }
}

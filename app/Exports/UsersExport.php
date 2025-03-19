<?php

namespace App\Exports;

use App\Models\StaffMember;
use \Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    protected $query;
    protected $columns;
    protected $startDate;
    protected $endDate;
    
    public function __construct(array $columns, $startDate, $endDate, array $selectedRowKeys, array $roleIds) {
        $this->columns = $columns;
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        $this->query = StaffMember::query()
            ->with(['role']);

        if(count($roleIds) > 0) {
            $this->query = $this->query->whereIn('role_id', $roleIds);
        }

        if(count($selectedRowKeys) > 0) {
            $this->query = $this->query->whereIn('id', $selectedRowKeys);
        }
    }

    public function view(): View
    {
        return view('exports.users', [
            'users' => $this->query->get(),
            'columns' => $this->columns,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
}

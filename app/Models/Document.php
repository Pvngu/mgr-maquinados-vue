<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Scopes\CompanyScope;
use App\Models\BaseModel;

class Document extends BaseModel
{
    protected $table = 'documents';
    
    protected $default = ["xid", "title"];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid', 'x_created_by_id', 'file_url'];

    protected $hashableGetterFunctions = [
        'getXCreatedByIdAttribute' => 'created_by_id',
    ];

    protected $casts = [
        'user_id' => Hash::class . ':hash',
        'created_by_id' => Hash::class . ':hash',
    ];

    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope(new CompanyScope);
    }

    public function getFileUrlAttribute()
    {
        $docFilesPath = Common::getFolderPath('docFilesPath');

        return $this->file == null ? null : Common::getFileUrl($docFilesPath, $this->file);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id')->withoutGlobalScopes();
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by_id', 'id')->withoutGlobalScopes();
    }

    public function signers()
    {
        return $this->hasMany(Signer::class);
    }
}

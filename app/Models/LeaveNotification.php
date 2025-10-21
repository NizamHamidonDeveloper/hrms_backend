<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveNotification extends BaseModel
{
    protected $table = 'leave_notification';

    protected $fillable = [
        'leavetype_id',
        'onapply_check',
        'onapproval_check',
        'onrejection_check',
        'oncancelation_check',
        'channelemail_check',
        'channelapp_check',
        'additional_recepients',
        'notification_template',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'additional_recepients' => 'array',
    ];

    public function setAdditionalRecepientsAttribute($value)
    {
        $this->setJsonField('additional_recepients', $value);
    }

    public function getAdditionalRecepientsAttribute($value)
    {
        return $this->getJsonField($value);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leavetype_id');
    }
}

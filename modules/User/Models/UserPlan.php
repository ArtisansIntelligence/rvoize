<?php


namespace Modules\User\Models;


use App\BaseModel;

class UserPlan extends BaseModel
{
    protected $table  = 'user_plan';

    protected $casts = [
        'start_date'=>'datetime',
        'end_date'=>'datetime',
        'plan_data'=>'array'
    ];

    public function getIsValidAttribute(){
        if(!$this->end_date) return true;

        return $this->end_date->timestamp > time();
    }

    public function plan(){
        return $this->belongsTo(Plan::class,'plan_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'create_user');
    }

    public function getUsedAttribute(){
        switch ($this->user->role->code ?? ''){
            case "employer":
                    if(!$this->user->company) return 0;
                    return $this->user->company->jobsPublish()->count('id');
                break;
            case "candidate";
                return $this->user->gigsPublish()->count('id');
            break;
        }
    }
}

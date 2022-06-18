<?php

namespace App\Models;
use App\Helpers\HashingSlug;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HashingSlug;
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;


    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the users for the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companyUsers()
    {
        $user = \Auth::user();

        return User::where('company_id', $user->company_id)
            ->where('department_id', $this->id)
            ->withNotRole('candidate');
    }
    public function department_manager()
    {
        $manager = DepartmentManager::where('department_id',$this->id)->first();
        if (!empty($manager->employee_id))
        {
            $employee = Employee::where('id',$manager->employee_id)->first();
            if ($employee)
            {
                return $employee->full_name;
            }
            else
            {
                return 'Çalışan Bulunmadı Yazılım Ekibiyle Görüşün';
            }

        }
        else
        {
            return 'Yönetici Bulunmadı';
        }
    }

}

<?php

namespace App;

use App\Helpers\HashingSlug;
use App\Helpers\ImageHelper;
use App\Models\CrmNotification;
use App\Models\CrmNotificationService;
use App\Models\Department;
use App\Models\Leave;
use App\Models\ModulePacket;
use App\Models\PacketCompany;
use App\Notifications\PasswordReset;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;


/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use HashingSlug;

    ///protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $dates = [
        'created_at'
    ];

    protected $fillable = [
        'name', 'email', 'password','company_id', 'is_demo', 'employee_id', 'sgk_company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the company that owns the user.
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the department that owns the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo('App\Models\Department')->withDefault(function() {
            return new Department();
        });
    }




    /**
     * Get picture url of user
     *
     * @return null|string
     */
    public function getPictureLinkAttribute()
    {
        if (! $this->picture) {
            $imageHelper = new ImageHelper();
            $imageHelper->createUserImage($this);
        }

        return $this->picture ? asset($this->picture) : asset('assets/images/avatar_2x.png');
    }

    public function EmployeeLeave()
    {


        return Leave::where('user_id',Auth::user()->id)->where('status','1')->count();

    }

    /**
     * Module permit for company
     *
     * @param string $module
     *
     * @return bool
     */
    public function modulePermit($moduleId)
    {

        $user = $this;
        $companyPacketIds = $user->company->packets;
        //dd($companyPacketIds);
        foreach($companyPacketIds as $companyPacketId) {
            $has = ModulePacket::where('packet_id', $companyPacketId->packet_id)->where('module_id', $moduleId)->first();
            if ($has) {
                return true;
            }
        }
        return false;

    }

    public function packetModule($id)
    {

        $has = PacketCompany::where('packet_id',$id)->where('company_id',\Auth::user()->company_id)->where('status',1)->first();

        if ($has)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    /**
     * Count of unread notifications
     *
     * @return mixed
     */
    public function getNotificationUnreadCountAttribute()
    {
        return $this->unreadNotifications->count();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function crmnotifications($value)
    {
        $users =  CrmNotificationService::where('user_id',Auth::user()->id)->get()->pluck('crm_notification_id')->toArray();
        return CrmNotification::whereIn('id',$users)->where('type',$value)->limit(10)->orderBy('id','DESC')->get();
    }
    public function CrmNotificationUnreadCount()
    {
        return  CrmNotificationService::where('user_id',Auth::user()->id)->where('read',0)->count();

    }


}

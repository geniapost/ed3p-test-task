<?php

namespace App\Models;

use app\Models\User\Address;
use app\Models\User\Phone;
use app\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $address_id
 * @property int|null $phone_id
 * @property int|null $email_id
 * @property int $status
 * @property int $delivery_type
 * @property \Illuminate\Support\Carbon|null $delivery_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Address|null $address
 * @property-read bool $can_be_canceled
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @property-read Phone|null $phone
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePhoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order withoutTrashed()
 * @property-read bool $can_be_updated
 * @property-read float $total_price
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_CREATED = 0;
    const STATUS_PAID = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DELIVERED = 3;

    const TYPE_DELIVERY = 0;
    const TYPE_SELF = 1;

    public static array $statuses = [
        self::STATUS_CREATED,
        self::STATUS_PAID,
        self::STATUS_CANCELLED,
        self::STATUS_DELIVERED,
    ];

    public static array $types = [
        self::TYPE_DELIVERY,
        self::TYPE_SELF,
    ];

    public static array $statusLabels = [
        self::STATUS_CREATED => 'Створено',
        self::STATUS_PAID => 'Оплачено',
        self::STATUS_CANCELLED => 'Скасовано',
        self::STATUS_DELIVERED => 'Доставлено',
    ];

    public static array $typesLabels = [
        self::TYPE_DELIVERY => 'Доставка',
        self::TYPE_SELF => 'Самовивіз',
    ];

    protected $fillable = [
        'status',
        'delivery_type',
        'delivery_time',
    ];

    protected $casts = [
        'delivery_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function phone(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Phone::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Item::class)->withPivot('quantity');
    }

    public function getCanBeCanceledAttribute(): bool
    {
        return Carbon::now()->diffInMinutes($this->created_at) < 1;
    }

    public function getCanBeUpdatedAttribute(): bool
    {
        return Carbon::now()->diffInMinutes($this->created_at) < 2;
    }

    public function getTotalPriceAttribute(): float
    {
        $totalPrice = 0;

        foreach ($this->items as $item) {
            $totalPrice += $item->price * $item->pivot->quantity;
        }

        return $totalPrice;
    }
}

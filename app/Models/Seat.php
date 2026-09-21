<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $event_id
 * @property string $section
 * @property string $row
 * @property int $number
 * @property string $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereRow($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seat whereStatus($value)
 *
 * @mixin Eloquent
 */
#[Fillable(['section', 'row', 'number', 'status'])]
class Seat extends Model
{
    use HasFactory;

    protected $table = 'seats';

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}

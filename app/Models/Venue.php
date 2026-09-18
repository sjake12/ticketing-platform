<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $city
 * @property string|null $layout
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Venue whereName($value)
 *
 * @mixin Eloquent
 */
#[Fillable(['name', 'city', 'layout'])]
class Venue extends Model
{
    use HasFactory;

    protected $casts = [
        'layout' => 'array',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}

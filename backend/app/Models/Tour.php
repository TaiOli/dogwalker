<?php

namespace App\Models;

use App\Enums\TourStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Tour extends Model implements Auditable
{
    use HasFactory;
    use AuditableTrait;

    protected $table = 'passeios';

    protected $auditExclude = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'dog_id',
        'tutor_id',
        'passeador_id',
        'data',
        'hora',
        'duracao',
        'local',
        'valor',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date',
            'status' => TourStatus::class
        ];
    }

    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function walker()
    {
        return $this->belongsTo(User::class, 'passeador_id');
    }
}
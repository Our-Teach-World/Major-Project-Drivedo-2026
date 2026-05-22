<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockchainBlock extends Model
{
    protected $fillable = [
        'block_index', 'certificate_id', 'certificate_uid',
        'previous_hash', 'data_hash', 'block_hash',
        'block_data', 'mined_at',
    ];

    protected $casts = [
        'block_data' => 'array',
        'mined_at' => 'datetime',
    ];

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }

    /**
     * Verify this block's integrity by recomputing hash
     */
    public function isIntact(): bool
    {
        $recomputed = hash('sha256',
            $this->block_index .
            $this->previous_hash .
            $this->data_hash .
            $this->mined_at->timestamp
        );
        return $recomputed === $this->block_hash;
    }
}

<?php

namespace App\Models\Rentman;

use App\Models\Rentman\Crew; // Pas de namespace aan indien nodig
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookCall extends Model
{
    /**
     * De tabel die bij dit model hoort.
     */
    protected $table = 'rm_webhook_calls';


    /**
     * De attributen die massaal toegewezen kunnen worden.
     * Gezien je tabelstructuur is dit een veilige lijst.
     */
    protected $fillable = [
        'payload',
        'headers',
        'ip',
        'account',
        'user',
        'eventType',
        'itemType',
        'items',
        'eventDate',
    ];

    /**
     * Definieer de relatie naar Crew.
     * We koppelen de 'user' kolom van deze tabel aan de 'id' van de Crew.
     */
    public function crew(): BelongsTo
    {
        // return $this->belongsTo(Crew::class, 'user', 'id');
        // We koppelen 'user' aan 'rm_id'
        return $this->belongsTo(Crew::class, 'user', 'rm_id')
                ->where('account', $this->account);
    }

    /**
     * De casts die moeten worden toegepast.
     */
    protected function casts(): array
    {
        return [
            'payload'   => 'object',    // Handig: zet de JSON payload direct om naar een stdClass object
            'headers'   => 'object',    // Idem voor headers
            'items'     => 'object',    // Idem voor items
            'eventDate' => 'datetime',
        ];
    }
}

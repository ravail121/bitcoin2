<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    protected $table = 'ticket_comments';

    protected $guarded =['id'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class)->withDefault();
    }
}

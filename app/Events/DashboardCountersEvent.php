<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class DashboardCountersEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

  public $channel;

 /**
     * Create a new event instance.
     *
     * @return void
     */
  public function __construct($channel)
  {
      $this->channel = $channel;
     
  }
  /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
  public function broadcastOn()
  {
      return new Channel($this->channel['channel']);
  }

  public function broadcastAs()
  {
      return $this->channel['event'];
  }
}
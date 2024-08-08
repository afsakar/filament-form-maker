<?php

namespace Afsakar\FormMaker\Notifications;

use Closure;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class MessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 0;

    public $maxExceptions = 2;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Message $message)
    {
        //
    }

    public function setToMailUsing(callable $toMailUsing)
    {
        $this->message->setToMailUsing($toMailUsing);

        return $this;
    }

    public function setToArrayUsing(callable $toArrayUsing)
    {
        $this->message->setToArrayUsing($toArrayUsing);

        return $this;
    }

    public function setVia(array $via)
    {
        $this->message->setVia($via);

        return $this;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(mixed $notifiable)
    {
        return $this->message->via($notifiable);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(mixed $notifiable)
    {
        return $this->message->toMail($notifiable);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(mixed $notifiable)
    {
        return $this->message->toArray($notifiable);
    }

    public static function make(?MailMessage $mailMessage = null, array | FilamentNotification | null $data = null): self
    {
        if (is_null($data)) {
            $data = [];
        } elseif ($data instanceof FilamentNotification) {
            $data = $data->getDatabaseMessage();
        }

        $message = new Message($mailMessage, $data);

        return new self($message);
    }
}

class Message
{
    public ?Closure $toMailUsing = null;

    public ?Closure $toArrayUsing = null;

    public ?array $via = null;

    public function __construct(public ?MailMessage $mailMessage = null, public array $data = [])
    {
        //
    }

    public function setToMailUsing(callable $toMailUsing)
    {
        $this->toMailUsing = $toMailUsing;

        return $this;
    }

    public function setToArrayUsing(callable $toArrayUsing)
    {
        $this->toArrayUsing = $toArrayUsing;

        return $this;
    }

    public function setVia(array $via)
    {
        $this->via = $via;

        return $this;
    }

    public function via($notifiable)
    {
        if (filled($this->via)) {
            return $this->via;
        }

        $via = [];

        if (filled($this->mailMessage)) {
            $via[] = 'mail';
        }

        if (filled($this->data)) {
            $via[] = 'database';
        }

        return $via;
    }

    public function toMail($notifiable)
    {
        if ($this->toMailUsing instanceof \Closure) {
            return call_user_func($this->toMailUsing, $this->mailMessage, $notifiable);
        }

        $mailMessage = $this->mailMessage;

        if ($mailMessage->subject !== '' && $mailMessage->subject !== '0') {
            $mailMessage->subject(Str::finish($mailMessage->subject, ' - ' . config('app.name')));
        }

        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        if ($this->toArrayUsing instanceof \Closure) {
            return call_user_func($this->toArrayUsing, $this->data, $notifiable);
        }

        return $this->data;
    }

    public function retryUntil()
    {
        return now()->addMinutes(2);
    }
}

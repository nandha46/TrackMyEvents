<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

class EventAdded extends Notification
{
    use Queueable;

    public $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if (config('services.slack.notifications.bot_user_oauth_token')) {
            $channels[] = 'slack';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Event Added: ' . $this->event->event_name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new event "' . $this->event->event_name . '" was successfully scheduled for ' . $this->event->event_date . '.')
            ->line('Category: ' . $this->event->event_type)
            ->action('View Event', route('event.show', $this->event->id))
            ->line('Thank you for using TrackMyEvents!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'name' => $this->event->event_name,
            'date' => $this->event->event_date,
            'type' => $this->event->event_type,
            'message' => 'Event "' . $this->event->event_name . '" was added for ' . $this->event->event_date,
        ];
    }

    /**
     * Get the Slack representation of the notification.
     */
    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->text('New event created: ' . $this->event->event_name)
            ->headerBlock('New Event Added')
            ->sectionBlock(function (SectionBlock $block) {
                $block->text('*' . $this->event->event_name . '* has been created.');
                $block->field("*Event Date:*\n" . $this->event->event_date)->markdown();
                $block->field("*Event Type:*\n" . $this->event->event_type)->markdown();
            })
            ->dividerBlock();
    }
}

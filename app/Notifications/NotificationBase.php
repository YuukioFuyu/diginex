<?php

namespace App\Notifications;

use App\Facades\NotificationTemplateFacade as NotificationTemplate;
use Exception;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class NotificationBase
 *
 * Base class for notifications that provides common functionality for sending notifications
 * via different channels (email, SMS, push, etc.) using predefined templates.
 */
abstract class NotificationBase extends Notification
{
    /**
     * @var array Template data for the notification (e.g., subject, message).
     */
    protected array $notifyData;

    /**
     * @var array Array data used in the notification message.
     */
    protected array $arrayData;

    /**
     * NotificationBase constructor.
     *
     * @param  string  $code  Notification template code.
     *
     * @throws Exception If template data retrieval fails.
     */
    public function __construct(string $code)
    {
        // Retrieve the notification template data from the facade
        $this->notifyData = NotificationTemplate::getTemplateData($code);

        // Cache the result of arrayData for reuse across methods
        $this->arrayData = $this->arrayData();

    }

    /**
     * Abstract method to be implemented by child classes to provide array data for notifications.
     *
     * @return array Associative array of data used in the notification.
     */
    abstract protected function arrayData(): array;

    /**
     * Defines the channels via which the notification should be sent.
     *
     * @param  mixed  $notifiable  The entity being notified (e.g., User, Admin).
     * @return array Channels for the notification (e.g., 'mail', 'database').
     */
    public function via(mixed $notifiable): array
    {
        return $this->notifyData['via'];
    }

    /**
     * Builds the email message content using the predefined notification template.
     *
     * @param  mixed  $notifiable  The entity being notified.
     * @return MailMessage Configured MailMessage instance.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->notifyData['subject'] ?? __('Notification')) // Fallback subject
            ->line($this->buildMessage('mail_message'))
            ->action(__('View Details'), $this->arrayData['action_link'] ?? route('home'))
            ->line(__('Thank you for using our application!'));
    }

    /**
     * Builds the notification message by replacing placeholders with dynamic data.
     *
     * @param  string  $type  The type of message to build (e.g., 'mail_message', 'push_message').
     * @return string The generated message with placeholders replaced by actual values.
     */
    protected function buildMessage(string $type): string
    {
        // Replace placeholders in the template with actual data
        $keys = collect(array_keys($this->arrayData))->map(fn ($key) => '{$'.$key.'}')->toArray();
        $values = array_values($this->arrayData);

        return str_replace($keys, $values, $this->notifyData[$type] ?? '');
    }

    /**
     * Converts the notification into an array format, adding the generated message.
     *
     * @param  mixed  $notifiable  The entity being notified.
     * @return array Array representation of the notification for storage or further processing.
     */
    public function toArray(mixed $notifiable): array
    {
        return array_merge($this->arrayData, [
            'message' => $this->buildMessage('push_message'),
        ]);
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage(array_merge($this->arrayData, [
            'message' => $this->buildMessage('push_message'),
        ]));
    }


    /**
     * Formats a given amount as currency using the application's currency symbol.
     *
     * @param  float  $amount  The amount to format.
     * @return string The formatted currency string (e.g., "$100.00").
     */
    protected function formatCurrency(float $amount): string
    {
        return setting('currency_symbol').number_format($amount, 2);
    }
}

<?php

namespace App\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BanNotification extends Notification
{
    use Queueable;

    protected $userId;
    protected $ipAddress;
    protected $email;
    protected $banDuration;

    public function __construct($userId, $ipAddress, $email, $banDuration)
    {
        $this->userId = $userId;
        $this->ipAddress = $ipAddress;
        $this->email = $email;
        $this->banDuration = $banDuration;
    }

    public function via($notifiable)
    {
        
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Build the mail representation of the notification
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('User Banned Notification')
            ->line("User with ID: {$this->userId} has been banned.")
            ->line("IP Address: {$this->ipAddress}")
            ->line("Email: {$this->email}")
            ->line("Ban duration: {$this->banDuration} minutes")
            ->action('View User Details', url('/users/'.$this->userId))
            ->line('Thank you for using our application!');
    }

}

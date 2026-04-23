<?php

namespace App\Notifications;

use App\Models\Producto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComenzarCultivoNotification extends Notification
{
    use Queueable;

    protected Producto $producto;

    /**
     * Create a new notification instance.
     */
    public function __construct(Producto $producto)
    {
        $this->producto = $producto;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Alerta: Comenzar Cultivo de ' . $this->producto->nombre)
            ->line('Se ha activado una alerta para comenzar el cultivo del producto: ' . $this->producto->nombre)
            ->line('Tipo: ' . $this->producto->tipo)
            ->action('Ver Producto', route('productos.show', $this->producto))
            ->line('¡Es hora de plantar!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'producto_id' => $this->producto->id,
            'mensaje' => 'Comenzar cultivo de ' . $this->producto->nombre,
        ];
    }
}

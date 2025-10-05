<?php

namespace Waynelogic\MagicForms\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use NotificationChannels\Telegram\TelegramMessage;
use Waynelogic\MagicForms\Filament\Resources\FormRecords\Pages\ViewFormRecord;
use Waynelogic\MagicForms\Models\FormRecord;

class MagicFormNotification extends Notification
{
    use Queueable;

    private string $url;
    private array $data;
    private array $files;
    /**
     * Create a new notification instance.
     */
    public function __construct(
        public FormRecord $record
    ) {
        $this->url = ViewFormRecord::getUrl(['record' => $this->record->id]);
        $this->data = $this->record->form_data;
        $this->files = $this->record->getFilesUrlAttribute();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $arVia = [];
        if (isset($notifiable->routes['mail'])) {
            $arVia[] = 'mail';
        }
        if (isset($notifiable->routes['telegram'])) {
            $arVia[] = 'telegram';
        }
        return $arVia;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Генерация HTML-таблицы
        $tableHtml = '<table style="width:100%; border-collapse: collapse; word-break: break-all;">';
        $tableHtml .= '<tr>';
        $tableHtml .= '<td colspan="2" style="border: 1px solid #ddd; padding: 8px; text-align: center;"><strong>Данные</strong></td>';
        $tableHtml .= '</tr>';
        foreach ($this->data as $key => $value) {
            $tableHtml .= '<tr>';
            $tableHtml .= '<td style="border: 1px solid #ddd; padding: 8px;"><strong>' . e($key) . '</strong></td>';
            $tableHtml .= '<td style="border: 1px solid #ddd; padding: 8px;">' . e($value) . '</td>';
            $tableHtml .= '</tr>';
        }
        if ($this->files) {
            $tableHtml .= '<tr>';
            $tableHtml .= '<td colspan="2" style="border: 1px solid #ddd; padding: 8px; text-align: center;"><strong>Файлы</strong></td>';
            $tableHtml .= '</tr>';
            foreach ($this->files as $file) {
                $tableHtml .= '<tr>';
                $tableHtml .= '<td colspan="2" style="border: 1px solid #ddd; padding: 8px;">' . e($file) . '</td>';
                $tableHtml .= '</tr>';
            }
        }

        $tableHtml .= '</table>';

        $mail = (new MailMessage)
            ->subject('Форма обратной связи |' . config('app.name'))
            ->greeting('Добрый день!')
            ->line('На сайте ' . url('/') . ' была отправлена форма обратной связи.')
            ->line('Группа: ' . $this->record->group)
            ->line(new HtmlString($tableHtml))
            ->action('Открыть', url($this->url))
            ->line('IP:' . $this->record->ip);

        if (!empty($this->record->city) && !empty($this->record->country)) {
            $mail->line('Город: ' . $this->record->city);
            $mail->line('Страна: ' . $this->record->country);
        }

        return $mail;
    }

    public function toTelegram(object $notifiable)
    {
        $lines = collect($this->data)->map(fn($value, $key) => "*{$key}* — {$value}")->all();
        $content = implode("\n", $lines);

        if ($this->files) {
            $files = collect($this->files)->map(fn($file) => "[{$file}]({$file})")->join('');
            $content .= "\n\nФайлы:\n{$files}";
        }

        $content .= "\n\n*IP:* {$this->record->ip}";
        if (!empty($this->record->city) && !empty($this->record->country)) {
            $content .= "\n*Город:* {$this->record->city}\n*Страна:* {$this->record->country}";
        }

        return TelegramMessage::create()
            ->line('На сайте ' . url('/') . ' была отправлена форма обратной связи.')
            ->line('Группа: ' . $this->record->group)
            ->line('')
            ->line($content)
            ->button('Открыть', $this->url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

<?php
 
 namespace App\Enums;
 
 enum AppointmentStatus: string
 {
     case SCHEDULED = 'scheduled';
     case CONFIRMED = 'confirmed';
     case CHECKED_IN = 'checked_in';
     case IN_SERVICE = 'in_service';
     case COMPLETED = 'completed';
     case CANCELLED = 'cancelled';
     case NO_SHOW = 'no_show';

   /**
   * Hasta listesindeki "aktif" randevu durumlarını tek bir yerden yönetir.
  */
     public static function activeForListing(): array
   {
        return [
            self::SCHEDULED->value,
            self::CONFIRMED->value,
            self::CHECKED_IN->value,
            self::IN_SERVICE->value,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::SCHEDULED => 'Planlandı',
            self::CONFIRMED => 'Onaylandı',
            self::CHECKED_IN => 'Check-in Yapıldı',
            self::IN_SERVICE => 'İşlemde',
            self::COMPLETED => 'Tamamlandı',
            self::CANCELLED => 'İptal Edildi',
            self::NO_SHOW => 'Gelmedi',
        };
    }
 }

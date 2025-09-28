<?php

namespace App\Enums;

enum FileType: string
{
    case XRAY = 'xray';
    case DOCUMENT = 'document';
    case PHOTO = 'photo';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::XRAY => 'Röntgen',
            self::DOCUMENT => 'Belge',
            self::PHOTO => 'Fotoğraf',
            self::OTHER => 'Diğer',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::XRAY => 'Röntgen görüntüleri ve radyolojik kayıtlar',
            self::DOCUMENT => 'Reçeteler, raporlar, sözleşmeler ve diğer belgeler',
            self::PHOTO => 'Klinik fotoğrafları ve görseller',
            self::OTHER => 'Diğer dosya türleri',
        };
    }

    public function allowedExtensions(): array
    {
        return match ($this) {
            self::XRAY => ['jpg', 'jpeg', 'png', 'dcm', 'tiff'],
            self::DOCUMENT => ['pdf', 'doc', 'docx', 'txt', 'rtf'],
            self::PHOTO => ['jpg', 'jpeg', 'png', 'gif', 'bmp'],
            self::OTHER => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'txt'],
        };
    }

    public function maxFileSize(): int
    {
        return match ($this) {
            self::XRAY => 10 * 1024 * 1024, // 10MB for X-rays
            self::DOCUMENT => 5 * 1024 * 1024, // 5MB for documents
            self::PHOTO => 8 * 1024 * 1024, // 8MB for photos
            self::OTHER => 5 * 1024 * 1024, // 5MB for others
        };
    }

    public function mimeTypes(): array
    {
        return match ($this) {
            self::XRAY => ['image/jpeg', 'image/png', 'application/dicom', 'image/tiff'],
            self::DOCUMENT => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'application/rtf'],
            self::PHOTO => ['image/jpeg', 'image/png', 'image/gif', 'image/bmp'],
            self::OTHER => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'text/plain'],
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($type) => [
            $type->value => $type->label()
        ])->toArray();
    }

    public function isImage(): bool
    {
        return in_array($this, [self::XRAY, self::PHOTO]);
    }

    public function requiresPreview(): bool
    {
        return $this->isImage() || $this === self::DOCUMENT;
    }
}
<?php

namespace App\Enums;

enum FileType: string
{
    case XRAY = 'xray';
    case PHOTO = 'photo';
    case DOC = 'doc';
    case OTHER = 'other';
}
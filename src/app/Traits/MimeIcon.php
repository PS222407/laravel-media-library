<?php

namespace Jensramakers\LaravelMediaLibrary\app\Traits;

trait MimeIcon
{
    public function getIconByMimeTYpe($mime_type): string
    {
        // List of official MIME Types: http://www.iana.org/assignments/media-types/media-types.xhtml
        $icon_classes = array(
            // Media
            'image' => 'fa-file-image',
            'audio' => 'fa-file-audio',
            'video' => 'fa-file-video',
            // Documents
            'application/pdf' => 'fa-file-pdf',
            'application/msword' => 'fa-file-word',
            'application/vnd.ms-word' => 'fa-file-word',
            'application/vnd.oasis.opendocument.text' => 'fa-file-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml' => 'fa-file-word',
            'application/vnd.ms-excel' => 'fa-file-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml' => 'fa-file-excel',
            'application/vnd.oasis.opendocument.spreadsheet' => 'fa-file-excel',
            'application/vnd.ms-powerpoint' => 'fa-file-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml' => 'fa-file-powerpoint',
            'application/vnd.oasis.opendocument.presentation' => 'fa-file-powerpoint',
            'text/plain' => 'fa-file-text',
            'text/html' => 'fa-file-code',
            'application/json' => 'fa-file-code',
            // Archives
            'application/gzip' => 'fa-file-zipper',
            'application/zip' => 'fa-file-zipper',
        );
        foreach ($icon_classes as $text => $icon) {
            if (str_starts_with($mime_type, $text)) {
                return $icon;
            }
        }
        return 'fa-file';
    }
}
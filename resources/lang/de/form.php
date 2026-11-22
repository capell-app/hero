<?php

declare(strict_types=1);

return [
    'hero_background' => [
        'label' => 'Hero-Hintergrund',
        'mode' => 'Hero-Hintergrund',
        'background_color' => 'Hintergrundfarbe',
        'accent_color' => 'Akzentfarbe',
        'accent_color_alt' => 'Zweite Akzentfarbe',
        'overlay_style' => 'Overlay-Stil',
        'overlay_opacity' => 'Overlay-Stärke',
        'theme_helper' => 'Seiten mit diesem Theme übernehmen diesen Hero-Hintergrund, sofern das Widget oder das Widget-Asset ihn nicht überschreibt.',
        'widget_helper' => 'Dieses Widget verwendet standardmäßig den Hero-Hintergrund des Website-Themes. Wählen Sie „Benutzerdefiniert“, um ihn hier zu überschreiben, oder „Aus“, um die dekorative Ebene zu deaktivieren.',
        'asset_helper' => 'Dieses Widget-Asset übernimmt standardmäßig den Hero-Hintergrund des Widgets oder des Themes. Wählen Sie „Aus“, wenn das Bild des Assets für sich allein stehen soll.',
        'mode_options' => [
            'inherit' => 'Theme/Widget verwenden',
            'default' => 'Standard-Overlay',
            'custom' => 'Benutzerdefiniertes Overlay',
            'off' => 'Aus',
        ],
        'overlay_options' => [
            'mesh' => 'Weiches Mesh',
            'ribbons' => 'Bandlinien',
            'grid' => 'Dezentes Raster',
            'contours' => 'Konturen',
        ],
        'overlay_opacity_options' => [
            '0_16' => '16 %',
            '0_24' => '24 %',
            '0_32' => '32 %',
            '0_44' => '44 %',
            '0_56' => '56 %',
        ],
    ],
    'hero_media' => [
        'use_custom' => 'Hier benutzerdefinierte Medien verwenden',
        'upload_help' => [
            'custom_asset' => 'Diese gespeicherten Uploads werden bei den jeweils passenden Bildschirmgrößen verwendet. Benutzerdefinierte Slide-Medien können eine deaktivierte Widget-Basis ersetzen.',
            'custom' => 'Diese gespeicherten Uploads werden bei den jeweils passenden Bildschirmgrößen verwendet. Ein übergeordneter Modus „Aus“ deaktiviert Medien weiterhin.',
            'inherit' => 'Es werden übernommene Medien verwendet. Hier gespeicherte Uploads bleiben erhalten, werden aber ignoriert, bis Sie „Benutzerdefinierte Medien“ wählen.',
            'off' => 'Medien sind deaktiviert. Hier gespeicherte Uploads bleiben erhalten, werden aber ignoriert, bis Sie „Benutzerdefinierte Medien“ wählen.',
        ],
        'theme_inheritance' => 'Die Theme-Medien liefern den Standard für das Widget. „Aus“ deaktiviert Medien für Widgets, die von diesem Theme erben.',
        'widget_inheritance' => '„Übernehmen“ verwendet die Theme-Medien. Ist das Theme auf „Aus“ gesetzt, sind die Widget-Medien auch im Modus „Benutzerdefiniert“ deaktiviert.',
        'asset_inheritance' => '„Übernehmen“ verwendet zuerst die Widget-Medien, dann die Theme-Medien. Ein übergeordneter Modus „Aus“ deaktiviert die übernommene Basis.',
        'label' => 'Hero-Medien',
        'uploads_label' => 'Responsive Hero-Medien',
        'mode' => 'Hero-Medien',
        'autoplay' => 'Video automatisch abspielen',
        'loop' => 'Video in Endlosschleife abspielen',
        'muted' => 'Video stummschalten',
        'pause_when_out_of_view' => 'Pausieren, wenn nicht sichtbar',
        'preload' => 'Video-Vorladen',
        'desktop_video' => 'Desktop-Video',
        'tablet_video' => 'Tablet-Video',
        'mobile_video' => 'Mobil-Video',
        'desktop_image' => 'Desktop-Ersatzbild',
        'tablet_image' => 'Tablet-Ersatzbild',
        'mobile_image' => 'Mobil-Ersatzbild',
        'mode_options' => [
            'inherit' => 'Theme/Widget verwenden',
            'custom' => 'Benutzerdefinierte Medien',
            'off' => 'Aus',
        ],
        'preload_options' => [
            'none' => 'Keine',
            'metadata' => 'Metadaten',
            'auto' => 'Automatisch',
        ],
    ],
];

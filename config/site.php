<?php

return [
    /* Developer / site owner info */
    'developer' => [
        'name' => env('SITE_DEVELOPER_NAME', 'Your Name'),
        'email' => env('SITE_DEVELOPER_EMAIL', 'you@example.com'),
    ],

    /* Short about text (shown in footer) */
    'about' => env('SITE_ABOUT_TEXT', 'This is a demo shop I developed to showcase electronic products and accessories. You can edit this text to describe yourself, your experience, or contact details.'),

    /* Quick links (can be overridden if your routes differ) */
    'quick_links' => [
        'home' => env('SITE_LINK_HOME', '/'),
        'categories' => env('SITE_LINK_CATEGORIES', '/categories'),
        'products' => env('SITE_LINK_PRODUCTS', '/products'),
        'contact' => env('SITE_LINK_CONTACT', '/contact'),
    ],

    /* Social links (leave empty to hide the icon) */
    'social' => [
        'facebook' => env('SITE_SOCIAL_FACEBOOK', ''),
        'twitter' => env('SITE_SOCIAL_TWITTER', ''),
        'instagram' => env('SITE_SOCIAL_INSTAGRAM', ''),
        /* WhatsApp: can be a full URL (https://wa.me/...) or a phone number like +201234567890 */
        'whatsapp' => env('SITE_SOCIAL_WHATSAPP', ''),
    ],
];

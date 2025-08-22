<?php

return [
    '*' => [
        'data' => [
            // EXAMPLE: having different data for different sites
            'Background colors' => [
                'primarySiteHandle' => [
                    'section--default' => 'Default',
                    'section--light' => 'Light',
                    'section--primary' => 'Primary',
                ],
                'secondarySiteHandle' => [
                    'section--default' => 'Default',
                    'section--light' => 'Light',
                    'section--primary' => 'Primary',
                ],
            ],
            // EXAMPLE: Or just one data set for all sites
            'CTA styles' => [
                'btn btn--primary btn--ext' => 'Primary >',
                'btn btn--secondary btn--ext' => 'Secondary >',
                'link link--ext' => 'Link >',
            ],
        ],
    ],
    'production' => [
        // Production config
    ],
    'dev' => [
        // Development config
    ]
];

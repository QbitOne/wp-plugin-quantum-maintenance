<?php

$update = array(
    'name'              => 'Quaintenance',
    'slug'              => 'quaintenance',
    'version'           => '1.2.0',
    'author'            => '<a href="https://qbitone.de">Andreas Geyer</a>',
    'download_url'      => 'https://dev.qbitone.de/files/plugins/quaintenance/quaintenance.zip',
    'requires'          => '5.8.2',
    'tested'            => '5.8.2',
    'requires_php'      => '7.4',
    'last_updated'      => '2021-12-20 00:00:00', // format '2021-01-31 00:00:00'
    'sections'          => [ // the sections/tabs inside plugin update details
        'description'       => '',
        'installation'      => '',
        'changelog'         => file_get_contents('changelog.html'),
    ],
    'banners'           => [
        'low'               => '', // url to low resolution image
        'high'              => '', // url to high resolution image
    ]
);

header('Content-Type: application/json');
echo json_encode($update);

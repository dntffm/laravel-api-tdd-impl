<?php

namespace App\Http\Services;

use Xendit\Xendit;

class CheckoutService {
    function __construct()
    {
        Xendit::setApiKey(env('xnd_development_US2mWjQR1vd0JGMBXU3bT3MG14zTHj8oUJuG4WJIIaimbgmBFP5cQtdsp3wSsLP'));
    }

    public function createInvoice()
    {
        $params = [ 
            'external_id' => 'demo_1475801962607',
            'amount' => 50000,
            'description' => 'Invoice Demo #123',
            'invoice_duration' => 86400,
            'customer' => [
                'given_names' => 'John',
                'surname' => 'Doe',
                'email' => 'johndoe@example.com',
                'mobile_number' => '+6287774441111',
                'addresses' => [
                    [
                        'city' => 'Jakarta Selatan',
                        'country' => 'Indonesia',
                        'postal_code' => '12345',
                        'state' => 'Daerah Khusus Ibukota Jakarta',
                        'street_line1' => 'Jalan Makan',
                        'street_line2' => 'Kecamatan Kebayoran Baru'
                    ]
                ]
            ],
            'customer_notification_preference' => [
                'invoice_created' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ],
                'invoice_reminder' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ],
                'invoice_paid' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ],
                'invoice_expired' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ]
            ],
            'success_redirect_url' => 'https=>//www.google.com',
            'failure_redirect_url' => 'https=>//www.google.com',
            'currency' => 'IDR',
            'items' => [
                [
                    'name' => 'Air Conditioner',
                    'quantity' => 1,
                    'price' => 100000,
                    'category' => 'Electronic',
                    'url' => 'https=>//yourcompany.com/example_item'
                ]
            ],
            'fees' => [
                [
                    'type' => 'ADMIN',
                    'value' => 5000
                ]
            ]
          ];
        
        \Xendit\Xendit::setApiKey('xnd_development_US2mWjQR1vd0JGMBXU3bT3MG14zTHj8oUJuG4WJIIaimbgmBFP5cQtdsp3wSsLP');
        $createInvoice = \Xendit\Invoice::create($params);
        return $createInvoice;
    }
}
<?php

namespace Database\Seeders;

use App\Enums\GatewayMode;
use App\Enums\Activity;
use App\Enums\InputType;
use App\Models\GatewayOption;
use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewayTableSeederVersionOne extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public array $gateways = [
        [
            "name"    => "Cash On Delivery",
            "slug"    => "cash-on-delivery",
            "misc"    => null,
            "status"  => Activity::ENABLE,
            "options" => [],
        ],
        [
            "name"    => "Credit",
            "slug"    => "credit",
            "misc"    => null,
            "status"  => Activity::ENABLE,
            "options" => [],
        ],
        [
            "name"    => "Bkash",
            "slug"    => "bkash",
            "misc"    => null,
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option"     => 'bkash_app_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'bkash_app_secret',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'bkash_username',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'bkash_password',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'bkash_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'bkash_status',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ]
            ]
        ],
        [
            "name"    => "SslCommerz",
            "slug"    => "sslcommerz",
            "misc"    => null,
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option"     => 'sslcommerz_store_name',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'sslcommerz_store_id',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'sslcommerz_store_password',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'sslcommerz_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'sslcommerz_status',
                    "value"      => Activity::ENABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ],
            ]
        ],
    ];

    public function run(): void
    {
        foreach ($this->gateways as $gateway) {
            $payment = PaymentGateway::create([
                'name'   => $gateway['name'],
                'slug'   => $gateway['slug'],
                'misc'   => json_encode($gateway['misc']),
                'status' => $gateway['status']
            ]);

            if (file_exists(public_path('/images/payment-gateway/' . $gateway['slug'] . '.png'))) {
                $payment->addMedia(public_path('/images/payment-gateway/' . $gateway['slug'] . '.png'))->preservingOriginal()->toMediaCollection('payment-gateway');
            }
            $this->gatewayOption($payment->id, $gateway['options']);
        }
    }

    public function gatewayOption($id, $options): void
    {
        if (!blank($options)) {
            foreach ($options as $option) {
                GatewayOption::create([
                    'model_id'   => $id,
                    'model_type' => 'App\Models\PaymentGateway',
                    'option'     => $option['option'],
                    'value'      => $option['value'] ?? "",
                    'type'       => $option['type'],
                    'activities' => json_encode($option['activities']),
                ]);
            }
        }
    }
}

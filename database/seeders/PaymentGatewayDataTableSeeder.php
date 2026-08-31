<?php

namespace Database\Seeders;

use App\Enums\GatewayMode;
use App\Enums\Activity;
use App\Models\GatewayOption;
use App\Models\PaymentGateway;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class PaymentGatewayDataTableSeeder extends Seeder
{
    public array $gateways = [
        [
            "slug" => "sslcommerz",
            "status" => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'sslcommerz_store_name',
                    "value" => 'testfoodkp1pi',
                ],
                [
                    "option" => 'sslcommerz_store_id',
                    "value" => 'foodk6472ed754a400',
                ],
                [
                    "option" => 'sslcommerz_store_password',
                    "value" => 'foodk6472ed754a400@ssl',
                ],
                [
                    "option" => 'sslcommerz_mode',
                    "value" => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'sslcommerz_status',
                    "value" => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug" => "bkash",
            "status" => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'bkash_app_key',
                    "value" => '4f6o0cjiki2rfm34kfdadl1eqq',
                ],
                [
                    "option" => 'bkash_app_secret',
                    "value" => '2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b',
                ],
                [
                    "option" => 'bkash_username',
                    "value" => 'sandboxTokenizedUser02'
                ],
                [
                    "option" => 'bkash_password',
                    "value" => 'sandboxTokenizedUser02@12345'
                ],
                [
                    "option" => 'bkash_mode',
                    "value" => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'bkash_status',
                    "value" => Activity::ENABLE,
                ],
            ]
        ],
    ];

    public function run(): void
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            foreach ($this->gateways as $gateway) {
                $payment = PaymentGateway::where(['slug' => $gateway['slug']])->first();
                if ($payment) {
                    $payment->status = $gateway['status'];
                    $payment->save();
                }
                $this->gatewayOption($gateway['options']);
            }
        }
    }

    public function gatewayOption($options): void
    {
        if (!blank($options)) {
            foreach ($options as $option) {
                $gatewayOption = GatewayOption::where(['option' => $option['option']])->first();
                if ($gatewayOption) {
                    $gatewayOption->value = $option['value'];
                    $gatewayOption->save();
                }
            }
        }
    }
}

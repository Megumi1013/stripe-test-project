<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends ApiController
{
    public function createCheckoutSession(Request $request)
    {
        try {

            $data = $request->json()->all();

            $rules = [
                'line_items' => 'required|array',
                'line_items.*' => 'required'
            ];

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return $this->createErrorResponse(400, 'The Stripe data is not valid.', 'data_invalid', $validator->errors());
            }

            $lineItems = [];

            foreach ($data['line_items'] as $lineItem) {
                $lineItems[] = [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $lineItem['title'] // Get from DB
                            ],
                            'unit_amount' => 2000 // Get from DB
                        ],
                        'quantity' => $lineItem['quantity']
                    ]
                ];
            }

            Stripe::setApiKey(env('STRIPE_API_KEY'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => 'https://example.com/success',
                'cancel_url' => 'https://example.com/cancel',
            ]);

            return $this->createSuccessResponse(200, 'Yay it worked!', 'success', [
                'id' => $session->id
            ]);

        } catch (\Exception $e) {

            return $this->createErrorResponse(500, $e->getMessage(), 'error');

        }
    }
}

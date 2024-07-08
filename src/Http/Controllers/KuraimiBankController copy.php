<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KuraimiBankController 
{
    /**
     * Sends a payment request to the Kuraimi bank's payment API.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendPayment(Request $request)
    {
        // Retrieve the safe data from the request
        $data = $request->safe();

        // Set the currency to Yemeni Rial (YER)
        $data["CRCY"] = "YER";

        // Encode the PINPASS value using base64
        $data["PINPASS"] = base64_encode($data->PINPASS);

        // Retrieve the authorization token
        $token = $this->encoding();

        // Construct the API endpoint URL
        $url = config('kuraimi.url.base') . "/alk-payments-exp/v1/PHEPaymentAPI/EPayment/SendPayment";

        try {
            // Send the payment request to the API
            $response = Http::withoutVerifying()
                ->withHeaders(['Authorization' => $token, 'Cache-Control' => 'no-cache'])
                ->withOptions(["verify" => false])
                ->post($url, $data);

            // Process the API response
            if ($response->successful()) {
                if ($response['Code'] == 1) {
                    // Return a successful JSON response
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'payment_id' => $response['ResultSet']['PH_REF_NO'],
                            'reference_id' => $data['REFNO'],
                            'phone' => $data['SCustID'],
                            'amount' => $data['AMOUNT'],
                            'payment_method' => 'Kuraimi_bank',
                            'response' => $response->json(),
                        ]
                    ]);
                }

                // Return a failure JSON response
                return response()->json(
                    ['status' => false, 'data' => $response->json()],
                    422
                );
            }
        } catch (Exception $e) {
            // Return an error JSON response
            $message = $e->getMessage();
            return response()->json([
                'status' => false,
                'message' => $message,
                'url' => url('')
            ], 400);
        }
    }

    /**
     * Initiates a reversal of a payment transaction with Kuraimi bank.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reversePayment(Request $request)
    {
        // Validate the required request parameters
        $request->validate(['SCUSTID' => 'required', 'REFNO' => 'required']);

        // Retrieve the payment ID from the request
        $payment_id = $request->REFNO;

        // Retrieve all the request data
        $data = $request->all();

        // Retrieve the authorization token
        $token = $this->encoding();

        // Construct the API endpoint URL
        $url = config('kuraimi.url.base') . "/alk-payments-exp/v1/PHEPaymentAPI/EPayment/ReversePayment";

        try {
            // Send the reversal request to the API
            $response = Http::withoutVerifying()
                ->withHeaders(['Authorization' => $token, 'Cache-Control' => 'no-cache'])
                ->withOptions(["verify" => false])
                ->post($url, $data);

            // Process the API response
            if ($response->successful()) {
                if ($response['Code'] == 1) {
                    // Retrieve the refund ID from the response
                    $refund_id = $response['ResultSet']['PH_REF_NO'];

                    // Construct the refund data array
                    $refund = [
                        'refund_id' => $refund_id,
                        'payment_id' => $payment_id,
                        'reference_id' => $request->reference_id,
                        'phone' => $request->phone,
                        'amount' => $request->amount,
                        'payment_method' => 'Kuraimi_bank',
                        'response' => $response->json(),
                    ];

                    // Return a successful JSON response with the refund data
                    return response()->json(
                        ['status' => true, 'data' => $refund]
                    );
                }

                // Return a failure JSON response
                return response()->json(
                    ['status' => false, 'data' => $response->json()],
                    422
                );
            }
        } catch (Exception $e) {
            // Return an error JSON response
            $message = $e->getMessage();
            return response()->json([
                'status' => false,
                'message' => $message,
                'url' => url('')
            ], 400);
        }
    }

    /**
     * Generates the authorization token for the Kuraimi bank's payment API.
     *
     * @return string
     */
    protected function encoding()
    {
        // Retrieve the Kuraimi bank's authentication credentials from the config
        $username = config('kuraimi.auth.username');
        $password = config('kuraimi.auth.password');

        // Concatenate the username and password with a colon separator
        $text = $username . ":" . $password;

        // Encode the concatenated text using base64
        $textAsBytes = base64_encode($text);

        // Prepend the "Basic " string to the base64 encoded text
        $base64 = "Basic " . $textAsBytes;

        // Return the authorization token
        return $base64;
    }
}

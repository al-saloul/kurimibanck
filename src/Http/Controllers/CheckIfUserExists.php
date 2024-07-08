<?php

namespace AlSaloul\KuraimibankPayment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class CheckIfUserExists
{
    protected $acceptedCustomerZones = [
        'YE0012003',
        'YE0012004',
        'YE0012005',
        'YE0012006',
        'YE0012007',
        'YE0012008',
        'YE0012009'
    ];

    /**
     * Handle the incoming request and verify the customer's mobile number and currency zone.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'MobileNo' => 'required',
            'CustomerZone' =>  Rule::in($this->acceptedCustomerZones)
        ]);

        if (!$this->getAcceptedZones($request->CustomerZone)) {
            return response()->json([
                "Code" => "2",
                "SCustID" => null,
                "DescriptionAr" => "عذرًا، العملة غير مستخدمة في التطبيق.",
                "DescriptionEn" => "Sorry, the currency is not used in the application."
            ]);
        }

        if ($this->checkIfMobileExists($request->MobileNo)) {
            return response()->json([
                "Code" => "1",
                "SCustID" => $request->MobileNo,
                "DescriptionAr" => " تم التحقق من تفاصيل العميل بنجاح",
                "DescriptionEn" => "Customer details verified successfully"
            ]);
        }

        return response()->json([
            "Code" => "2",
            "SCustID" => null,
            "DescriptionAr" => "تفاصيل العميل غير صالحة",
            "DescriptionEn" => "Invalid customer details"
        ]);
    }

    /**
     * Determine if the given customer currency zone is accepted based on the configured currency zone setting.
     *
     * @param string $customer_currency_zone
     * @return bool
     */
    protected function getAcceptedZones($customer_currency_zone)
    {
        $currency_zone = config('kuraimibank.currency_zone');

        if ($currency_zone == 'old') {
            return in_array($customer_currency_zone, ['YE0012003', 'YE0012004', 'YE0012005']);
        }

        if ($currency_zone == 'new') {
            return in_array($customer_currency_zone, ['YE0012006', 'YE0012007', 'YE0012008', 'YE0012009']);
        }

        if ($currency_zone == 'all') {
            return in_array($customer_currency_zone, $this->acceptedCustomerZones);
        }

        return false;
    }

    /**
     * Check if the given mobile number exists in the system.
     *
     * @param string $mobile_no
     * @return bool
     */
    protected function checkIfMobileExists($mobile_no): bool
    {
        // check for the mobile number in the database with model on the project.
        // return app(config('kuraimibank.model'))->where(config('kuraimibank.column_name'), $mobile_no)->exists();

        $response = Http::asMultipart()->withHeaders([
            'token' => config('api.odoo.token'),
        ])
            ->post(config('api.odoo.baseurl') . '/rayah/patient/check-user', [
                'MobileNo' => $request->MobileNo,
            ]);

        return $response->successful();
    }
}

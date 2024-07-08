<?php

namespace AlSaloul\KuraimibankPayment\Http\Middleware;

use App\Models\Webhook as ModelsWebhook;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class Webhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorization = $request->header('authorization');
        $authorization = str_replace("Basic ", "", $authorization);

        $encrypted = $authorization;
        $data_text = base64_decode($encrypted);
        $decoded_string = utf8_decode($data_text);

        $decoded_string = str_replace(" ", "", $decoded_string);
        $data = explode(':', $decoded_string);

        $username = $data[0] ?? "";
        $password = $data[1] ?? "";

        if ($username == config('kuraimibank.webhook_credentials.username') && $password == config('kuraimibank.webhook_credentials.password')) {
            return $next($request);
        }

        return response()->json(['success' => false, 'error' => 'Forbidden. Unauthenticated.'], 403);
    }
}

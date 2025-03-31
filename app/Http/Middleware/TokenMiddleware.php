<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $token = $request->headers->get('Authorization');
        $token = $request->bearerToken();

        $http = new Client();

        $response = $http->request(
            'POST',
            env('AUTH_URL') . '/api/auth/token',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ]
            ]
        );

        switch ($response->getStatusCode()) {
            case 200:
            case 201:
                $response = json_decode($response->getBody(), true);
                break;

            default:
                abort(401, 'Unauthorized');
        }

        $user = new User();

        collect($response)->each(function ($data, $key) use ($user) {
            $user->$key = $data;
        });

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}

<?php

namespace App\Providers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Validator::extendImplicit('force_json', function ($attribute, $value, $parameters, $validator) {
            return true;
        });

        // Override the default validation exception response
        $this->app->bind(
            ValidationException::class,
            function ($app, $parameters) {
                throw new HttpResponseException(
                    response()->json(['errors' => $parameters[0]->errors()], 422)
                );
            }
        );
    }
}
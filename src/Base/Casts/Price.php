<?php

namespace Lunar\Base\Casts;

use App\Infrastructure\Repositories\Contract\CurrencyContract;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Lunar\DataTypes\Price as PriceDataType;

class Price implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return PriceDataType
     */
    public function get($model, $key, $value, $attributes)
    {
        /** @var CurrencyContract $contract */
        $contract = app(CurrencyContract::class);
        $currency = $model->currency ?: $contract->getDefaultCurrency();

        if (! is_null($value)) {
            /**
             * Make it an integer based on currency requirements.
             */
            $value = preg_replace('/[^0-9]/', '', $value);
        }

        Validator::make([
            $key => $value,
        ], [
            $key => 'nullable|numeric',
        ])->validate();

        return new PriceDataType(
            (int) $value,
            $currency,
            $model->priceable->unit_quantity ?? $model->unit_quantity ?? 1,
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  PriceDataType  $value
     * @param  array  $attributes
     * @return array
     */
    public function set($model, $key, $value, $attributes)
    {
        return [
            $key => $value,
        ];
    }
}

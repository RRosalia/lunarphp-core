<?php

namespace Lunar\Base\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Lunar\Facades\Pricing;
use Lunar\Managers\PricingManager;
use Lunar\Models\Price;

/**
 * @property-read \App\Domain\Models\Price $price
 */
trait HasPrices
{
    /**
     * Get all of the models prices.
     */
    public function prices(): MorphMany
    {
        return $this->morphMany(
            Price::modelClass(),
            'priceable'
        );
    }

    public function price(): MorphOne
    {
        return $this->morphOne(
            Price::modelClass(),
            'priceable'
        );
    }

    /**
     * Return base prices query.
     */
    public function basePrices(): MorphMany
    {
        return $this->prices()->whereMinQuantity(1)->whereNull('customer_group_id');
    }

    public function priceBreaks(): MorphMany
    {
        return $this->prices()->where('min_quantity', '>', 1);
    }

    /**
     * Return a PricingManager for this model.
     */
    public function pricing(): PricingManager
    {
        return Pricing::for($this);
    }
}

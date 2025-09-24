<?php

namespace App\Services\Stock;

use App\Models\Stock\StockItem;
use App\Models\Stock\StockMovement;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StockMovementService
{
    public function recordIncoming(StockItem $item, float $quantity, array $attributes = []): StockMovement
    {
        return $this->recordMovement($item, 'in', $quantity, $attributes);
    }

    public function recordOutgoing(StockItem $item, float $quantity, array $attributes = []): StockMovement
    {
        return $this->recordMovement($item, 'out', $quantity, $attributes);
    }

    public function recordAdjustment(StockItem $item, float $quantity, array $attributes = []): StockMovement
    {
        return $this->recordMovement($item, 'adjustment', $quantity, $attributes);
    }

    protected function recordMovement(StockItem $item, string $direction, float $quantity, array $attributes): StockMovement
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero.');
        }

        return DB::transaction(function () use ($item, $direction, $quantity, $attributes) {
            $movement = new StockMovement(array_merge($attributes, [
                'direction' => $direction,
                'quantity' => $quantity,
            ]));
            $movement->stockItem()->associate($item);
            $movement->save();

            $newQuantity = $this->calculateNewQuantity($item, $direction, $quantity);

            if ($newQuantity < 0 && ! $item->allow_negative) {
                throw new InvalidArgumentException('Stock item cannot go below zero.');
            }

            $item->forceFill(['quantity' => $newQuantity])->save();

            return $movement;
        });
    }

    protected function calculateNewQuantity(StockItem $item, string $direction, float $quantity): float
    {
        $current = (float) $item->quantity;

        return match ($direction) {
            'in' => $current + $quantity,
            'out' => $current - $quantity,
            default => $quantity,
        };
    }
}

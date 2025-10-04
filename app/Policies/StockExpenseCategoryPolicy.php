<?php

namespace App\Policies;

use App\Models\Stock\StockExpenseCategory;
use App\Models\User;

class StockExpenseCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('accessStockManagement');
    }

    public function view(User $user, StockExpenseCategory $stockExpenseCategory): bool
    {
        return $user->can('accessStockManagement');
    }

    public function create(User $user): bool
    {
        return $user->can('accessStockManagement');
    }

    public function update(User $user, StockExpenseCategory $stockExpenseCategory): bool
    {
        return $user->can('accessStockManagement');
    }

    public function delete(User $user, StockExpenseCategory $stockExpenseCategory): bool
    {
        return $user->can('accessStockManagement');
    }

    public function restore(User $user, StockExpenseCategory $stockExpenseCategory): bool
    {
        return $user->can('accessStockManagement');
    }

    public function forceDelete(User $user, StockExpenseCategory $stockExpenseCategory): bool
    {
        return $user->can('accessStockManagement');
    }
}

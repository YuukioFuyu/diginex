<?php

namespace App\Services;

use App\Constants\BalanceUpdateType;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    /**
     * Add balance to the user's account
     */
    public function add(User $user, float $amount): bool
    {

        return $user->addBalance($amount);
    }

    /**
     * Subtract balance from the user's account
     */
    public function subtract(User $user, float $amount): bool
    {
        return $user->subtractBalance($amount);
    }

    /**
     * Update the user's balance
     *
     * @return bool
     */
    public function update(User $user, string $type, float $amount)
    {
        // Check if the type is valid
        if (! in_array($type, BalanceUpdateType::TYPES)) {
            throw new \InvalidArgumentException('Invalid balance update type');
        }

        // Update the balance
        return match ($type) {
            BalanceUpdateType::ADD => $this->add($user, $amount),
            BalanceUpdateType::SUBTRACT => $this->subtract($user, $amount),
            default => false,
        };

    }

    /**
     * Get the user's current balance
     */
    public function get(User $user): float
    {
        return $user->getBalance();
    }

    /**
     * Transfer balance between users
     */
    public function transfer(User $fromUser, User $toUser, float $amount): bool
    {
        return DB::transaction(function () use ($fromUser, $toUser, $amount) {
            if ($this->subtract($fromUser, $amount)) {
                return $this->add($toUser, $amount);
            }

            return false;
        });
    }
}

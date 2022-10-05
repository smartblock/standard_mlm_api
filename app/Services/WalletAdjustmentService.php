<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 20/09/2022
 * Time: 11:00 AM
 */

namespace App\Services;

use App\Traits\ResponseAPI;

use App\Interfaces\UserInterface;
use App\Interfaces\WalletSetupInterface;
use App\Interfaces\WalletSummaryInterface;
use App\Interfaces\WalletDetailInterface;

class WalletAdjustmentService extends WalletService
{
    use ResponseAPI;

    protected $transType, $walletDetailInterface;

    public function __construct(UserInterface $interface, WalletSetupInterface $walletSetupInterface, WalletSummaryInterface $balanceInterface, WalletDetailInterface $walletDetailInterface)
    {
        parent::__construct($interface, $walletSetupInterface, $balanceInterface, $walletDetailInterface);
        $this->setTransType("ADJUSTMENT");
    }

    public function setTransType(string $trans_type)
    {
        $this->transType = $trans_type;
    }

    public function store(string $username, string $wallet_type, float $amount, array $options)
    {
        $user = $this->interface->findBy('username', $username, ['id']);
        if (!$user) {
            return $this->response(false, 'invalid_user');
        }

        $wallet = $this->walletSetupInterface->findBy('code', $wallet_type, ['id']);
        if (!$wallet) {
            return $this->response(false, 'invalid_wallet');
        }

        $result = $this->walletTransInOut($user['id'], $wallet['id'], $this->transType, ($amount > 0) ? $amount : 0, ($amount < 0) ? abs($amount) : 0, [
                'remark' => $options['remark'] ?? null
            ]);
        if ($result) {
            return $this->response(true, 'record_saved_successfully');
        }

        return $this->response(false, 'failed_to_save');
    }
}
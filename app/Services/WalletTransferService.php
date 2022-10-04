<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 20/09/2022
 * Time: 11:00 AM
 */

namespace App\Services;

use App\Traits\ResponseAPI;
use Carbon\Carbon;

use App\Interfaces\UserInterface;
use App\Interfaces\WalletSetupInterface;
use App\Interfaces\WalletSummaryInterface;
use App\Interfaces\WalletDetailInterface;
use App\Interfaces\WalletTransferInterface;

class WalletTransferService extends WalletService
{
    use ResponseAPI;

    protected $transType, $walletTransferInterface;

    public function __construct(
        UserInterface $interface,
        WalletSetupInterface $walletSetupInterface,
        WalletSummaryInterface $balanceInterface,
        WalletDetailInterface $walletDetailInterface,
        WalletTransferInterface $walletTransferInterface
    )
    {
        parent::__construct($interface, $walletSetupInterface, $balanceInterface, $walletDetailInterface);
        $this->walletTransferInterface = $walletTransferInterface;
        $this->setTransType("TRANSFER");
    }

    /**
     * @param string $trans_type
     */
    public function setTransType(string $trans_type)
    {
        $this->transType = $trans_type;
    }

    public function all(int $page, array $columns = ['*'], array $params = [], array $order = [], array $relations = [])
    {
        if (!empty($params['limit'])) {
            $this->walletTransferInterface->setPerPage($params['limit']);
        }

        $limit = $this->walletTransferInterface->perPage();
        $start = $page == 1 ? 0 : --$page * $limit;

        $result = $this->walletTransferInterface->pagination($start, $limit, $columns, $params, $order, $relations);

        return $this->responsePaginate($limit, $result);
    }

    /**
     * @param string $sender
     * @param string $receiver
     * @param string $wallet_type_from
     * @param string $wallet_type_to
     * @param float $amount
     * @param array $options
     * @return array
     */
    public function store(string $sender, string $receiver, string $wallet_type_from, string $wallet_type_to, float $amount, array $options)
    {
        $user_from = $this->interface->findBy('username', $sender, ['id']);
        if (!$user_from) {
            return $this->response(false, 'invalid_sender');
        }

        $user_to = $this->interface->findBy('username', $receiver, ['id']);
        if (!$user_to) {
            return $this->response(false, 'invalid_receiver');
        }

        $wallet_from = $this->walletSetupInterface->findBy('code', $wallet_type_from);
        if (!$wallet_from) {
            return $this->response(false, 'invalid_wallet_from');
        }

        $wallet_to = $this->walletSetupInterface->findBy('code', $wallet_type_to);
        if (!$wallet_to) {
            return $this->response(false, 'invalid_wallet_to');
        }

        if ($wallet_from['is_allowed_transfer'] == 0) {
            return $this->response(false, 'permission_denied');
        }

        if ($wallet_from['transfer_min'] > $amount) {
            return $this->response(false, 'invalid_minimum_amount');
        }

        if ($wallet_from['transfer_max'] && $wallet_from['transfer_max'] < $amount) {
            return $this->response(false, 'invalid_maximum_amount');
        }

        $balance_from = $this->getBalanceById($user_from['id'], $wallet_from['id']);
        if ($balance_from < $amount) {
            return $this->response(false, 'insufficient_balance');
        }

        $result_out = $this->walletTransInOut($user_from['id'], $wallet_from['id'], $this->transType, 0, $amount, [
                'remark' => $options['remark'] ?? null
            ]);
        if ($result_out) {
            $result_in = $this->walletTransInOut($user_to['id'], $wallet_to['id'], $this->transType, $amount, 0, [
                'remark' => $options['remark'] ?? null
            ]);
            if ($result_in['status']) {
                $this->walletTransferInterface->create([
                    'doc_date' => Carbon::now()->format('Y-m-d'),
                    'doc_no' => '',
                    'user_id' => $user_from['id'],
                    'user_id_to' => $user_to['id'],
                    'wallet_id' => $wallet_from['id'],
                    'wallet_id_to' => $wallet_to['id'],
                    'transfer_amount' => $amount,
                    'transfer_amount_to' => $amount,
                ]);
                return $this->response(true, 'record_saved_successfully');
            }

            return $this->response(false, $result_in['message']);
        }

        return $this->response(false, $result_out['message']);
    }
}
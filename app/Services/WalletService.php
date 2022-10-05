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

class WalletService extends BaseService
{
    use ResponseAPI;

    protected $walletSetupInterface, $balanceInterface, $walletDetailInterface;

    public function __construct(
        UserInterface $interface,
        WalletSetupInterface $walletSetupInterface,
        WalletSummaryInterface $balanceInterface,
        WalletDetailInterface $walletDetailInterface
    )
    {
        parent::__construct($interface);
        $this->walletSetupInterface = $walletSetupInterface;
        $this->balanceInterface = $balanceInterface;
        $this->walletDetailInterface = $walletDetailInterface;
    }

    public function listAll(array $columns = ['*'], array $relations = [], array $params = [], array $orders = [], string $lock = null)
    {
        return $this->walletSetupInterface->all($columns, $relations, $params, $orders, $lock);
    }

    public function all(int $page, array $columns = ['*'], array $params = [], array $order = [], array $relations = [])
    {
        if (!empty($params['limit'])) {
            $this->walletDetailInterface->setPerPage($params['limit']);
        }

        $limit = $this->walletDetailInterface->perPage();
        $start = $page == 1 ? 0 : --$page * $limit;

        $result = $this->walletDetailInterface->pagination($start, $limit, $columns, $params, $order, $relations);

        return $this->responsePaginate($limit, $result);
    }

    public function walletTransInOut(int $user_id, int $wallet_id, string $trans_type, float $total_in, float $total_out, array $params, string $remark = null)
    {
        $user = $this->interface->findBy('id', $user_id, ['*'], []);
        if (!$user) {
            return $this->response(false, 'invalid_user');
        }

        $wallet = $this->walletSetupInterface->findBy('id', $wallet_id, ['*'], []);
        if (!$wallet) {
            return $this->response(false, 'invalid_payment_wallet');
        }

        if ($total_in > 0 && $total_out > 0) {
            return $this->response(false, 'invalid_trans_request');
        }

        $result = $this->walletDetailInterface->store($user['id'], $wallet['id'], $trans_type, !$total_in ? 0 : $total_in, !$total_out ? 0 : $total_out, $params);
        if (!$result['status']) {
            return $this->response(false, 'invalid_payment_detail');
        }

        $wallet_balance = $this->balanceInterface->getBalanceById($user['id'], $wallet['id']);
        $total = ($total_in > 0) ? $total_in : $total_out;
        if (!$wallet_balance) {
            if ($total_out > 0) {
                return $this->response(false, 'insufficient_balance');
            }

            $this->balanceInterface->create([
                'user_id' => $user_id,
                'wallet_id' => $wallet_id,
                'total_in' => $total_in,
                'total_out' => $total_out,
                'balance' => $total
            ]);

            return $this->response(true, 'saved_successfully');
        } else {
            $total_result = $this->walletDetailInterface->getTotalByUserID($user['id'], $wallet['id']);
            $wallet_balance['total_in'] = $total_result['total_in'];
            $wallet_balance['total_out'] = $total_result['total_out'];
            if ($total_in > 0) {
                $wallet_balance['balance'] += $total;
            } else {
                if ($wallet_balance['balance'] < $total) {
                    return $this->response(false, 'insufficient_balance');
                }

                $wallet_balance['balance'] -= $total;
            }

            if ($wallet_balance->save()) {
                return $this->response(true, 'saved_successfully');
            }
        }

        return $this->response(false, 'failed_to_save');
    }

    /**
     * Get balance by ID
     *
     * @param string $member_id
     * @param string $wallet_code
     * @return array|mixed
     */
    public function getBalanceById($member_id, int $wallet_id)
    {
        if (is_string($member_id)) {
            $member_details = $this->interface->findBy('email', $member_id, ['*'], []);

            if (empty($member_details)) {
                return $this->response(false, 'invalid_user');
            }

            $member_id = $member_details['id'];
        }

        $credit = $this->balanceInterface->getBalanceById($member_id, $wallet_id);
        if (empty($credit)) {
            return 0;
        }

        return $credit->balance;
    }

    /**
     * @param string $username
     * @param string|null $wallet_code
     * @param array $params
     * @return array
     */
    public function getBalances(string $username, string $wallet_code = null, array $params = [])
    {
        $wallet = "";
        if (!empty($wallet_code)) {
            $wallet = $this->walletSetupInterface->findBy('wallet_code', $wallet_code, ['id'], []);
            if (!$wallet) {
                return $this->response(false, 'invalid_wallet');
            }
        }

        $user = $this->interface->findBy('username', $username);
        $balances = $this->walletSetupInterface->balances($user['id']);
        return $this->response(true, '', $balances);
    }
}
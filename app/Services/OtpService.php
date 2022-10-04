<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 29/09/2022
 * Time: 3:47 PM
 */

namespace App\Services;

use App\Traits\ResponseAPI;

use Carbon\Carbon;
use Ichtrojan\Otp\Otp;

use App\Interfaces\OneTimePasswordInterface;

class OtpService extends BaseService
{
    use ResponseAPI;

    public function __construct(
        OneTimePasswordInterface $interface
    )
    {
        parent::__construct($interface);
    }

    /**
     * @param string $identifier
     * @return array
     */
    public function generateOTP(string $identifier)
    {
        $date_now = Carbon::now()->addMinutes(5);
        $otp = new Otp;
        $otp_text = $otp->generate($identifier, 6, 15);

        return [
            'otp_code' => $otp_text->token,
            'expired_date' => $date_now
        ];
    }

    /**
     * @param string $identifier
     * @param string $otp_code
     * @return array
     */
    public function validateOtp(string $identifier, string $otp_code)
    {
        $otp = new Otp;
        $validate = $otp->validate($identifier, $otp_code);
        if (!$validate->status) {
            return $this->response(false, str_replace(" ", "_", strtolower($validate->message)));
        }

        return $this->response(true, 'valid_otp');
    }

    /**
     * @param string $identifier
     * @return array
     */
    public function sendOtp(string $identifier)
    {
        $result = $this->interface->validate($identifier);
        if ($result) {
            $validate_date = Carbon::createFromFormat("Y-m-d H:i:s", $result->created_at)->addMinutes($result->validity);
            $date_now = Carbon::now();
            if ($date_now < $validate_date) {
                return $this->response(false, 'otp_already_requested', [
                    'expired_date' => $validate_date,
                ]);
            }
        }

        $otp_msg = $this->generateOTP($identifier);
        return $this->response(true, 'otp_sent_successfully', $otp_msg);
    }
}

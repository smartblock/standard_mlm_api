<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait ResponseAPI
{
    protected $page_length = 10;

    /**
     * @param string $message
     * @param array|null $data
     * @param string $type
     * @param integer $statusCode
     * @param bool $isSuccess
     * @return \Illuminate\Http\JsonResponse
     */
    public function coreResponse(string $message, $data, string $type, int $statusCode, $isSuccess = true)
    {
        if (!$message) return response()->json([
            'code' => 422,
            'error' => false,
            'message' => 'message_is_required',
            'results' => ""
        ], 422);

        if ($isSuccess) {
            return response()->json([
                'code' => $statusCode,
                'error' => false,
                'message' => trans("{$message}"),
                'results' => $data
            ], $statusCode);
        } else {
            return response()->json([
                'code' => $statusCode,
                'error' => true,
                'message' => $message,
                'results' => $data ?? ""
            ], $statusCode);
        }
    }

    public function coreResponseWithCode(string $message, $data, string $type, int $statusCode, int $errorCode, $isSuccess = true)
    {
        if (!$message) return response()->json([
            'code' => 422,
            'error' => false,
            'message' => 'message_is_required',
            'results' => ""
        ], 422);

        if ($isSuccess) {
            return response()->json([
                'code' => $statusCode,
                'error' => false,
                'message' => trans("{$message}"),
                'results' => $data
            ], $statusCode);
        } else {
            return response()->json([
                'code' => $errorCode ?? $statusCode,
                'error' => true,
                'message' => $message,
                'results' => ""
            ], $statusCode);
        }
    }

    /**
     * @param string $message
     * @param $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(string $message, $data = "", $statusCode = 200)
    {
        return $this->coreResponse(trans("message.{$message}"), $data, 'success', $statusCode);
    }

    /**
     * @param string $message
     * @param integer $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $message = null, $statusCode = 500, $data = null)
    {
        if (!$message) {
            $message = 'internal_server_error';
        }

        return $this->coreResponse(trans("message.{$message}"), $data, 'error', $statusCode, false);
    }

    /**
     * @param string|null $message
     * @param int $statusCode
     * @param int $erroCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorWithCode(string $message = null, $statusCode = 500, $errorCode = 500)
    {
        if (!$message) {
            $message = 'internal_server_error';
        }

        return $this->coreResponseWithCode(trans("message.{$message}"), null, 'error', $statusCode, $errorCode, false);
    }

    /**
     * @param string $message
     * @param integer $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function successWithoutTranslation(string $message, $data, $statusCode = 200)
    {
        return $this->coreResponse($message, $data, 'success', $statusCode, true);
    }

    /**
     * @param string $message
     * @param integer $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorWithoutTranslation(string $message, $statusCode = 500)
    {
        return $this->coreResponse($message, null, 'error', $statusCode, false);
    }

    /**
     * @param array $inputs
     * @param array $rules
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseValidator(array $inputs, array $rules, $statusCode = 422)
    {
        $validator = Validator::make($inputs, $rules);

        if($validator->fails()) {
            $errorMsg = '';

            foreach($validator->errors()->all() as $key => $value){
                $errorMsg .= $value;
                $errorMsg .= '';
            }

            return $this->errorWithoutTranslation($errorMsg, $statusCode);
        }
    }

    /**
     * Return response
     *
     * @param bool $status
     * @param string $message
     * @param array|null $data
     * @return array
     */
    public function response(bool $status, string $message, $data = "")
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }

    public function responseTable($data, int $total, $page = 1, $length = 50)
    {
        $total_page = $total / $length;
        $remaining = $total % $length;

        $total_count = ($remaining > 0) ? (int) ++$total_page : $total_page;
        return response()->json([
                'current_page' => $page == 0 ? 1 : (int) $page,
                'page_length' => (int) $length,
                'total_page' => (int) $total_count,
                'total_items' => (int) $total,
                'page_items' => $data,
        ], 200);
    }

    public function responsePaginate(int $length, array $data)
    {
        return [
            'length' => $length,
            'total' => $data['total'] ?? 0,
            'data' => $data['data'] ?? []
        ];
    }
}

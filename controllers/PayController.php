<?php

namespace Controllers;

use Model\App;
use Model\Transactions;

class PayController
{

    public static function pay()
    {
        header('Content-Type: application/json; charset=utf-8');

        $data = json_decode(file_get_contents("php://input"), true);

        $appID  = $data['app_id'];

        $app = App::find($appID);

        if (!$app) {
            echo json_encode(['success' => false, 'message' => 'App not found']);
            return;
        }

        $payment = new Transactions($data);

        $payment->status = 'COMPLETED';
        $payment->created_at = date('Y-m-d H:i:s');
        $payment->save();


        echo json_encode([
            'success' => true
        ]);
    }
}

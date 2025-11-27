<?php

namespace Model;

class Transactions extends ActiveRecord
{
    protected static $table = 'transactions';
    protected static $columnsDB = ['id', 'order_id', 'payer_id', 'app_id', 'user_id', 'amount', 'currency', 'status', 'created_at'];
    public $id;
    public $order_id;
    public $payer_id;
    public $app_id;
    public $user_id;
    public $amount;
    public $currency;
    public $status;
    public $created_at;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->order_id = $args['order_id'] ?? '';
        $this->payer_id = $args['payer_id'] ?? '';
        $this->app_id = $args['app_id'] ?? '';
        $this->user_id = $args['user_id'] ?? '';
        $this->amount = $args['amount'] ?? '';
        $this->currency = $args['currency'] ?? '';
        $this->status = $args['status'] ?? '';
        $this->created_at = $args['created_at'] ?? '';
    }
}

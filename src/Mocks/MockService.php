<?php namespace Userdesk\Subscription\Mocks;

use Userdesk\Subscription\Classes\TransactionResult;
use Userdesk\Subscription\Classes\ProcessorInfo;
use Userdesk\Subscription\Exceptions\TransactionException;

use Userdesk\Subscription\Contracts\Product as SubscriptionProductContract;
use Userdesk\Subscription\Contracts\Consumer as SubscriptionConsumerContract;
use Userdesk\Subscription\Contracts\Service as ProcessorContract;

class MockService implements ProcessorContract {
    private $config = [];

    public function __construct(array $config){
        $this->config = $config;
    }

    /**
     * Create Redirect response to complete cart.
     *
     * @param int $id
     * @param \Userdesk\Subscription\Contracts\Product $product
     * @param \Userdesk\Subscription\Contracts\Consumer $consumer
     * @return \Illuminate\Http\Response|null
     */
    public function complete(int $id, SubscriptionProductContract $product, SubscriptionConsumerContract $consumer = null) {
        $redir_url = 'https://www.google.com';
        return redirect()->away($redir_url);
    }

    /**
     * Return Response
     *
     * @param int $id
     * @param \Userdesk\Subscription\Contracts\Consumer $consumer
     * @param float $total
     * @param string $token
     * @return \Illuminate\Http\Response|null
     */
    public function completeWithoutRedirection(
        int $id, SubscriptionConsumerContract $consumer, float $total = 0, $token = null
    ) {

        $data =
        '{
            "status_code": 202,
            "body": {
                "validationErrors": null,
                "response": {
                    "type": "AuthResponse",
                    "currencyCode": "USD",
                    "lineItems": [
                        {
                        "duration": null,
                        "description": "",
                        "options": [],
                        "price": "10",
                        "quantity": "1",
                        "recurrence": null,
                        "startupFee": null,
                        "productId": "",
                        "tangible": "N",
                        "name": "1",
                        "type": "product"
                        }
                    ],
                    "transactionId": "9093732414081",
                    "billingAddr": {
                        "addrLine1": "Nikole Tesle 11",
                        "addrLine2": null,
                        "city": "Smederevo",
                        "zipCode": "11300",
                        "phoneNumber": null,
                        "phoneExtension": null,
                        "email": "admin@admin.com",
                        "country": "Serbia",
                        "name": "Admin One",
                        "state": "Serbia"
                    },
                    "shippingAddr": {
                        "addrLine1": null,
                        "addrLine2": null,
                        "city": null,
                        "zipCode": null,
                        "phoneNumber": null,
                        "phoneExtension": null,
                        "email": null,
                        "country": null,
                        "name": null,
                        "state": null
                    },
                    "merchantOrderId": "1",
                    "orderNumber": "9093732414072",
                    "responseMsg": "Successfully authorized the provided credit card",
                    "recurrentInstallmentId": null,
                    "responseCode": "APPROVED",
                    "total": "10.00",
                    "errors": null
                },
                "exception": null
            }
        }';

        return json_decode($data, true);
    }

    /**
     * Handle IPN data.
     *
     * @param array $input
     * @return \Userdesk\Subscription\Class\TransactionResult|null
     */
    public function ipn(array $input){
        $item_number    = str_random(12);
        $txn_id         = str_random(12);

        $action = 'test';
        $status = 'mock';
        $amount = array_get($input, 'mc_gross', 0);

        return new TransactionResult($item_number, $txn_id, $amount, $status, $action, $input);
    }

    /**
     * Handle PDT data.
     *
     * @param array $input
     * @return \Userdesk\Subscription\Class\TransactionResult|null
     */
    public function pdt(array $input){
        $item_number            = str_random(12);
        $subscr_id          = str_random(12);

        $action = 'test';
        $status = 'mock';
        $amount = array_get($input, 'mc_gross', 0);


        return new TransactionResult($item_number, $subscr_id, 0, $payment_status, $action, $keys->get());

    }

    /**
     * Return Processor Info.
     *
     * @return \Userdesk\Subscription\Classes\ProcessorInfo|null
     */
    public function info(){
        new ProcessorInfo('Mock', '/vendor/laravel-subscription/logo/mock.png', 'http://www.example.com');
    }
}
<?php
header("Content-Type: application/json");

class Payment {

    public function paymentInformation()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if (!$this->validateMethod($method)) {
            return $this->response(false, 405, 'Method HTTP not allow');
        }
        // data input
        $creditCardNumber = $_POST['credit_card_number'];
        $phone = $_POST['phone'];
        $expireMonth = $_POST['exp_month'];
        $expireYear = $_POST['exp_year'];
        $ccv2 = $_POST['ccv2'];
        // validate all data required
        if (!$this->validateRequire($creditCardNumber, $phone, $expireMonth, $expireYear, $ccv2)) {
            return $this->response(false, 1001, 'Please enter required fields');
        }
        if (!$this->validateCreditCard($creditCardNumber)) {
            return $this->response(false, 1001, 'Credit card format not exact');
        }
        if (!$this->validatePhone($phone)) {
            return $this->response(false, 1001, 'Phone format not exact');
        }
        $expireDate = DateTime::createFromFormat('my', $_POST['exp_month'].$_POST['exp_year']);
        if (!$this->validateExpireDate($expireDate)) {
            return $this->response(false, 1001, 'Expire date not exact');
        }
        if (!$this->validateCCV2($ccv2)) {
            return $this->response(false, 1001, 'CCV2 number not exact');
        }

        return $this->response(true, 0, 'Payment information exact');
    }

    public function validateMethod($method)
    {
        if ($method != 'POST') {

            return false;
        }

        return true;
    }

    public function validateRequire($creditCardNumber, $phone, $expireMonth, $expireYear, $ccv2)
    {
        if (!isset($creditCardNumber) || !isset($phone) || !isset($expireMonth) || !isset($expireYear) || !isset($ccv2)) {
            return false;
        }

        return true;
    }

    public function validateCreditCard($creditCardNumber)
    {
        if (!preg_match('/^[0-9]*$/', $creditCardNumber) ) {
            return false;
        }

        return true;
    }

    public function validatePhone($phone)
    {
        if (strlen($phone) < 9 || strlen($phone) > 10 || !preg_match('/^[0-9]*$/', $phone)) {
            return false;
        }

        return true;
    }

    public function validateExpireDate($expireDate)
    {
        $now = new DateTime();
        if ($expireDate < $now) {
            return false;
        }

        return true;
    }

    public function validateCCV2($ccv2)
    {
        if (strlen($ccv2) != 3) {
            return false;
        }

        return true;
    }

    public function response($valid, $code, $message)
    {
        $result['valid'] = $valid;
        $result['error']['code'] = $code;
        $result['error']['message'] = $message;

        return $result;
    }
}

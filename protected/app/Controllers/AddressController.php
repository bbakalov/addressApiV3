<?php

class AddressController
{
    /**
     * @var Request
     */
    private $requestObj;

    /**
     * @var AddressModel
     */
    private $model;

    /**
     * HTTP code statuses
     * @var array
     */
    public $codeStatus = array(
        200 => 'OK',
        201 => 'Created',
        404 => 'Not Found',
        406 => 'Not Acceptable',
        422 => 'Unprocessable Entity',
        500 => 'Internal server error');

    /**
     * AddressController constructor.
     * @param Request $requestObj
     */
    public function __construct(Request $requestObj)
    {
        $this->requestObj = $requestObj;
        $this->model = new AddressModel();
        if ($requestObj->requestMethod == 'GET' || $this->requestObj->isJson) {
            switch ($requestObj->requestMethod) {
                case 'GET':
                    $this->getAction();
                    break;
                case 'POST':
                    $this->postAction();
                    break;
                case 'PUT':
                    $this->putAction();
                    break;
            }
        } else {
            $response = array('status' => 'Not Acceptable', 'code' => 406, 'message' => 'Content type not : application/json');
            $this->json($response, $response['code']);
        }
    }

    /**
     * Process GET action
     */
    public function getAction()
    {
        if (isset($this->requestObj->urlElements[1]) && $this->requestObj->urlElements[1] == 'addresses' &&
            isset($this->requestObj->urlElements[2])
        ) {
            $response = $this->model->getAddress($this->requestObj->urlElements[2]);
        } elseif (
            isset($this->requestObj->urlElements[1]) && $this->requestObj->urlElements[1] == 'addresses'
        ) {
            $response = $this->model->getAllAddresses();
        } else {
            $response = array('status' => 'Not Found', 'code' => 404);
        }
        $this->json($response['code'], $response);
    }

    /**
     * Process POST action
     */
    public function postAction()
    {
        if (isset($this->requestObj->urlElements[1]) && $this->requestObj->urlElements[1] == 'addresses') {
            $response = $this->model->createAddress($this->requestObj);
        } else {
            $response = array('status' => 'Not Found', 'code' => 404);
        }
        $this->json($response['code'], $response);
    }

    /**
     * Process PUT action
     */
    public function putAction()
    {
        if (isset($this->requestObj->urlElements[1]) && $this->requestObj->urlElements[1] == 'addresses' &&
            isset($this->requestObj->urlElements[2])
        ) {
            $response = $this->model->updateAddress($this->requestObj);
        } else {
            $response = array('status' => 'Not Found', 'code' => 404);
        }
        $this->json($response['code'], $response);
    }

    /**
     * Convert some data into a JSON response.
     * @param int $code The response status code
     * @param array $data The response data
     */
    public function json($code = 200, $data = array())
    {
        header("HTTP/1.1 {$code} {$this->codeStatus[$code]}");
        if (!empty($data)) {
            header('Content-Type: application/json; charset=utf8');
            echo json_encode($data);
        }
    }
}
<?php

class AddressModel extends DbModel
{
    /**
     * Address table columns
     * @var array
     */
    private $tableColumns = array(
        'addressid', 'label', 'street', 'housenumber', 'postalcode', 'city', 'country');

    /**
     * AddressModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all addresses
     * @return array
     */
    public function getAllAddresses()
    {
        $sql = "SELECT " . implode(',', $this->tableColumns) . " FROM address";
        $this->query($sql);
        $addresses = $this->fetchAll();
        $response = array('status' => 'OK', 'code' => 200, 'data' => $addresses);
        return $response;
    }

    /**
     * Get address by ID
     * @param int $addressId
     * @return array
     */
    public function getAddress($addressId)
    {
        $sql = "SELECT " . implode(',', $this->tableColumns) . " FROM address WHERE addressid = :id";
        $this->query($sql);
        $this->bind(':id', $addressId);
        $addressData = $this->fetch();
        if (!is_array($addressData)) {
            $response = array('status' => 'Not Found', 'code' => 404, 'message' => "ID not found");
        } else {
            $response = array('status' => 'OK', 'code' => 200, 'data' => $addressData);
        }
        return $response;
    }

    /**
     * Create new address
     * @param Request $request
     * @return array
     */
    public function createAddress(Request $request)
    {
        $jsonRequestArray = $request->parameters;
        if ($this->checkJsonFormat($jsonRequestArray)) {
            try {
                $sql = 'INSERT INTO address (label, street, housenumber, postalcode, city, country)
            VALUES (:label, :street, :housenumber, :postalcode, :city, :country)';
                $this->query($sql);
                foreach ($this->tableColumns as $column) {
                    if ($column == 'addressid') {
                        continue;
                    }
                    $this->bind(":$column", $jsonRequestArray[$column]);
                }
                $this->execute();

                $lastAddedObject = $this->getAddress($this->lastInsertId());
                $response = array('status' => 'Created', 'code' => 201, 'data' => $lastAddedObject['data']);
            } catch (\Exception $e) {
                $response = array('status' => 'Internal server error', 'code' => 500, 'message' => 'Address wasn\'t created.');
            }
        } else {
            $response = array('status' => 'Unprocessable Entity', 'code' => 422, 'message' => 'JSON isn\'t correct.');
        }
        return $response;
    }

    /**
     * Update existing address
     * @param Request $request
     * @return array
     */
    public function updateAddress(Request $request)
    {
        $addressId = (int)$request->urlElements[2];
        $jsonRequestArray = $request->parameters;
        if ($this->checkJsonFormat($jsonRequestArray)) {
            if ($this->addressExist($addressId)) {
                try {
                    $sql = 'UPDATE address
                        SET `label` = :label,
                            `street` = :street,
                            `housenumber` = :housenumber,
                            `postalcode` = :postalcode,
                            `city` = :postalcode,
                            `country` = :postalcode
                        WHERE `addressid` = :addressid';
                    $this->query($sql);
                    foreach ($this->tableColumns as $column) {
                        if ($column == 'addressid') {
                            $this->bind(':addressid', $addressId);
                            continue;
                        }
                        $this->bind(":$column", $jsonRequestArray[$column]);
                    }
                    $this->execute();

                    $updatedObject = $this->getAddress($addressId);
                    $response = array('status' => 'OK', 'code' => 200, 'data' => $updatedObject['data']);
                } catch (\Exception $e) {
                    $response = array('status' => 'Internal server error', 'code' => 500, 'message' => "ID $addressId wasn't updated.");
                }
            } else {
                $response = array('status' => 'Not Found', 'code' => 404, 'message' => "ID not found");
            }
        } else {
            $response = array('status' => 'Unprocessable Entity', 'code' => 422, 'message' => 'JSON isn\'t correct.');
        }
        return $response;
    }

    /**
     * Check json format on correctness
     * @param array $jsonRequest
     * @return bool
     */
    private function checkJsonFormat($jsonRequest)
    {
        $status = false;
        if (isset(
            $jsonRequest['label'],
            $jsonRequest['street'],
            $jsonRequest['housenumber'],
            $jsonRequest['postalcode'],
            $jsonRequest['city'],
            $jsonRequest['country'])) {
            $status = $this->checkAddressFieldsLength($jsonRequest);
        }
        return $status;
    }

    /**
     * Check fields on correct length
     * @param array $jsonRequest
     * @return bool
     */
    private function checkAddressFieldsLength($jsonRequest)
    {
        $pattern = array(
            'label' => 100,
            'street' => 100,
            'city' => 100,
            'country' => 100,
            'housenumber' => 10,
            'postalcode' => 6
        );

        $result = true;
        foreach ($jsonRequest as $key => $value) {
            $length = $pattern[$key];
            if (strlen($value) > $length) {
                $result = false;
                break;
            }
        }
        return $result;
    }

    /**
     * Check if address id exist
     * @param int $addressId
     * @return bool
     */
    private function addressExist($addressId)
    {
        $result = true;
        $sql = "SELECT addressid FROM address WHERE addressid = :id";
        $this->query($sql);
        $this->bind(':id', $addressId);
        $id = $this->fetchColumn();
        if (empty($id)) {
            $result = false;
        }
        return $result;
    }
}
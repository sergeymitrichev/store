<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 19.01.2018
 * Time: 12:11
 */

namespace App\Store\Models;


use App\Store\Services\AbstractService;

class UsersModel
{
    private $modelData;

    /**
     * UsersModel constructor.
     */
    public function __construct()
    {
        $this->uApi = AbstractService::initial(AbstractService::UAPI);
        $this->retailCrm = AbstractService::initial(AbstractService::RETAILCRM);
        $this->sendPulse = AbstractService::initial(AbstractService::SENDPULSE);

    }


    /**
     * Create new user
     *
     * @param array $user
     */
    public function usersCreate($user)
    {
        try {

            $response = $this->uApi->request->usersCreate($user);

            if (isset($response['success'])) {
                $this->modelData[$this::UAPI] = $response->getSuccess();
            } else if (isset($response['error'])) {
                $this->modelData[$this::UAPI] = $response->getError();
            }

            if ($this->isUserExists($user)) {

                $findedUsers = $this->usersGet($user);

                $this->retailCrm->request->customersNotesCreate(
                    array(
                        'customer'  => array('id'   =>   $findedUsers[0]['id']),
                        'text'      => sprintf("%s Пользователь %s пытался зарегистрироваться по email %s", date("Y-m-d H:i:s"), $user['name'], $user['email'])
                    )
                );

                // TODO Подключить отправку SendPulse о восстановлении пароля

                $this->modelData[$this::RETAILCRM] = array(
                    'error' =>  "Пользователь с таким именем уже зарегистрирован",
                    //'customers'    =>  $findedUsers
                );

            } else {
                $response = $this->retailCrm->request->customersCreate($this->formatUserData($this::RETAILCRM, $user));
                if ($response->isSuccessful()) {
                    $this->modelData[$this::RETAILCRM]['id'] = $response->getId();
                } else {
                    $this->modelData[$this::RETAILCRM]['error'] = $response->getErrorMsg();
                }
            }

        } catch (\APIuCoz\Exception\CurlException $e) {
            echo "Connection error: " . $e->getMessage();
        } catch (\RetailCrm\Exception\CurlException $e) {
            echo "Connection error: " . $e->getMessage();
        }

    }

    /**
     * Check by email is user exists in RetailCRM
     *
     * @param array $user   user data array
     * @return bool
     */
    private function isUserExists(array $user)
    {
        try {
            $response = $this->retailCrm->request->customersList(array('email' => $user['email']));
            if ($response->isSuccessful()) {
                return $response['pagination']['totalCount'] > 0;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return false;
    }

    /**
     * Get user id by email and externalId from RetailCRM
     *
     * @param array $user
     * @return int
     */
    public function usersGet(array $user = [])
    {
        $filters = [];
        if(count($user)) {
            $filters = array('email' => $user['email'], 'externalIds' => array($user['user']));
        }
        try {
            $response = $this->retailCrm->request->customersList($filters);
            if ($response->isSuccessful()) {
                return $response['customers'];
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return 0;
    }

    /**
     * Format array for service methods
     *
     * @param string $serviceName  service name
     * @param array $userData     input data
     * @return mixed
     */
    private function formatUserData($serviceName, $userData) {

        switch ($serviceName)
        {
            case $this::UAPI:
                {
                    $result = $userData;
                    break;
                }
            case $this::RETAILCRM:
                {
                    $result = array(
                        'firstName' => $userData['name'],
                        'email' => $userData['email'],
                        'birthday'  =>  $userData['birthday'],
                        'source'    => $userData['source'],
                        'externalId'    => $userData['user']
                    );
                    if (isset($userData['phone'])) {
                        $result['phones'] = array(
                            array('number'  => $userData['phone'])
                        );
                    }
                    break;
                }
            case $this::SENDPULSE:
                {

                    break;
                }
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function getModelData()
    {
        return $this->modelData;
    }

    /**
     * @param mixed $modelData
     */
    public function setModelData($modelData, $serviceName)
    {
        $this->modelData[$serviceName] = $modelData;
    }




}
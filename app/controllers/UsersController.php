<?php

namespace App\Store\Controllers;



use App\Store\Models\UsersModel;

class UsersController
{
    private $model;

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->model = new UsersModel();
    }

    public function actionCreate(...$params)
    {
        $this->model->usersCreate($params[0]);
        print_r(json_encode($this->model->getModelData()));
    }

    public function actionGet(... $params)
    {
        $this->model->setModelData($this->model->usersGet($params[0]), UsersModel::RETAILCRM);
        print_r(json_encode($this->model->getModelData(), JSON_PRETTY_PRINT));
    }

    public function actionUpdate(... $params)
    {
        $this->model->setModelData($this->model->usersUpdate($params[0]), UsersModel::RETAILCRM);
        print_r(json_encode($this->model->getModelData(), JSON_PRETTY_PRINT));
    }
}
<?php

namespace Module\Fund\Controller\Official;

use Application\Controller\Front;

class DeductionApi extends Front
{

    public function __construct()
    {
        parent::__construct();

        $this->model = $this->model(':Deduction');
    }

    public function shipping()
    {
        $data = json_decode($_POST['data']);
        
        $result = $this->model->shipping($data);
        if ($result === false) {
            $this->response->json(array(
                'code' => 'DeductionApi.ERROR_ACTION',
            ));
        }

        $this->response->json(array(
            'code' => 'OK'
        ));
        // $this->response->json($data);
    }
}
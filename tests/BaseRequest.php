<?php

namespace tests;

trait BaseRequest
{
    public function setUpRequest(string $method = "GET", array $data = [])
    {
        $method = strtoupper($method);
        switch ($method) {
            case 'GET':
                $this->request = new \app\Request();
                $this->app = new \think\App();
                $this->response = $this->app->http->run($this->request);
                break;
            case 'POST':
                $this->request = new \app\Request();
                $this->request->withServer(['REQUEST_METHOD' => 'POST']);
                $this->request->setMethod('POST');
                $this->request->withPost($data);
                $this->app = new \think\App();
                $this->response = $this->app->http->run($this->request);
                break;
            case 'PUT':
                $this->request = new \app\Request();
                $this->request->withHeader(['Content-Type' => 'application/json']);
                $this->request->withServer(['REQUEST_METHOD' => 'PUT']);
                $this->request->setMethod('PUT');
                $this->request->withInput(json_encode($data));
                $this->app = new \think\App();
                $this->response = $this->app->http->run($this->request);
                break;
            default:
                # code...
                break;
        }
    }
}

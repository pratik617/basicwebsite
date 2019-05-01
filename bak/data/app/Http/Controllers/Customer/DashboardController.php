<?php

namespace App\Http\Controllers\customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class DashboardController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
      /*
      dd(Auth()->user());
      $client = new Client();
      $result = $client->post('http://localhost:8000/api/v1/login_cust', [
          'form_params' => [
              'contact_no' => '9904492084',
              'password' => 'pratik@123#',
              'country_code' => '+91',
              'device_type' => 'Android',
              'device_token' => '123456789'
          ]
      ]);
      */
      /*
      $body = $response->getBody();
      //dd(json_decode($body), true);
      $data = json_decode($body);
      dd($data->data->id);
      echo $body;
      exit();
      */
      //$token = $response['token'];

          //echo '<pre>';
          //  dd($response);
          //dd($token);

      /*
      $result = $client->post('your-request-uri', [
          'form_params' => [
              'sample-form-data' => 'value'
          ]
      ]);
      */
      return view('customer.dashboard');
    }
}

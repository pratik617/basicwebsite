<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Model\Exceptions;
use Illuminate\Auth\AuthenticationException;
use Auth;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if($this->isHttpException($exception)){
            $statusCode = $exception->getStatusCode();
            switch ($statusCode) {
                case '400':
                    return redirect()->route('400');
                    break;
                case '404':
                    return redirect()->route('404');
                    break;
                case '500':
                    return redirect()->route('500');
                    break;
                case '503':
                    return redirect()->route('503');
                    break;
                default:
                    return parent::render($request, $exception);
                    break;
            }
        } else{
            if($exception!=""){
                $errorMessage = "";
                $errorMessage .= "<h3> Error : ".$exception->getMessage()."</h3>";
                $errorMessage .= "<h4> Code : ".$exception->getCode()."</h4>";
                $errorMessage .= "<h4> File : ".$exception->getFile()."</h4>";
                $errorMessage .= "<h4> Line : ".$exception->getLine()."</h4>";
                $errorMessage .= "<p> TraceAsString : ".$exception->getTraceAsString()."</p>";

                $Exceptions = New Exceptions;
                $Exceptions->sendException($errorMessage);
            }
            if(Auth::guard('user_api')->check()){
                return json_encode(['status' => 401, 'message' => "unauthenticated" , 'data' => array()]);
                exit;
            }
            if(Auth::guard('driver_api')->check()){
                return json_encode(['status' => 401, 'message' => "unauthenticated" , 'data' => array()]);
                exit;
            }
            return parent::render($request, $exception);   
        }
    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            $response = ['status' => 401, 'message' => "unauthorize" , 'data' => array()];
            return response()->json($response);
        }
        return redirect()->guest('login');
    }
}

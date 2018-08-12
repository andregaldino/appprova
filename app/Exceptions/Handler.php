<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];
    
	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		parent::report($e);
	}
	
	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		$response = app(\EllipseSynergie\ApiResponse\Contracts\Response::class);
		
		// Catch validator exception
		if ($e instanceof ValidationException) {
			return $response->errorWrongArgsValidator($e->getValidator());
		}
		// Catch model not found exception
		if ($e instanceof ModelNotFoundException) {
			return $response->errorNotFound();
		}
		
		// Catch not found
		if ($e instanceof NotFoundHttpException) {
			return $response->errorNotFound('Endpoint not available');
		};
		
		// Catch method not allowed
		if ($e instanceof MethodNotAllowedHttpException) {
			return $response->errorMethodNotAllowed();
		}
		return parent::render($request, $e);
	}
}

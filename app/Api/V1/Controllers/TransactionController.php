<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Transaction;
use App\Http\Requests;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TransactionController extends Controller
{
	use Helpers;
	public function index()
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		$sortField = Input::get('sort_field') ? Input::get('sort_field') : 'datedon';
		$sortType = Input::get('sort_order');
		$sortType = $sortType == 'true'? 'DESC' : 'ASC';
		
		return $currentUser
				->transactions()
				->orderBy($sortField , $sortType)
				->get()
				->toArray();
	}
	
	
	public function show($id)
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		//User can only see his own transactions
		$transaction = $this->currentUser()->transactions()->find( $id );
	
		if(!$transaction)
			throw new NotFoundHttpException;
		return $transaction;
	}
	
	public function store( Requests\StoreTransactionRequest $request)
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		if($transaction = $currentUser->transactions()->create($request->all()))
		{
			$location = 'api/transactions/'.$transaction->id;
			return response()->json($transaction->toArray())->setStatusCode
			(Response::HTTP_CREATED);
		}
		else
			return $this->response->errorBadRequest();
	}
	
	
	public function update(Requests\StoreTransactionRequest $request, $id)
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		//Checking that a user can't update other users expenses by ID
		$transaction = $this->currentUser()->transactions()->find( $id );

		if(!$transaction)
			throw new NotFoundHttpException;
		$fields = $transaction->getFillable();
		$values = $transaction->all();
		foreach ( $fields as $field )
		{
			if (isset ($values[$field]) && ($values[$field]!=''))
			{
				$transaction->$field = $values[$field];
			}
		}
		if($transaction->save())
			return $this->response->noContent();
		else
			return $this->response->errorBadRequest();
	}
	public function destroy($id)
	{
		$currentUser = JWTAuth::parseToken()->authenticate();
		//Checking that a user can't update other users expenses by ID
		$transaction = $this->currentUser()->transactions()->find( $id );
		$transaction->delete();
		return $this->response->noContent();
	}
	
	private function currentUser() {
		return JWTAuth::parseToken()->authenticate();
	}
}
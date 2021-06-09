<?php
namespace App\Http\Controllers\v1;
use Laravel\Lumen\Routing\Controller as BaseController;
use \Illuminate\Http\Request;
use App\Utils\FuncResponse;
use App\Models\User;
use App\Models\BankUser;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use DB;

class BankController extends BaseController{

  use FuncResponse;

  const USER_ID = 'user_id';
  const TGL_DELETE = 'tgl_delete';
  const BANK ='bank';
  const REKENING ='rekening';
  const NAMA ='nama';

  const INFO = 'info';

  public function show(Request $request){

    $user = $request->auth;
    if(!$user){
      // user tidak ada
      return $this->responseValidation([self::INFO => 'User tidak ada']);
    }

    // get UserID
    $userID = $user->id;
    $bankUser=BankUser::where(self::USER_ID,$userID)
                            ->whereNull(self::TGL_DELETE)->get();

    $count = $bankUser->count();
    if($count>0){
      return $this->responseDataCount($bankUser,$count);
    }else{
      return $this->responseDataNotFound([self::INFO => 'Bank user user tidak ada']);
    }

  }

  public function create(Request $request){

    $user = $request->auth;
    if(!$user){
      // user tidak ada
      return $this->responseValidation([self::INFO => 'User tidak ada']);
    }

    $userID = $user->id;

    $this->validate($request, [
          self::BANK => 'required',
          self::REKENING => 'required|unique:bank_user',
          self::NAMA => 'required',

    ],[
          self::BANK.".required" => self::BANK." tidak boleh kosong",
          self::REKENING.".required" => self::REKENING." tidak boleh kosong",
          self::NAMA.".required" => self::NAMA." tidak boleh kosong",
          self::REKENING.".unique" => self::REKENING." sudah terdaftar",
    ]);

    try{

      $formParams = $request->all();
      $formParams[self::USER_ID] = $userID;

      BankUser::create($formParams);

      unset($formParams[self::USER_ID]);

      return $this->responseInfo($formParams);

    }catch(\Exception $e){
      DB::rollback();
      return $this->responseValidation([self::INFO => 'Bank gagal di tambah']);
    }

  }


  public function delete(Request $request,$uuid){
    $user = $request->auth;
    if(!$user){
      // user tidak ada
      return $this->responseValidation([self::INFO => 'User tidak ada']);
    }

    $userID = $user->id;

    try{
      BankUser::where(self::USER_ID,$userID)->delete();
      return $this->responseInfo([self::INFO => 'Bank berhasil di hapus']);
    }catch(\Exception $e){
      DB::rollback();
      return $this->responseValidation([self::INFO => 'Bank gagal di hapus']);
    }
  }

}

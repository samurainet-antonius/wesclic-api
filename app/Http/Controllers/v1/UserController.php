<?php
namespace App\Http\Controllers\v1;
use Laravel\Lumen\Routing\Controller as BaseController;
use \Illuminate\Http\Request;
use App\Utils\FuncResponse;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use DB;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController{

  use FuncResponse;

  // info
  const INFO = 'info';

  // table
  CONST USER = 'user';

  // kolom
  const ID = 'id';
  const UUID = 'uuid';
  const NAMA = 'nama';
  const EMAIL = 'email';
  const JENIS_KELAMIN = 'jenis_kelamin';
  const TEMPAT_LAHIR = 'tempat_lahir';
  const TANGGAL_LAHIR = 'tanggal_lahir';
  const ALAMAT = 'alamat';
  const PASSWORD = 'password';
  const PASSWORD_LAMA = 'password_lama';
  const NOTELP = 'notelp';
  const KODE_AKTIVASI = 'kode_aktif';
  const FLAG_AKTIF = 'flag_aktif';
  const FOTO = 'foto';

  // setting email
  const EMAIL_PENDAFTARAN = 'email_pendaftaran';
  const VIEW = 'view';
  const SUBJECT = 'subject';

  // jwt
  const AUTHORIZATION = 'Authorization';

  public function profile(Request $request){

    $user = $request->auth;
    if(!$user){
      // user tidak ada
      return $this->responseValidation([self::INFO => 'Profile user tidak ada']);
    }

    return $this->responseInfo($user);
  }


  public function update(Request $request){

    $user = $request->auth;
    if(!$user){
      // user tidak ada
      return $this->responseValidation([self::INFO => 'User tidak ada']);
    }

    $userID = $user->id;
    $userDetail = User::where(self::ID,$userID)->first();

    $formParams = $request->all();

    $this->validate($request, [
      self::EMAIL => 'required|email',
      self::NAMA => 'required',
      self::JENIS_KELAMIN => 'required',
      self::NOTELP => 'required',
      self::TEMPAT_LAHIR => 'required',
      self::TANGGAL_LAHIR => 'required',
      self::ALAMAT => 'required',
    ],[
      self::EMAIL.".required" => self::EMAIL." tidak boleh kosong",
      self::NOTELP.".required" => self::NOTELP." tidak boleh kosong",
      self::EMAIL.".email" => self::EMAIL." beriskan email",
      self::NAMA.".required" => self::NAMA." tidak boleh kosong",
      self::JENIS_KELAMIN.".required" => self::JENIS_KELAMIN." tidak boleh kosong",
      self::TEMPAT_LAHIR.".required" => self::TEMPAT_LAHIR." tidak boleh kosong",
      self::TANGGAL_LAHIR.".required" => self::TANGGAL_LAHIR." tidak boleh kosong",
      self::ALAMAT.".required" => self::ALAMAT." tidak boleh kosong",
    ]);

    try {

        if($request->hasFile(self::FOTO)){
          $file = $request->file(self::FOTO);

          // validate foto
            $this->validate($request, [
              self::FOTO => 'image|max:1896'
          ],[
            self::FOTO.".max" => self::FOTO." maximal 1mb",
            self::FOTO.".image" => self::FOTO." hanya boleh berformat jpg,png,bmp,gif dan svg",
          ]);

          // getOriginalExtension
          $formParams[self::FOTO] = time().'.'.$file->getClientOriginalExtension();


          if (File::exists(self::USER.'/'.$userDetail->foto)) {
              File::delete(self::USER.'/'.$userDetail->foto);
          }

          $file->move(self::USER,$formParams[self::FOTO]);
        }else{
          $formParams[self::FOTO] = $userDetail->foto;
        }

        User::where(self::ID,$userID)->update($formParams);

        $serverDokumen = $this->serverApi();
        $formParams[self::FOTO] = $serverDokumen.self::USER.'/'.$formParams[self::FOTO];

        return $this->responseInfo($formParams);


    } catch (\Exception $e) {
        DB::rollback();
        return $this->responseValidation([self::INFO => 'profile gagal di ubah']);
    }


  }

  public function changePassword(Request $request){

    $user = $request->auth;
    if(!$user){
      // tidak ada
      return $this->responseValidation([self::INFO => 'User tidak ada']);
    }

    $userID = $user->id;

    $this->validate($request, [
      self::PASSWORD_LAMA => 'required',
      self::PASSWORD => 'required',
    ],[
      self::PASSWORD_LAMA.".required" => self::PASSWORD_LAMA." tidak boleh kosong",
      self::PASSWORD.".required" => self::PASSWORD." baru tidak boleh kosong",
    ]);

    try{

      $password_lama = $request->input(self::PASSWORD_LAMA);

      $userDetail = User::where(self::ID,$userID)->first();

      if(Hash::check($request->input(self::PASSWORD_LAMA), $userDetail->password)){

        $password = app('hash')->make($request->input(self::PASSWORD));

        User::where(self::ID,$userID)->update([
          self::PASSWORD => $password
      ]);

        return $this->responseInfo([self::INFO => 'Password berhasil di ubah']);

      }else{
        return $this->responseValidation([self::INFO => 'Password lama salah']);
      }

    } catch (\Exception $e) {
        DB::rollback();
        return $this->responseValidation([self::INFO => 'Password gagal di ubah']);
    }


  }

}

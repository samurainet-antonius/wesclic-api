<?php
namespace App\Http\Controllers\v1;
use Laravel\Lumen\Routing\Controller as BaseController;
use \Illuminate\Http\Request;
use App\Utils\FuncResponse;
use App\Models\User;
use App\Models\TokoUser;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use DB;

class TokoController extends BaseController{

  use FuncResponse;

  const UUID = 'uuid';
  const USER_ID = 'user_id';
  const TGL_DELETE = 'tgl_delete';
  const NAMA ='nama';
  const KATEGORI ='kategori';
  const JENIS ='jenis';
  const EMAIL ='email';
  const NOHP ='nohp';
  const TAGLINE ='tagline';
  const PROVINSI ='provinsi';
  const KOTA ='kota';
  const KECAMATAN ='kecamatan';
  const ALAMAT ='alamat';
  const FLAG_AKTIF ='flag_aktif';
  const LOGO ='logo';
  const NUMBER_UNIQUE = 'number_unique';

  const INFO = 'info';

  public function show(Request $request){

    $user = $request->auth;
    if(!$user){
      // user tidak ada
      return $this->responseValidation([self::INFO => 'User tidak ada']);
    }

    // get UserID
    $userID = $user->id;
    $tokoUser=TokoUser::where(self::USER_ID,$userID)
                            ->whereNull(self::TGL_DELETE)->get();

    $count = $tokoUser->count();
    if($count>0){
      return $this->responseDataCount($tokoUser,$count);
    }else{
      return $this->responseDataNotFound([self::INFO => 'Toko user user tidak ada']);
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
        self::NAMA => 'required',
        self::KATEGORI => 'required',
        self::JENIS => 'required',
        self::EMAIL => 'required|email|unique:toko_user',
        self::NOHP => 'required|unique:toko_user',
        self::TAGLINE => 'required',
        self::PROVINSI => 'required',
        self::KOTA => 'required',
        self::KECAMATAN => 'required',
        self::ALAMAT => 'required',
        self::LOGO => 'required|image|max:1896',
    ],[
        self::NAMA.".required" => self::NAMA." toko tidak boleh kosong",
        self::KATEGORI.".required" => self::KATEGORI." toko tidak boleh kosong",
        self::JENIS.".required" => self::JENIS." toko tidak boleh kosong",
        self::EMAIL.".required" => self::EMAIL." toko tidak boleh kosong",
        self::EMAIL.".email" => self::EMAIL." beriskan email",
        self::NOHP.".required" => self::NOHP." toko tidak boleh kosong",
        self::TAGLINE.".required" => self::TAGLINE." toko tidak boleh kosong",
        self::PROVINSI.".required" => self::PROVINSI." toko tidak boleh kosong",
        self::KOTA.".required" => self::KOTA." toko tidak boleh kosong",
        self::KECAMATAN.".required" => self::KECAMATAN." toko tidak boleh kosong",
        self::ALAMAT.".required" => self::ALAMAT." toko tidak boleh kosong",
        self::LOGO.".required" => self::LOGO." toko tidak boleh kosong",
        self::NOHP.".unique" => self::NOHP." toko sudah terdaftar",
        self::EMAIL.".unique" => self::EMAIL." toko sudah terdaftar",
        self::LOGO.".image" => self::LOGO." hanya boleh berformat jpg,png,bmp,gif dan svg",
        self::LOGO.".max" => self::LOGO." maximal 1mb",
    ]);

    try{

      $formParams = $request->all();
      $formParams[self::USER_ID] = $userID;
      $formParams[self::NUMBER_UNIQUE] = date("Ymdhis");

      $file = $request->file(self::LOGO);
      $formParams[self::LOGO] = time()."_".$file->getClientOriginalName();
      $tujuan_upload = 'logo';

      $file->move('logo',$formParams[self::LOGO]);

      TokoUser::create($formParams);

      unset($formParams[self::USER_ID]);

      $serverDokumen = $this->serverApi();
      $formParams[self::LOGO] = $serverDokumen.$tujuan_upload.$formParams[self::LOGO];

      return $this->responseInfo($formParams);

    }catch(\Exception $e){
      DB::rollback();
      return $this->responseValidation([self::INFO => 'Toko gagal di tambah']);
    }

  }

  public function edit(Request $request,$uuid){

    $user = $request->auth;
    if(!$user){
      // user tidak ada
      return $this->responseValidation([self::INFO => 'User tidak ada']);
    }

    $userID = $user->id;

    $tokoDetail = TokoUser::where(self::UUID,$uuid)->first();

    $formParams = $request->all();

    $this->validate($request, [
        self::NAMA => 'required',
        self::KATEGORI => 'required',
        self::JENIS => 'required',
        self::EMAIL => 'required|unique:toko_user,email,'.$tokoDetail->id,
        self::NOHP => 'required|unique:toko_user,nohp,'.$tokoDetail->id,
        self::TAGLINE => 'required',
        self::PROVINSI => 'required',
        self::KOTA => 'required',
        self::KECAMATAN => 'required',
        self::ALAMAT => 'required',
        self::LOGO => 'required|image|max:1896',

    ],[
        self::NAMA.".required" => self::NAMA." toko tidak boleh kosong",
        self::KATEGORI.".required" => self::KATEGORI." toko tidak boleh kosong",
        self::JENIS.".required" => self::JENIS." toko tidak boleh kosong",
        self::EMAIL.".required" => self::EMAIL." toko tidak boleh kosong",
        self::NOHP.".required" => self::NOHP." toko tidak boleh kosong",
        self::TAGLINE.".required" => self::TAGLINE." toko tidak boleh kosong",
        self::PROVINSI.".required" => self::PROVINSI." toko tidak boleh kosong",
        self::KOTA.".required" => self::KOTA." toko tidak boleh kosong",
        self::KECAMATAN.".required" => self::KECAMATAN." toko tidak boleh kosong",
        self::ALAMAT.".required" => self::ALAMAT." toko tidak boleh kosong",
        self::LOGO.".required" => self::LOGO." toko tidak boleh kosong",
        self::NOHP.".unique" => self::NOHP." toko sudah terdaftar",
        self::EMAIL.".unique" => self::EMAIL." toko sudah terdaftar",
        self::LOGO.".image" => self::LOGO." hanya boleh berformat jpg,png,bmp,gif dan svg",
        self::LOGO.".max" => self::LOGO." maximal 1mb",
    ]);

    try{

      $formParams = $request->all();

      if($request->hasFile(self::LOGO)){

        if (File::exists('logo/'.$tokoDetail->logo)) {
            File::delete('logo/'.$tokoDetail->logo);
        }

        $file = $request->file(self::LOGO);
        $formParams[self::LOGO] = time()."_".$file->getClientOriginalName();
        $tujuan_upload = 'logo';

        $file->move('logo',$formParams[self::LOGO]);
      }else{
        $formParams[self::LOGO] = $tokoDetail->logo;
      }

      TokoUser::where(self::UUID,$uuid)->update($formParams);

      $serverDokumen = $this->serverApi();
      $formParams[self::LOGO] = $serverDokumen.$tujuan_upload.'/'.$formParams[self::LOGO];

      return $this->responseInfo($formParams);

    }catch(\Exception $e){
      DB::rollback();
      return $this->responseValidation([self::INFO => 'Toko gagal di ubah']);
    }

  }


  public function delete(Request $request){
    $user = $request->auth;
    if(!$user){
      // user tidak ada
      return $this->responseValidation([self::INFO => 'User tidak ada']);
    }

    $userID = $user->id;

    try{

      $uuid = $request->input(self::UUID);

      if(is_array($uuid)){
        BankUser::whereIn(self::USER_ID,$userID)->delete();
        return $this->responseInfo([self::INFO => 'Toko berhasil di hapus']);
      }else{
          return $this->responseValidation([self::INFO => 'Toko gagal di hapus']);
      }
    }catch(\Exception $e){
      DB::rollback();
      return $this->responseValidation([self::INFO => 'Toko gagal di hapus']);
    }
  }

}

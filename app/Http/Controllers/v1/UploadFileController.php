<?php

namespace App\Http\Controllers\v1;
use Laravel\Lumen\Routing\Controller as BaseController;
use \Illuminate\Http\Request;
use App\Utils\FuncResponse;
use App\Models\DokumenUser;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\File;

class UploadFileController extends BaseController {
    use FuncResponse;

    // info
    const INFO = 'info';

    // table
    CONST DOKUMEN_USER = 'dokumen_user';

    //kolom
    const TGL_DELETE = 'tgl_delete';
    const UUID = 'uuid';
    const USER_ID='user_id';
    const FILE='file';
    const NAME_FILE='name_file';
    const FLAG_AKTIF = 'flag_aktif';
    const FLAG_VERIFIED = 'flag_verified';
    // jwt
    const AUTHORIZATION = 'Authorization';

    public function show(Request $request)
    {

      $user = $request->auth;
      if(!$user){
        // user tidak ada
        return $this->responseValidation([self::INFO => 'User tidak ada']);
      }

        // get UserID
        $userID = $user->id;
        $dokumenUser=DokumenUser::where(self::USER_ID,$userID)
                                ->whereNull(self::TGL_DELETE)->get();

        $count = $dokumenUser->count();
        if($count>0){
          return $this->responseDataCount($dokumenUser,$count);
        }else{
          return $this->responseDataNotFound([self::INFO => 'Document user user tidak ada']);
        }
    }

    public function create(Request $request){

      $user = $request->auth;
      if(!$user){
        // user tidak ada
        return $this->responseValidation([self::INFO => 'User tidak ada']);
      }

      $userID = $user->id;


      $dataFile = $request->all();
      //validate data
      $this->validate($request, [
			      'file' => 'required|image|max:1896',
            'name_file' => 'required',

      ],[
            self::FILE.".required" => self::FILE." File tidak boleh kosong",
            self::FILE.".image" => self::FILE." hanya boleh berformat jpg,png,bmp,gif dan svg",
            self::FILE.".max" => self::FILE." maximal 1mb",
            self::NAME_FILE.".required" => self::NAME_FILE." Name File tidak boleh kosong",
      ]);


      try {


        $file = $request->file(self::FILE);
        $nama_file= $request->input(self::NAME_FILE);
        $nama_fileku = time()."_".$file->getClientOriginalName();
        $tujuan_upload = 'fileku';

        $formParams = [
          self::USER_ID => $user->id,
          self::FILE => $nama_fileku,
          self::NAME_FILE => $nama_file
        ];

        $checkFile = $this->checkFile($nama_file,$userID);

        if($checkFile){

          File::delete('fileku/'.$checkFile->file);

          $file->move($tujuan_upload,$nama_fileku);

          DokumenUser::where([
            [self::USER_ID,$userID],
            [self::NAME_FILE,$checkFile->name_file],
          ])->delete();

          DokumenUser::create($formParams);


        }else{

          $file->move($tujuan_upload,$nama_fileku);


          DokumenUser::create($formParams);

        }

        $serverDokumen = $this->serverApi();
        $formParams[self::FILE] = $serverDokumen.$tujuan_upload.$nama_fileku;

        return $this->responseInfo($formParams);

      } catch (\Exception $e) {
          DB::rollback();
          return $this->responseValidation([self::INFO => 'file gagal di upload']);
      }
    }

    private function checkFile($name_file,$userID){

      $dokumenUser=DokumenUser::where([
        [self::USER_ID,$userID],
        [self::NAME_FILE,$name_file],
      ])->whereNull(self::TGL_DELETE)->first();

      return $dokumenUser;
    }
}

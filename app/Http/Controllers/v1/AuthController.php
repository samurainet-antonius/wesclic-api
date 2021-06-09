<?php
namespace App\Http\Controllers\v1;
use Laravel\Lumen\Routing\Controller as BaseController;
use \Illuminate\Http\Request;
use App\Utils\FuncResponse;
use App\Models\User;
use App\Events\EmailRegistration;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Queue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;


class AuthController extends BaseController{

  use FuncResponse;

  // info
  const INFO = 'info';

  // table
  CONST USER = 'user';

  // kolom
  const UUID = 'uuid';
  const NAMA = 'nama';
  const EMAIL = 'email';
  const PASSWORD = 'password';
  const NOTELP = 'notelp';
  const KODE_AKTIVASI = 'kode_aktif';
  const FLAG_AKTIF = 'flag_aktif';

  // setting email
  const EMAIL_PENDAFTARAN = 'email_pendaftaran';
  const EMAIL_RESET_PASSWORD = 'email_reset_password';
  const VIEW = 'view';
  const SUBJECT = 'subject';
  const URL = 'url';

  // jwt
  const TOKEN = 'token';
  const TOKEN_TYPE = 'token_type';
  const EXPIRES_IN = 'expires_in';

  public function login(Request $request){

        $this->validate($request, [
          self::EMAIL => 'required|email',
          self::PASSWORD => 'required',
        ],[
          self::EMAIL.".required" => self::EMAIL." tidak boleh kosong",
          self::EMAIL.".email" => self::EMAIL." beriskan email",
          self::PASSWORD.".required" => self::PASSWORD." tidak boleh kosong",
        ]);

        $user = User::where([
          [self::EMAIL, $request->input(self::EMAIL)],
          [self::FLAG_AKTIF,1]
          ])->first();

        if(!$user){
          return $this->responseValidation([self::INFO => 'email tidak terdaftar.']);
        }


        if (Hash::check($request->input(self::PASSWORD), $user->password)) {
            return $this->respondWithToken($user);
        }

        // gagal login
        return $this->responseValidation([self::INFO => 'email atau password salah.']);
  }

  // registration akun
  public function registration(Request $request){

    // request all paramas
    $dataUser = $request->all();
    $url = $dataUser[self::URL];
    unset($dataUser[self::URL]);

    // check user email actived
    $checkUser = $this->userEmailActived($dataUser[self::EMAIL]);
    if(!$checkUser){
      $errors = $this->validate($request,[
        self::NAMA => 'required',
        self::EMAIL => 'required|email',
        self::PASSWORD => 'required',
        self::NOTELP => 'required',
      ],[
        self::NAMA.".required" => self::NAMA." tidak boleh kosong",
        self::EMAIL.".required" => self::EMAIL." tidak boleh kosong",
        self::EMAIL.".email" => self::EMAIL." beriskan email",
        self::PASSWORD.".required" => self::PASSWORD." tidak boleh kosong",
        self::NOTELP.".required" => self::NOTELP." tidak boleh kosong",
      ]);
    }else{
      $errors = $this->validate($request,[
        self::NAMA => 'required',
        self::EMAIL => 'required|email|unique:user',
        self::PASSWORD => 'required',
        self::NOTELP => 'required',
      ],[
        self::NAMA.".required" => self::NAMA." tidak boleh kosong.",
        self::EMAIL.".required" => self::EMAIL." tidak boleh kosong.",
        self::EMAIL.".unique" => self::EMAIL." sudah terdaftar.",
        self::PASSWORD.".required" => self::PASSWORD." tidak boleh kosong.",
        self::NOTELP.".required" => self::NOTELP." tidak boleh kosong.",
      ]);
    }


    // hash password
    $dataUser[self::PASSWORD] = app('hash')->make($request->input(self::PASSWORD));
    $dataUser[self::KODE_AKTIVASI] = date("Ymdhis");
    // save into model user
    $user = User::firstOrNew($dataUser);

     // logic save data user
    if(!$user->save()){

      // failed messsage
      return $this->responseValidation([self::INFO => 'gagal melakukan pendaftaran.']);
    }


    // setting view and password not hash
    $dataUser[self::VIEW] = self::EMAIL_PENDAFTARAN;
    // $dataUser[self::URL] = $url;
    $dataUser[self::URL] = 'https://wesclic-ui.vercel.app/activation';
    $dataUser[self::SUBJECT] = 'Pendaftaran Akun Wesclic Sales';
    $dataUser[self::PASSWORD] = $request->input(self::PASSWORD);

    // call Jobs SendEmail for send mail using method ansyc using 1 seconds time
    Mail::send(['html' => $dataUser[self::VIEW]], $dataUser, function($message) use($dataUser) {
      $message->to($dataUser[self::EMAIL], $dataUser[self::NAMA])->subject($dataUser[self::SUBJECT]);
    });
    // $date = Carbon::now()->addSeconds(1);
    // Queue::later($date, new SendEmail($dataUser));


    unset($dataUser[self::PASSWORD]);
    unset($dataUser[self::VIEW]);
    unset($dataUser[self::NOTELP]);
    unset($dataUser[self::SUBJECT]);

    // success message
    return $this->responseInfo($dataUser);

  }


  // activation akun
  public function activation(Request $request){

    $kode = $request->input(self::TOKEN);
    $email = $request->input(self::EMAIL);

    // check kode aktivasi
    $userDetail = User::where(self::EMAIL,$email)
                      ->where(self::FLAG_AKTIF, 1)
                      ->first();

    if(!$userDetail){

      // check aktif akun
      $user = User::where(self::EMAIL,$email)
                  ->where(self::KODE_AKTIVASI, $kode)
                  ->first();

      if($user){

        $dataUser[self::FLAG_AKTIF] = 1;

        User::where(self::UUID,$userDetail->uuid)->update($dataUser);

        // message success activation
        return $this->responseInfo([self::INFO => 'Akun berhasil di aktifkan']);
      }

      // message token failed activation
      return $this->responseValidation([self::INFO => 'Kode aktivasi tidak terdaftar']);
    }
    // message failed activation
    return $this->responseValidation([self::INFO => 'Akun sudah aktif']);

  }


  // reset password akun
  public function requestPassword(Request $request){

    $email = $request->input(self::EMAIL);

    // check user by email and flag_aktif
    $user = User::where([
      [self::EMAIL,$email],
      [self::FLAG_AKTIF,1],
    ])->first()->toArray();

    // check user not empty
    if(!empty($user)){

      $token = date("Ymdhis");

      $user[self::VIEW] = self::EMAIL_RESET_PASSWORD;
      $user[self::SUBJECT] = 'Reset password akun Wesclic Sales';
      $user[self::KODE_AKTIVASI] = $token;
      // $user[self::URL] = $url;
      $user[self::URL] = 'https://wesclic-ui.vercel.app/reset-password';


      User::where([
        [self::EMAIL,$email],
        [self::FLAG_AKTIF,1],
        ])->update([
        self::KODE_AKTIVASI => $token
      ]);

      // call Jobs SendEmail for send mail using method ansyc using 1 seconds time
      Mail::send(['html' => $user[self::VIEW]], $user, function($message) use($user) {
        $message->to($user[self::EMAIL], $user[self::NAMA])->subject($user[self::SUBJECT]);
      });

      return $this->responseInfo([self::INFO => 'Reset password berhasil, silahkan cek email']);

    }


    return $this->responseValidation([self::INFO => 'Reset password gagal']);
  }

  public function checkTokenResetPassword(Request $request){

    $email = $request->input(self::EMAIL);
    $token = $request->input(self::TOKEN);

    $checkUser = User::where([
      [self::EMAIL,$email],
      [self::FLAG_AKTIF,1],
      [self::KODE_AKTIVASI,$token]
    ])->first();

    if($checkUser){
        return $this->responseInfo([self::INFO => 'token terdaftar']);
    }

    return $this->responseValidation([self::INFO => 'token tidak terdaftar']);


  }


  public function resetPassword(Request $request){

    $password = app('hash')->make($request->input(self::PASSWORD));
    $email = $request->input(self::EMAIL);


    User::where([
      [self::EMAIL,$email],
      [self::FLAG_AKTIF,1],
    ])->update(
      [self::PASSWORD => $password],
      [self::KODE_AKTIVASI => null]);

    return $this->responseInfo([self::INFO => 'Password berhasil disimpan']);

  }



  // function other

  // check email and actived
  private function userEmailActived($email){

    $userDetail = User::where([
      [self::EMAIL,$email],
      [self::FLAG_AKTIF,1],
    ])->first();

    return $userDetail;
  }


  // jwt funciton
  protected function respondWithToken(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        $token =  JWT::encode($payload, env('JWT_SECRET'));
        return $this->responseInfo([
            self::TOKEN => $token,
        ]);
    }

}

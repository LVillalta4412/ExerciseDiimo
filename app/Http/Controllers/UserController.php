<?php
namespace App\Http\Controllers;

    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\Mail;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use App\Mail\RecoverPassword;

class UserController extends Controller
{

     //Aqui mostramos los datos de todos los usuarios almacenados en nuestra tabla user de nuestra base de datos.
    public function index()
    {
        return User::all();
    }

     //Obtenemos un registro especifico que se encuentra almacenado
     //en nuestra base de datos por medio del id.
    public function show($id)
    {
        return User::where('id',$id)->get();
    }
 //Obtener token para verificar si aun existe
    public function SearchToken($token)
    {
        return User::where('remember_token',$token)->get();
    }

    //Esta función es la que realiza la autenticacion con nuestro email y password.
    public function authenticate(Request $request)
    {
        //realiza la busqueda para solamente encontrar el email y password
        //y asi realizar la validacion para que genere un token de lo contrario
        //mostrara que las credenciales no son válidas.
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
       //retorno del token obtenido al colocar las credenciales de forma correcta.
        return response()->json(compact('token'));
    }

    //Esta función es para eliminar el token al momento de cerrar sesión.
    public function logout( $token ) {

        try {
            JWTAuth::parseToken()->invalidate( $token );

            return response()->json( [
                'error'   => false,
                'message' => trans( 'auth.logged_out' )
            ] );
        } catch ( TokenExpiredException $exception ) {
            return response()->json( [
                'error'   => true,
                'message' => trans( 'auth.token.expired' )

            ], 401 );
        } catch ( TokenInvalidException $exception ) {
            return response()->json( [
                'error'   => true,
                'message' => trans( 'auth.token.invalid' )
            ], 401 );

        } catch ( JWTException $exception ) {
            return response()->json( [
                'error'   => true,
                'message' => trans( 'auth.token.missing' )
            ], 500 );
        }
    }

    //Verifica el usuario que se encuentra validado y asi tambien verifica
    //si el token aun es valido o ya ha sido eliminado.
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
    }
   //Registrar el usuario con sus respectivas validaciones
    public function register(Request $request)
        {

                $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'password' => 'required|string|min:6',
                'phone' => 'required|numeric|min:6'
            ]);

            if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
            ]);
              //Aqui lo que hace es obtener el usuario y validarlo para asi asignarle un token.
            $token = JWTAuth::fromUser($user);
             //y dicho token se retorna para ser mostrado y verificar cual es el token asignado.
            return response()->json(compact('user','token'),201);
        }


        //Modificar los registros almacenados en nuestra BD y d eigual manera tiene sus respectivas validaciones
        //para que los datos sean modificados de manera exitosa.
        public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|numeric|min:6'
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::findOrFail($id);
        $user -> name = $request->name;
        $user -> username = $request->username;
        $user -> email = $request->email;
        $user -> phone = $request->phone;
        $user -> date_of_birth = $request->date_of_birth;
        $user ->update();
        return $user;
    }

    //Esta parte es para modificar solamente el password
    public function updatePassword(Request $request, $token)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|max:255'
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }
        $id = User::where('remember_token',$token)->value('id');
        $user = User::findOrFail($id);
        $user -> password = Hash::make($request->password);
        $user ->update();
        return $user;
    }

    //De igua manera contiene su función para eliminar algun usuario.
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json('User deleted successfully');
    }

   // Función para recuperar contraseña
    public function recover_password(request $request){
        $email = User::where('email',$request->email)->value('email');
        if (!$email){
            return response()->json('Email not exists', 400);
        }else{
            $remember_token = User::where('email',$request->email)->value('remember_token');
            if(!$remember_token){
                $token = Str::random(25);
                $id = User::where('email',$request->email)->value('id');
                $user = User::findOrFail($id);
                $user -> remember_token = $token;
                $user -> created_at = now();
                $user ->update();
                Mail::to($request->email)->send(new RecoverPassword($request->email, $token));
                return response()->json('Send successfully');
            }else{
                $token = User::where('email',$request->email)->value('remember_token');
                Mail::to($request->email)->send(new RecoverPassword($request->email, $token));
                return response()->json('Send successfully');
            }
        }


    }

    // Función para modificar campo de recovery password en 15 minutos.
    public function time_recover_password($token){
        $email = User::where('remember_token',$token)->value('email');
        $created_at = User::where('email',$email)->value('created_at');
        if (now() > $created_at->addMinutes(15)) {
            $id = User::where('email',$email)->value('id');
            $user = User::findOrFail($id);
            $user -> remember_token = null;
            $user ->update();
            return response(['message' => trans('passwords.token_is_expire')], 200);
        }else{
            return response(['message' => trans('passwords.code_has_not_expired_yet')], 422);
        }

    }

}

<?php

namespace App\Http\Middleware;

use Closure;

use App\Appuser;

use Response;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!empty(($request->header('accesstoken')))){
            $userDetail = Appuser::where(['accesstoken' => $request->header('accesstoken')])->first();
            if(empty($userDetail)){
                $Response = [
                  'message'  => "Invalid accesstoken",
                ];
                return Response::json( $Response , 401);
            }else{
                if($userDetail['status']==1){
                   return response()->json(["message"=>"You are block by admin."],401); 
                }
				
				/*if($userDetail['status']==2){
                   return response()->json(["message"=>"Your document has been rejected by Adminstrator. Please Re-upload."],401); 
                }*/
                $request['userDetail'] = $userDetail;
                return $next($request);
            }
            
        } else {
            $response['message'] = "Key accesstoken required";
            return response()->json($response,401);
        }
    }
}

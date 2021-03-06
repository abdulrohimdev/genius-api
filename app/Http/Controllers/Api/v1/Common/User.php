<?php

namespace App\Http\Controllers\Api\v1\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Common\UserModel;
use App\Models\Common\UserRoleModel;

use Package\Common\Message;
use Package\Common\VerifyAccount;
use Carbon\Carbon;
use DB;

use Package\Utility\ExcelToArray;

class User extends Controller
{
    public $message;
    public $verify;

    function __construct(Request $r){
        $this->message = new Message();
        $this->verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
    }

    /*
        method : delete
        url api : /api/v1/user-delete/{id}
        with header apikey and secretkey
        function: delete
    */

    public function delete(Request $r){
        $verify_userid = $this->verify->first();
        if($verify_userid){
            try{
                $user = UserModel::where(['username' => $r->input('username')]);
                if($user->count()){
                    $secretkey_user = $user->first();
                    $user_role = UserRoleModel::whereIn('secretkey',[$secretkey_user->secret_key]);
                    $user_role->delete();
                    if($user->delete()){
                        return Response()->json([
                            'status' => true,
                            'message'=> $this->message->get(4,[
                                'use' => true,
                                'lang' => $verify_userid->language])
                        ]);
                    }
                    return Response()->json([
                        'status' => false,
                        'message' => $this->message->get(5,[
                                    'use' => true,
                                    'lang' => $verify_userid->language])]);
                }
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->get(6,[
                                'use' => true,
                                'lang' => $verify_userid->language])]);
            }
            catch(\Exception $e){
                return Response()->json([
                    'status' => false,
                    'message' => $e->getMessage()]);
            }
        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);
    }

    /*
        method : post
        url api : /api/v1/user-create
        with header apikey and secretkey
        function: store
    */

    public function store(Request $r){
        $verify_userid = $this->verify->first();
        if($verify_userid){
            try{
                $user = UserModel::where(['username' => $r->input('username')]);
                if($user->count() > 0){
                    return Response()->json([
                        'status' => false,
                        'message' => $this->message->get(7,[
                                    'use' => true,
                                    'lang' => $verify_userid->language])]);
                }else{

                        if($r->input('operational') === 'Y'){
                            $CreateData = [
                                'api_key' => Str::random(16),
                                'secret_key' =>Str::random(50),
                                'username' => $r->input('username'),
                                'password' => Hash::make($r->input('password')),
                                'fullname' => $r->input('fullname'),
                                'operational' => $r->input('operational'),
                                'company_code' => $r->input('company_code'),
                                'employee_id' => $r->input('employee_id'),
                                'email' => $r->input('email'),
                                'phone' => $r->input('phone'),
                                'photo' => "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zzzzzzzzzzxkZe/88999d/5T/wD3f3u/76f8888888888888888pv/f/AP8A/wD/AP8A5S/f9f8A/ffo8888888888888888867r/wD/AOd/ev5T/J9vPvdfTTzzzzzzzzzzzzzzzz+PPPNvfPOP5Ss8ce8PYcffzzzzzzzzzzzzzzzxcOMMMMMOIP5T/fvP9vf/ADc88888888888888887T/3/AM929/8A5T77/fbr6P8Aq8888888888888884077z/3/AO++/lL39l//AP8A/ve0888888888888884X7vz/AN9+1+/lO884+u0080F/PPPPPPPPPPPPPC40+4869088/lL7zz70/wAc/PzzzzzzzzzzzzzzzwY8u8M8cccf5T//AP8A/wB//wDf588888888888888882f+/wD/AP7/APv+U7/f/wD/ANd//wA888888888888888o8f7//AP8A/wD3v+U7T2b7z33zgc88888888888888+Tj7zzzTzzj+U733Hf8A1z468/vPPPPPPPPPPPLsCw6wx01y0w/lL/33/wA/88889Hzzzzzzzzzzzw48+c8888888/5T/wD/AP8A9/8Af/v/AOFvPPPPPPPPPP8A/wD/AP8A/wD/AP8A/wD/AL/lP9/9/wD/AP8A/wDfvczzzzzzzzzy9zv/AH77/wD/APfffv5T99+P+fdPPsMMP7zzzzzzzwJcNcMPsNMOeMOP5TsNNf8A7XXjjDDTH88888888jzDDzDjDDTTjDT+U/8A/wD/APT/AP7/AP8Av/R888888842v7v/AH//AP8A/fff/wDlO/8Af/P/AH//AP8Av/8Ar88888888377/wD/APf/AP8A/wDf/wD+U/8A99//APv/AP8A69/1vPPPPPPPOv8Afdfv/f8A337Xr+UvPPHvPPXLPLPAc88888888qGLLHLPDCHHPPD+UvvPX/zfTOEX/wDPPPPPPPPPPPzEyQKzwy1wxx/lP/8A/Tn6R/zzzzzzzzzzzzzzzzzzyz0/TLf9/wD+U7wf88888888888888888888888888888/v0/wDkL3PPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPCwvmvPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPDcP/PPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPAv/EAB8RAQADAAIDAQEBAAAAAAAAAAEAETAxQBAhUCBBUf/aAAgBAwEBPxC5bLZbLZbLZbLZbLZbLZbLZbLZbLZbLZbLZbLZbLZbLZbLZbLZbLZbLfB+MfjH4x+MejaXl5aI9E7CYD90MZRPBoHSof60AIzlmcwuBW1Gh7QlxKzOR67k4nPS4ZnE6XHM5HHcnJeujyzOT6Lmcj09BetDkFw3F6Ht3nM9u85nLn3DkdBetDnRDVpFvQ6P+arbqdBqHvNVsdVXrJai3sdl6xfvuuOPLc9e87nbjjy3O3DHludQvI+tzjTL+INKlPG2Z/FQXjSUdWkrLyn9HwQA71RlK8mf0+ElxK8B7v4iX4Hxf7P/xAAfEQEAAgMAAwEBAQAAAAAAAAABABEwMVEhQEFQECD/2gAIAQIBAT8QBWpTkpyU5KclOSnJTkpyU5KclOSnJTkpyU5KclOSnJTkpyU5KclOSnJTkpyU5KclOSnJTkpyU5KclOSnISGvxhr8Ya/GGvxhr0Uvv+KAP30RrMMLdRV3/oRqHcBp/ANS9W4xrItRXWRTuFHmFq3GNYwNxVms8uQaxU+D0E0lC8YxLbforZjGsLr3YawuvS3xjWIU16I24xrEK9EKxhrEPF+geYFYxrELK9AXkDWJQLYovjOR4yDWJaPRVl4xrF8ejqxjWI+L9A8wxjWJLKiV4z3N5BrJEprK8ACjINZPNeUUrKNZBZUSvDjsbzDWW3yYhaoFFZhrMKwjVs41m3w6s41m2MOjONZtsOrONZleE7zhlUC4+cNTWcawoPsTFfIt9yCmoFDogkEdYhr/AAobiEeCLRV36oX2D+wP2Afv+hr+Cai3vCmopuAfJ/RqWeD8JFZAFn8UFfiXI7i2/ij4n//EACsQAAEDAwIDCQEBAQAAAAAAAAEAEVEhQPAxQWFxoRAwUGCBkbHB0SDh8f/aAAgBAQABPxAAGAIDm8lQpSFKUpClKQhSlIUhTkKUpzkKchSlOUpCnKUpSlKUpClIc5ClKUpTlKUpSlKQOwggI3B1jJ8lZyVhJ8lZyVjJ8lZyVjJ8lZyVlJ7l7x+5ezft2/h+zOSsJPkrOSsJPkrOSsJPkrOSsJPhz3mclZSfJWclYSfAdEmRR7qsgfU6LWC5f6ob5oD6QWvNL/3SJtBcjRfxw/SFqYcHftV0FwYeqc2okOHuFoWPdGxRAEltgN7TOSsJKN7zmICg9UewYcw+6aTzgkAAYBh3TBCfWSK+6fSWg0fqdQa71dNUBJgII2P9AxRvYc0cXDl5thaZyVjJvG2UjcqB6ocOE9A/UCArQAMOwd+0DHoI9U4Djyz7ooAGwdg0UTQAOUYHCKpfiYIFrJ5lVFuiW3O59LTOSsJPYLgS0bAHJQQxnatNPUoVFBoAGFqWcWBoq7hv+1x0EgVPqiqPkGsElExD15thaZyVlJuWqLGroP1VOz1dSmudZVrrPoPtF7MkuQuSSUITg1Q3/wAWmclYSbgYcEdd+X9QO+mAF2UMoxBttnmiXLmpNpnJWEm24N0BygKqIHr/AIQkAAAwA2vBBfO8bkko7OIXJJ1tc5Kwk2ryBqGh5oCEARsCa9MzVqCHQQbAiv4/iNCxpaZyVhJtBNTskJOwQADN4CIIgMwjaXraZyVhJ7d7HT9D3NOjeBBNcDgdkXFCGNnnJWUmzOG1AB6oAwMAGHgbLBgCbka/dnnJWEmzeTf5kPA+cQXQWeclYSbPoHi+s5Kwk2fCj5vBOUAHSzzkrCTZ8EE9UD4HzwD2pZ5yVhJtOMQ6eBEwcmi42XuNnnJWUm0Z71Mn7+BV9Ygm56C0zkrCTaVidCBHOn0PAmEDUI9BVPZ5yVhJtHGLAx/I+PAmVOoXM/8ALTOSsJNofWAw9ChkTgBHgB0TMlw8cgpaZyVhJtamLkJ+mnRvAKh1K3M0FrnJCwk2rlmjDmP8+PAHzV9IH+/H87f0e6zkhZSUbQwCY8t+iMAI4IcXxTPF+jf69rnJWEnvqdw1ifpmnS+DvLHMdEauSXPfH+85Kwk2pSomxI1KAjwBBjsdb5kJx2MogACp92Ei1zkrKTaFAZmL1Br4AYAcEMQotOBy2tM5Kwk2nAsCBgEeA0bsLpaZyVhJtKXcD4FrYa2mclZSbOi4EEFECBG48B9GHTspZZyVlJsh21c1AHmKWW3fHRQiQOT070f3nJCyk2rrmtPka/L+AV2rR5mgtc5IWEm1dgwn89kNL8IKVL09s4WuclYSUbQEgggsRoUByGgcf9vgzA3JW4DoIGwtc5Kwk2wuL0Ew/UFOfcXgHCQANSUOsiUN/wCW2clYSbd/iOrGh9EIPmSzB9roJoxcTt1XCOigPtb5yVlJuKiML3bIXLr/ADoLjOSsJNwJmYjgwgv1IYIO9wWZiiPBanLzQNhcZyVhJuXkjew/1+IWxWoh7DYXOclZSbkEk4QQXBCCIw0jjPrbGOoFBJhGXc7n+W7GsWWckLKTdDK5KkgQOATgi0JYVWtI9OKbrOSFhJu22qq26OSGlm80hQ2RzsT3WclZSUbs0zESEWRLrr0U1REQkqkne7zkrCTePE4fBsqHFJ0vM5Kwk/3vavySR7goWL/FvuTeZyVlJvDdZOhpYsKBZ73mckLKTeExo+YIaWB0RvwG+B/D2b9g7c5Kwk3leNWxOi+L8C8zkrKTc17R4p8yGlgVzATpeZyVhJvGbBH2BQsWWuj0JvM5IWUm8hgnucNk+MNCS6H6Nke6zkhZSUbt8RsOQ/7ZUSHLB9Nej3mclYSbtnLAOU/Ir7g1NkM1wEHktQs12zkrCTdAElg5K14iCAE7BCyKMB0WrGI39vhEEFiGI1Bus5Kwk2xZiHEK1EAkQ6aolCAcPsKZSRn8mQFuSAQtGdDW5AFOJBs/stX2fuCqoGRfaIMc4gts5Kwk2IBJoC621yQw6qpjeZJVcjAoTDWSDuqAMAAgXwAGIBCdS7O4O6Krl+bFUQUFyWwiQZ0RBBYggwbHOSsJPelm5gFVYgEtIuvofcVqKz+SEtyQCbwVkyAMA4BWiGy0tU+h9wVRAk/sqTzQHe5yVhJ7gAksASUwuQdyGdUzkfDuTcSUChMdRIO6oAwACB4gQNCARC3USDuieSXh2Koh+NS2UDcGdEBJgIMHuM5Kyk/zPaGGiCgEhc+6YSVn6ghLckAtB3I8QGsA4BTiSs/Uh5AeQfxcEMB/OckLKT2gEkAByaABAHGsA1POEGiHQA3kA9OU7clNBEYP/GclYSew2ApgBugAAN7cv75DIQAYtYuocUaFj2ZyVhJQBIAByUIIBB7Y5+RSE2oio9+I7M5KwkpmpBpO5lDyMQ4W6C0G6FnJTwwIxQHqgeABgB4QfAAXuFkZkNIcMUzeoDg4t5JKb17od9L/2Q==",
                                'locked' => 'No',
                                'language' => 'id',
                            ];
                        }
                        else{
                            $CreateData = [
                                'api_key' => Str::random(16),
                                'secret_key' =>Str::random(50),
                                'username' => $r->input('username'),
                                'password' => Hash::make($r->input('password')),
                                'fullname' => $r->input('fullname'),
                                'operational' => $r->input('operational'),
                                'email' => $r->input('email'),
                                'phone' => $r->input('phone'),
                                'locked' => 'No',
                                'photo' => "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zzzzzzzzzzxkZe/88999d/5T/wD3f3u/76f8888888888888888pv/f/AP8A/wD/AP8A5S/f9f8A/ffo8888888888888888867r/wD/AOd/ev5T/J9vPvdfTTzzzzzzzzzzzzzzzz+PPPNvfPOP5Ss8ce8PYcffzzzzzzzzzzzzzzzxcOMMMMMOIP5T/fvP9vf/ADc88888888888888887T/3/AM929/8A5T77/fbr6P8Aq8888888888888884077z/3/AO++/lL39l//AP8A/ve0888888888888884X7vz/AN9+1+/lO884+u0080F/PPPPPPPPPPPPPC40+4869088/lL7zz70/wAc/PzzzzzzzzzzzzzzzwY8u8M8cccf5T//AP8A/wB//wDf588888888888888882f+/wD/AP7/APv+U7/f/wD/ANd//wA888888888888888o8f7//AP8A/wD3v+U7T2b7z33zgc88888888888888+Tj7zzzTzzj+U733Hf8A1z468/vPPPPPPPPPPPLsCw6wx01y0w/lL/33/wA/88889Hzzzzzzzzzzzw48+c8888888/5T/wD/AP8A9/8Af/v/AOFvPPPPPPPPPP8A/wD/AP8A/wD/AP8A/wD/AL/lP9/9/wD/AP8A/wDfvczzzzzzzzzy9zv/AH77/wD/APfffv5T99+P+fdPPsMMP7zzzzzzzwJcNcMPsNMOeMOP5TsNNf8A7XXjjDDTH88888888jzDDzDjDDTTjDT+U/8A/wD/APT/AP7/AP8Av/R888888842v7v/AH//AP8A/fff/wDlO/8Af/P/AH//AP8Av/8Ar88888888377/wD/APf/AP8A/wDf/wD+U/8A99//APv/AP8A69/1vPPPPPPPOv8Afdfv/f8A337Xr+UvPPHvPPXLPLPAc88888888qGLLHLPDCHHPPD+UvvPX/zfTOEX/wDPPPPPPPPPPPzEyQKzwy1wxx/lP/8A/Tn6R/zzzzzzzzzzzzzzzzzzyz0/TLf9/wD+U7wf88888888888888888888888888888/v0/wDkL3PPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPCwvmvPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPDcP/PPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPP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",
                                'company_code' => $r->input('company_code'),
                                'employee_id' => $r->input('employee_id'),
                                'language' => 'id',
                            ];
                        }
                        $user = UserModel::create($CreateData);
                        if($user){
                            if(count($r->input('role')) > 0){
                                $RoleData = [];
                                $now = Carbon::now();
                                foreach($r->input('role') as $role){
                                    array_push($RoleData,[
                                        'secretkey' => $CreateData['secret_key'],
                                        'role_code' => $role['role_code'],
                                        'created_at'=> $now,
                                        'updated_at'=> $now,
                                    ]);
                                }
                                $create = UserRoleModel::insert($RoleData);
                                if($create){
                                    return Response()->json([
                                        'status' => true,
                                        'message' => $this->message->get(8,[
                                                    'use' => true,
                                                    'lang' => $verify_userid->language])]);
                                }
                                return Response()->json([
                                    'status' => true,
                                    'message' => $this->message->get(20,[
                                                'use' => true,
                                                'lang' => $verify_userid->language])]);
                            }
                            else{
                                return Response()->json([
                                    'status' => true,
                                    'message' => $this->message->get(8,[
                                                'use' => true,
                                                'lang' => $verify_userid->language])]);
                            }
                        }
                        return Response()->json([
                            'status' => false,
                            'message' => $this->message->get(9,[
                                        'use' => true,
                                        'lang' => $verify_userid->language])]);
                }
            }
            catch(\Exception $e){
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->error($e->getMessage(),[
                                'use' => true,
                                'lang' => $verify_userid->language])]);
            }
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                        'use' => true,
                        'lang' => 'en'])]);
    }

    public function AccountList(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $account = UserModel::where('username','like',"%".$r->input('username')."%")
                       ->orWhere('fullname','like',"%".$r->input('username')."%");
            return Response()->json([
                'status' => true,
                'data' => $account->paginate(10)
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function AccountPersonal(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $account = UserModel::where('username',$r->input('username'));
            if($account->count() > 0){
                $account = $account->first();
                $user_role= DB::table('user_roles')
                                ->join('roles','roles.role_code','user_roles.role_code')
                                ->select('roles.role_code','roles.role_description')
                               ->where('user_roles.secretkey',$account->secret_key)->get();
                return Response()->json([
                    'status' => true,
                    'data' => $account,
                    'role' => $user_role
                ]);
            }
            else{
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->get(21,[
                        'use' => true,
                        'lang' => 'en'])]);
            }
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function update(Request $r){
        $verify_userid = $this->verify->first();
        if($verify_userid){
            try{
                $user = UserModel::where(['username' => $r->input('username')]);
                $UserData = $user->first();
                if($user->count() > 0){
                    if($r->input('operational') === 'Y'){
                        $update = [
                            'fullname' => $r->input('fullname'),
                            'operational' => $r->input('operational'),
                            'email' => $r->input('email'),
                            'phone' => $r->input('phone'),
                            'company_code' => $r->input('company_code'),
                            'employee_id' => $r->input('employee_id'),

                        ];
                    }
                    else{
                        $update = [
                            'fullname' => $r->input('fullname'),
                            'operational' => $r->input('operational'),
                            'email' => $r->input('email'),
                            'phone' => $r->input('phone'),
                            'company_code' => $r->input('company_code'),
                            'employee_id' => $r->input('employee_id'),
                        ];
                    }
                    $user = $user->update($update);
                    if($user){
                        $UpdateRole = $this->UpdateRole($r->input('role'),$UserData->secret_key);
                        if($UpdateRole){
                            return Response()->json([
                                'status' => true,
                                'data' => $UpdateRole,
                                'message' => $this->message->get(13,[
                                            'use' => true,
                                            'lang' => $verify_userid->language])]);
                        }
                        return Response()->json([
                            'status' => false,
                            'message' => $this->message->get(12,[
                                        'use' => true,
                                        'lang' => $verify_userid->language])]);
                    }
                    return Response()->json([
                        'status' => false,
                        'message' => $this->message->get(12,[
                                    'use' => true,
                                    'lang' => $verify_userid->language])]);


                }
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->get(21,[
                                'use' => true,
                                'lang' => $verify_userid->language])]);

            }
            catch(\Exception $e){
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->error($e->getMessage(),[
                                'use' => true,
                                'lang' => $verify_userid->language])]);
            }
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                        'use' => true,
                        'lang' => 'en'])]);
    }


    public function UpdateRole($data=[],$secretkey){
        $UserRole = UserRoleModel::where('secretkey',$secretkey);
        $now = Carbon::now();
        $role = [];
        if($UserRole->count() > 0){
            $delete = $UserRole->delete();
        }
        foreach($data as $item){
            array_push($role,[
                'secretkey' => $secretkey,
                'role_code' => $item['role_code'],
                'created_at'=> $now,
                'updated_at'=> $now,
            ]);
        }
        $create = UserRoleModel::insert($role);
        return $role;
    }

    public function UpdatePasswordUser(Request $r){
        $verify_userid = $this->verify->first();
        if($verify_userid){
            try{
                $user = UserModel::where(['username' => $r->input('username')]);
                $UserData = $user->first();
                if($user->count() > 0){
                    $UserData->password = Hash::make($r->input('password'));
                    if($UserData->save()){
                        return Response()->json([
                            'status' => true,
                            'message' => $this->message->get(13,[
                                        'use' => true,
                                        'lang' => $verify_userid->language])]);
                    }
                    return Response()->json([
                        'status' => false,
                        'message' => $this->message->get(12,[
                                    'use' => true,
                                    'lang' => $verify_userid->language])]);
                }
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->get(1,[
                                'use' => true,
                                'lang' => $verify_userid->language])]);
            }
            catch(\Exception $e){
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->error($e->getMessage(),[
                                'use' => true,
                                'lang' => $verify_userid->language])]);
            }
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                        'use' => true,
                        'lang' => 'en'])]);
    }

    public function importUser(Request $r){
        $verify_userid = $this->verify->first();
        if($verify_userid){
            $path = $r->file('data')->getPathName();
            $param = $r->all();
            $ExcelToArray = new ExcelToArray();
            $data = $ExcelToArray->file($param['data'],$path);
            $temp = [];
            $role = [];
            $now = Carbon::now();
            // if(count($data) < 250){
                for($i=1;$i < count($data); $i++){
                    array_push($temp,[
                            'api_key' => Str::random(16),
                            'secret_key' =>Str::random(50),
                            'username' => $data[$i][0],
                            'password' => Hash::make($data[$i][1]),
                            'fullname' => $data[$i][2],
                            'operational' => 'N',
                            'email' => $data[$i][3],
                            'phone' => $data[$i][4],
                            'locked' => 'No',
                            // 'photo' => "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zzzzzzzzzzxkZe/88999d/5T/wD3f3u/76f8888888888888888pv/f/AP8A/wD/AP8A5S/f9f8A/ffo8888888888888888867r/wD/AOd/ev5T/J9vPvdfTTzzzzzzzzzzzzzzzz+PPPNvfPOP5Ss8ce8PYcffzzzzzzzzzzzzzzzxcOMMMMMOIP5T/fvP9vf/ADc88888888888888887T/3/AM929/8A5T77/fbr6P8Aq8888888888888884077z/3/AO++/lL39l//AP8A/ve0888888888888884X7vz/AN9+1+/lO884+u0080F/PPPPPPPPPPPPPC40+4869088/lL7zz70/wAc/PzzzzzzzzzzzzzzzwY8u8M8cccf5T//AP8A/wB//wDf588888888888888882f+/wD/AP7/APv+U7/f/wD/ANd//wA888888888888888o8f7//AP8A/wD3v+U7T2b7z33zgc88888888888888+Tj7zzzTzzj+U733Hf8A1z468/vPPPPPPPPPPPLsCw6wx01y0w/lL/33/wA/88889Hzzzzzzzzzzzw48+c8888888/5T/wD/AP8A9/8Af/v/AOFvPPPPPPPPPP8A/wD/AP8A/wD/AP8A/wD/AL/lP9/9/wD/AP8A/wDfvczzzzzzzzzy9zv/AH77/wD/APfffv5T99+P+fdPPsMMP7zzzzzzzwJcNcMPsNMOeMOP5TsNNf8A7XXjjDDTH88888888jzDDzDjDDTTjDT+U/8A/wD/APT/AP7/AP8Av/R888888842v7v/AH//AP8A/fff/wDlO/8Af/P/AH//AP8Av/8Ar88888888377/wD/APf/AP8A/wDf/wD+U/8A99//APv/AP8A69/1vPPPPPPPOv8Afdfv/f8A337Xr+UvPPHvPPXLPLPAc88888888qGLLHLPDCHHPPD+UvvPX/zfTOEX/wDPPPPPPPPPPPzEyQKzwy1wxx/lP/8A/Tn6R/zzzzzzzzzzzzzzzzzzyz0/TLf9/wD+U7wf88888888888888888888888888888/v0/wDkL3PPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPCwvmvPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPDcP/PPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPP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",
                            'company_code' => $data[$i][6],
                            'employee_id' => $data[$i][7],
                            'language' => $data[$i][8],
                            'created_at' => $now,
                            'updated_at' => $now,
                    ]);
                }

                foreach($temp as $item){
                    array_push($role,[
                        'secretkey' => $item['secret_key'],
                        'role_code' => 'general-ess',
                        'created_at' => $now,
                        'updated_at' => $now,

                    ]);
                }
                try{
                    $user = UserModel::insert($temp);
                    $rolemodel = UserRoleModel::insert($role);
                    return Response()->json([
                        'status' => true,
                        'message' => 'Import Successfully',
                    ]);

                }
                catch(\Exception $e){
                    return Response()->json([
                        'status' => false,
                        'message' => $e->getMessage(),
                    ]);
                }
            // }
            // else{
            //     return Response()->json([
            //         'status' => false,
            //         'message' => 'Max data 240 Baris',
            //     ]);
            // }

        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);
    }

}

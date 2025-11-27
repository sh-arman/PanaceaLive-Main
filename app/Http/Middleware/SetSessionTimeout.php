<?php

namespace Panacea\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;
use Illuminate\Support\Facades\Session;
use Panacea\User;
use Panacea\Company;
use Panacea\Log;
use Panacea\InjectableUser;

class SetSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $sess = Session::get('timestamp');
        if ($sess) {
            if ((int)$sess + 1800 < time()) {

                $user = Sentinel::findById(Session::get('id'));
                $company = $this->getUserCompany($user);
                if ($company != "panacea") {
                    $data['company'] = Company::where('display_name', $company)->first();
                    Log::create([
                        'company_id' => $data['company']['id'],
                        'company_admin_id' => Sentinel::getUser()->id,
                        'action' => 4
                    ]);
                }

                Session::flush();
                Sentinel::logout();
                session()->flash('message', 'You have been inactive for more than 30 minutes. Please login again');
                return redirect()->to('/');
            } else {
                Session::put('timestamp', time());

            }
        }


        return $next($request);
    }


    /**
     * Get logged in company info
     */
    public function getUserCompany($user)
    {
        $user_email = User::select('email')->where('id', $user->id)->first();
        $ijectable_user=InjectableUser::select('id')->where('user_id',$user->id)->first();
        $explodedEmail = explode('@', $user_email->email);
        $domain = array_pop($explodedEmail);
        if ($domain == 'panacea.live' || $domain == 'panacealive.xyz') {
            return 'panacea';
        } elseif ($domain == 'gmail.com' || $domain == 'hotmail.com' || $domain == 'yahoo.com') {
            return 'invalid';
        } elseif ($domain == 'kumarika.com') { // this will return kumarika mail as a domain to redirect kumarika panel
            return 'kumarika';
        }  else {
            if(!empty($ijectable_user))
            {
                return 'renata_injectable';
            }
            $company_name = Company::select('display_name')->where('contact_email',
                'like', '%' . $domain)->first();
            return $company_name->display_name;
        }
    }
}

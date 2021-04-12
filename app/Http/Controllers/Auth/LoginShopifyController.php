<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Socialite;
use App\Store;
use App\UserProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class LoginShopifyController extends Controller {

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(Request $request) {

        $this->validate($request, [
            'domain' => 'string|required'
        ]);
        $config = new \SocialiteProviders\Manager\Config(
                env('SHOPIFY_KEY'), env('SHOPIFY_SECRET'), env('SHOPIFY_REDIRECT'), ['subdomain' => $request->get('domain')]
        );

        return Socialite::with('shopify')
                        ->setConfig($config)
                        ->scopes(['read_products','read_orders', 'read_fulfillments', 'write_fulfillments', 'read_shipping', 'write_shipping'])
                        ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback() {
        $shopifyUser = Socialite::driver('shopify')->user();
        $user = User::where('username', $shopifyUser->nickname)->first();
        if (!isset($user->username)) {
            // Create user
            $user = User::firstOrCreate([
                        'name' => $shopifyUser->name,
                        'username' => $shopifyUser->nickname,
                        'email' => $shopifyUser->email,
                        'role' => '2',
                        'password' => $shopifyUser->nickname . "prnit",
            ]);

            //send email to new signup store
            $data = [];
            $data['receiver_name'] = "Hello " . $shopifyUser->name;
            $data['receiver_message'] = "Thanks for signup with dropship app. Now please complete your profile by adding your requested shipping product.";
            $data['sender_name'] = "DSA Team";

            $email_data['message'] = $data;
            $email_data['subject'] = 'Welcome :: ' . $shopifyUser->name;
            $email_data['layout'] = 'emails.sendemail';
            try {
                Mail::to($shopifyUser->email)->send(new SendMailable($email_data));
            } catch (\Exception $e) {
                // Never reached
            }

            //send email to admin
            $data = [];
            $data['receiver_name'] = "Hello " . "Admin";
            $data['receiver_message'] = "A new store sign up for your application";
            $data['sender_name'] = "DSA Team";

            $email_data['message'] = $data;
            $email_data['subject'] = 'New User Signup :: ' . $shopifyUser->nickname;
            $email_data['layout'] = 'emails.sendemail';
            try {
                Mail::to(env('ADMIN_MAIL_ADDRESS', 'info@dropshipagent.co'))->send(new SendMailable($email_data));
            } catch (\Exception $e) {
                // Never reached
            }

            // Store the OAuth Identity
            UserProvider::firstOrCreate([
                'user_id' => $user->id,
                'provider' => 'shopify',
                'provider_user_id' => $shopifyUser->id,
                'provider_token' => $shopifyUser->token,
            ]);

            // Create shop
            $store = Store::firstOrCreate([
                        'name' => $shopifyUser->name,
                        'domain' => $shopifyUser->nickname,
            ]);

            // Attach store to user
            $store->users()->syncWithoutDetaching([$user->id]);

            // Setup uninstall webhook
            dispatch(new \App\Jobs\RegisterUninstallShopifyWebhook($store->domain, $shopifyUser->token, $store));
        } else if ($user->is_deleted == 1) {
            $user->is_deleted = 0;
            if ($user->save()) {
                // Store the OAuth Identity
                UserProvider::firstOrCreate([
                    'user_id' => $user->id,
                    'provider' => 'shopify',
                    'provider_user_id' => $shopifyUser->id,
                    'provider_token' => $shopifyUser->token,
                ]);

                // Create shop
                $store = Store::firstOrCreate([
                            'name' => $shopifyUser->name,
                            'domain' => $shopifyUser->nickname,
                ]);

                // Attach store to user
                $store->users()->syncWithoutDetaching([$user->id]);

                // Setup uninstall webhook
                dispatch(new \App\Jobs\RegisterUninstallShopifyWebhook($store->domain, $shopifyUser->token, $store));
            }
        }


        // Login with Laravel's Authentication system
        Auth::login($user, true);

        //header('Location: https://' . $user->username . '/admin/apps');
        //return redirect(env('APP_URL')); exit;
        return redirect('/home');
    }

}

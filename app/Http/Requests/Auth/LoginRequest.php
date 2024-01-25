<?php

namespace App\Http\Requests\Auth;

use App\Models\KelasSantri;
use App\Models\Santri;
use App\Models\User;
use App\Models\Walisantri;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // return [
        //     'email' => ['required', 'string', 'email'],
        //     'password' => ['required', 'string'],
        // ];

        return [
            // 'username' => ['required', 'string'],
            // 'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $kk = User::where('username', $this->kk)->count();
        // dd($kk);
        if($kk !== 0){
            // $this->ensureIsNotRateLimited();
            $user = User::where('username', $this->kk)->first();
            $password = 'vnPgyLdEKcLdeqPjnXHfHicgEXd3kRujdnWjTAbxpUe9tbvVLEa7VwefU7cLYYaNfxokn9jw9fqyp97gbMtw9TakscwmqhFCanj4jLVHTNXowzJzvPH9LeMeJXmpTJAkqu47pap9daPCLezahf9n3mTAwnbAyYjqpnprMvhmaJxncNsswqwhhFqvpvpUafpmismJEjtEMo9HYATyWars9qR9mKEtfwaez3M9NmmJHLb97mHhTLzARRaLaehg3TM';
            $updatepassword = Hash::make($password);
            if ( !$user || !Hash::check($this->password,$user->password))
            {
                // // RateLimiter::hit($this->throttleKey());
                // throw ValidationException::withMessages([
                //     'username' => trans('auth.failed'),
                // ]);
                User::where('username', $this->kk)->update(['password'=>$updatepassword]);
            }
            Auth::login($user, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());

        } else {
            $this->ensureIsNotRateLimited();
            $user = User::where('username', $this->username)->first();
            $password = 'vnPgyLdEKcLdeqPjnXHfHicgEXd3kRujdnWjTAbxpUe9tbvVLEa7VwefU7cLYYaNfxokn9jw9fqyp97gbMtw9TakscwmqhFCanj4jLVHTNXowzJzvPH9LeMeJXmpTJAkqu47pap9daPCLezahf9n3mTAwnbAyYjqpnprMvhmaJxncNsswqwhhFqvpvpUafpmismJEjtEMo9HYATyWars9qR9mKEtfwaez3M9NmmJHLb97mHhTLzARRaLaehg3TM';
            if ($user !== null) {
                if (!$user || !Hash::check($password, $user->password)) {
                    // RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages([
                        'username' => trans('auth.failed'),
                    ]);
                }
                Auth::login($user, $this->boolean('remember'));
                RateLimiter::clear($this->throttleKey());
            } elseif ($user === null) {
                // dd($user);

                $user = User::create([
                    'name' => $this->name,
                    'username' => $this->username,
                    'password' => $password,
                    'panelrole' => 'psb',
                ]);
                Walisantri::create([
                    'user_id' => $user->id,
                    'kartu_keluarga_santri' => $this->username,
                    'nama_kpl_kel_santri' => $this->name,
                    'source' => 'psb',
                    'is_collapse' => '0',
                ]);

                // return $user;

                event(new Registered($user));

                Auth::login($user);

                // return redirect('/psb');
            }
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }
}

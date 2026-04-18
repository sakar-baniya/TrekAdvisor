<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;

/**
 * Forgot Password Controller: Password birsekama reset link pathaune thau.
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Forgot Password View: User lai email halne screen dekhaune.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send Reset Link (Action): Email validate garera secure link inbox ma pathaune.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (TransportExceptionInterface $exception) {
            report($exception);

            $hint = 'We could not send the reset email right now. Please verify mail settings and try again.';
            $message = strtolower($exception->getMessage());
            if (str_contains($message, 'badcredentials') || str_contains($message, 'username and password not accepted') || str_contains($message, '535')) {
                $hint = 'Mail login failed (SMTP 535). Use a valid email + app password in MAIL_USERNAME and MAIL_PASSWORD.';
            }

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => $hint]);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not send the reset email right now. Please check mail settings and try again.']);
        }

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}


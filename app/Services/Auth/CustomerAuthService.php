<?php

namespace App\Services\Auth;

use App\Support\Database;
use App\Support\Session;

class CustomerAuthService
{
    public static function sendOtp(string $phone): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($cleanPhone) < 10) {
            return ['success' => false, 'message' => 'Please enter a valid 10-digit Indian phone number.'];
        }

        // Generate 6-digit OTP
        $otp = (string)random_int(100000, 999999);

        // Store challenge with 10 minute expiry
        $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        Database::execute(
            "INSERT INTO otp_challenges (identifier, otp_code, type, expires_at)
             VALUES (?, ?, 'login', ?)",
            [$cleanPhone, $otp, $expiresAt]
        );

        // Check environment before providing debug OTP (Never leak in production)
        $isLocalDebug = in_array(\App\Support\Config::get('app.env', 'production'), ['local', 'test']) && 
                        (bool)\App\Support\Config::get('app.debug', false);

        $result = [
            'success' => true,
            'message' => 'OTP sent to ' . substr($cleanPhone, 0, 2) . '******' . substr($cleanPhone, -2),
        ];

        if ($isLocalDebug) {
            $result['debug_otp'] = $otp;
        }

        return $result;
    }

    public static function verifyOtp(string $phone, string $otp): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $cleanOtp = trim($otp);

        $challenge = Database::fetch(
            "SELECT * FROM otp_challenges
             WHERE identifier = ? AND type = 'login' AND verified_at IS NULL AND expires_at > NOW()
             ORDER BY id DESC LIMIT 1",
            [$cleanPhone]
        );

        if (!$challenge) {
            return ['success' => false, 'message' => 'Invalid or expired OTP. Please request a new code.'];
        }

        if ((int)$challenge['attempts'] >= (int)$challenge['max_attempts']) {
            return ['success' => false, 'message' => 'Too many failed attempts. Please request a new OTP.'];
        }

        if ($challenge['otp_code'] !== $cleanOtp) {
            Database::execute("UPDATE otp_challenges SET attempts = attempts + 1 WHERE id = ?", [$challenge['id']]);
            return ['success' => false, 'message' => 'Incorrect OTP code.'];
        }

        // Mark OTP challenge as verified
        Database::execute("UPDATE otp_challenges SET verified_at = NOW() WHERE id = ?", [$challenge['id']]);

        // Find or create customer
        $user = Database::fetch("SELECT * FROM users WHERE phone = ?", [$cleanPhone]);
        if (!$user) {
            $userId = Database::insert(
                "INSERT INTO users (phone, full_name, is_phone_verified, status)
                 VALUES (?, 'Welkar Guest', 1, 'active')",
                [$cleanPhone]
            );
            $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
        }

        // Establish customer session
        Session::regenerate();
        Session::set('customer_user', $user);

        return ['success' => true, 'user' => $user];
    }

    public static function loginOrRegisterWithEmail(string $email, string $password): array
    {
        $cleanEmail = strtolower(trim($email));
        if (!filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Please enter a valid email address.'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters.'];
        }

        $user = Database::fetch("SELECT * FROM users WHERE email = ?", [$cleanEmail]);

        if ($user) {
            // User exists: verify password
            if (!empty($user['password_hash'])) {
                if (!password_verify($password, $user['password_hash'])) {
                    return ['success' => false, 'message' => 'Incorrect password for this email account.'];
                }
            } else {
                // Account exists without a password (created via Phone OTP or guest checkout).
                // Require active verified phone session proof before setting an initial password
                $activeVerified = Session::get('customer_user');
                if (!$activeVerified || (int)$activeVerified['id'] !== (int)$user['id']) {
                    return [
                        'success' => false,
                        'message' => 'This account was registered via mobile OTP. Please sign in with your phone number to set a password.'
                    ];
                }
                $hash = password_hash($password, PASSWORD_BCRYPT);
                Database::execute("UPDATE users SET password_hash = ? WHERE id = ?", [$hash, $user['id']]);
            }
        } else {
            // New user registration
            $namePart = ucfirst(explode('@', $cleanEmail)[0]);
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $userId = Database::insert(
                "INSERT INTO users (email, password_hash, full_name, is_email_verified, status)
                 VALUES (?, ?, ?, 1, 'active')",
                [$cleanEmail, $hash, $namePart]
            );
            $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
        }

        // Establish session
        Session::regenerate();
        Session::set('customer_user', $user);

        return ['success' => true, 'user' => $user, 'message' => 'Welcome to Welkar!'];
    }

    public static function logout(): void
    {
        Session::remove('customer_user');
        Session::regenerate();
    }
}

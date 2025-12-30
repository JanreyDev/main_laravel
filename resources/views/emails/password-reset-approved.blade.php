<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Approved</title>
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color: #f0f2f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">

                    <!-- Header with Logo/Brand -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); padding: 50px 40px; text-align: center;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">
                                        <!-- Optional: Add your logo here -->
                                        <div
                                            style="width: 60px; height: 60px; background-color: rgba(255,255,255,0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                            <span style="color: #ffffff; font-size: 32px; font-weight: bold;">🔐</span>
                                        </div>
                                        <h1
                                            style="color: #ffffff; margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -0.5px;">
                                            Password Reset Approved
                                        </h1>
                                        <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 16px;">
                                            Your account is ready to use
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 50px 40px;">
                            <!-- Greeting -->
                            <p style="color: #1f2937; font-size: 18px; line-height: 28px; margin: 0 0 24px 0;">
                                Hello <strong style="color: #4F46E5;">{{ $user->name }}</strong>,
                            </p>

                            <!-- Success Message -->
                            <div
                                style="background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%); border-left: 4px solid #10B981; padding: 20px; margin: 0 0 30px 0; border-radius: 8px;">
                                <p
                                    style="color: #065F46; font-size: 15px; margin: 0; line-height: 22px; font-weight: 500;">
                                    ✓ Your password reset request has been approved by an administrator.
                                </p>
                            </div>

                            <p style="color: #4b5563; font-size: 16px; line-height: 26px; margin: 0 0 30px 0;">
                                Your account has been successfully updated with a new temporary password. Please use the
                                credentials below to access your account.
                            </p>

                            <!-- Password Display Card -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin: 0 0 35px 0;">
                                <tr>
                                    <td>
                                        <div
                                            style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); border-radius: 12px; padding: 35px 30px; text-align: center; box-shadow: 0 8px 24px rgba(79, 70, 229, 0.25);">
                                            <p
                                                style="color: rgba(255,255,255,0.95); font-size: 13px; margin: 0 0 12px 0; text-transform: uppercase; letter-spacing: 2px; font-weight: 700;">
                                                Your New Password
                                            </p>
                                            <div
                                                style="background-color: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 20px 25px; border-radius: 10px; margin: 0 0 15px 0; border: 1px solid rgba(255,255,255,0.2);">
                                                <p
                                                    style="color: #ffffff; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: 3px; font-family: 'Courier New', Courier, monospace; word-break: break-all;">
                                                    {{ $newPassword }}
                                                </p>
                                            </div>
                                            <p
                                                style="color: rgba(255,255,255,0.85); font-size: 13px; margin: 0; line-height: 20px;">
                                                Click or tap to copy this password
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Warning -->
                            <div
                                style="background-color: #FEF3C7; border-left: 4px solid #F59E0B; padding: 20px 24px; margin: 0 0 35px 0; border-radius: 8px;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td width="30" valign="top">
                                            <span style="font-size: 20px;">⚠️</span>
                                        </td>
                                        <td>
                                            <p style="color: #92400E; font-size: 15px; margin: 0; line-height: 22px;">
                                                <strong style="font-weight: 700;">Important Security
                                                    Notice:</strong><br>
                                                For your account security, please change this temporary password
                                                immediately after logging in. Do not share this password with anyone.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Login Instructions -->
                            <div
                                style="background-color: #F9FAFB; padding: 25px; border-radius: 10px; margin: 0 0 35px 0; border: 1px solid #E5E7EB;">
                                <p
                                    style="color: #374151; font-size: 15px; line-height: 24px; margin: 0 0 15px 0; font-weight: 600;">
                                    📝 How to Login:
                                </p>
                                <ol
                                    style="color: #6b7280; font-size: 14px; line-height: 24px; margin: 0; padding-left: 20px;">
                                    <li style="margin-bottom: 8px;">Click the button below or visit the login page</li>
                                    <li style="margin-bottom: 8px;">Enter your email: <strong
                                            style="color: #4F46E5;">{{ $user->email }}</strong></li>
                                    <li style="margin-bottom: 8px;">Use the temporary password shown above</li>
                                    <li>Change your password in account settings</li>
                                </ol>
                            </div>

                            <!-- Login Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin: 0 0 30px 0;">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{{ url('/') }}"
                                            style="display: inline-block; background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); color: #ffffff; text-decoration: none; padding: 16px 48px; border-radius: 10px; font-size: 17px; font-weight: 700; box-shadow: 0 6px 20px rgba(79, 70, 229, 0.3); transition: all 0.3s ease;">
                                            Login to Your Account →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alternative Link -->
                            <div
                                style="background-color: #F9FAFB; padding: 20px; border-radius: 8px; text-align: center; border: 1px dashed #D1D5DB;">
                                <p style="color: #6b7280; font-size: 13px; line-height: 20px; margin: 0 0 10px 0;">
                                    If the button doesn't work, copy and paste this URL:
                                </p>
                                <p style="margin: 0;">
                                    <a href="{{ url('/') }}"
                                        style="color: #4F46E5; text-decoration: none; font-size: 14px; word-break: break-all; font-weight: 500;">
                                        {{ url('/') }}
                                    </a>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Help Section -->
                    <tr>
                        <td style="background-color: #F9FAFB; padding: 30px 40px; border-top: 1px solid #E5E7EB;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">
                                        <p
                                            style="color: #6b7280; font-size: 14px; margin: 0 0 15px 0; line-height: 22px;">
                                            Need help? Have questions about your account?
                                        </p>
                                        <p style="margin: 0;">
                                            <a href="mailto:support@ppdo.gov.ph"
                                                style="color: #4F46E5; text-decoration: none; font-weight: 600; font-size: 14px;">
                                                Contact Support →
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1f2937; padding: 35px 40px; text-align: center;">
                            <p style="color: #9ca3af; font-size: 13px; margin: 0 0 8px 0; line-height: 20px;">
                                This is an automated message. Please do not reply to this email.
                            </p>
                            <p style="color: #6b7280; font-size: 12px; margin: 0 0 15px 0;">
                                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #374151;">
                                <p style="color: #6b7280; font-size: 11px; margin: 0; line-height: 18px;">
                                    Provincial Planning and Development Office<br>
                                    Government of the Philippines
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Bottom Disclaimer -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="margin-top: 20px;">
                    <tr>
                        <td align="center" style="padding: 20px;">
                            <p
                                style="color: #9ca3af; font-size: 12px; line-height: 18px; margin: 0; text-align: center;">
                                This email was sent to <strong>{{ $user->email }}</strong><br>
                                If you didn't request a password reset, please contact your administrator immediately.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>

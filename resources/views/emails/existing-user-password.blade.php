@extends('emails.layouts.app')

@section('content')
    <div class="content">
        <td align="left">
            <table border="0" width="80%" align="center" cellpadding="0" cellspacing="0" class="container590">
                <tr>
                    <td align="left" style="color: #888888; width:20px; font-size: 16px; line-height: 24px;">
                        <!-- section text ======-->
                        <p style="line-height: 24px; margin-bottom:15px;">
                            Dear {{ ucwords($name) }},<br />
                            Congratulations!
                        </p>
                        <p style="line-height: 24px; margin-bottom:15px;">
                            Your account has been set up, and you are ready to explore Eazytask features from
                            {{ strtoupper($company) }}.
                        </p>

                        <p style="line-height: 24px; margin-bottom:20px; margin-top:20px">
                            Thank you for using our application! If you need any further assistance, please contact our
                            support team.
                        </p>

                        <table align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation"
                            style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';margin:20px auto;padding:0;text-align:center;width:100%">
                            <tbody>
                                <tr>
                                    <td align="center">
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                            role="presentation">
                                            <tbody>
                                                <tr>
                                                    <td style="text-align:center">
                                                        <a href="https://apps.apple.com/id/app/eazytask/id1642332032"
                                                            target="_blank">
                                                            <img src="https://w7.pngwing.com/pngs/270/658/png-transparent-app-store-apple-google-play-apple-text-logo-mobile-phones.png"
                                                                alt="Download on the App Store" style="max-width: 200px;">
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:center">
                                                        <a href="https://play.google.com/store/apps/details?id=com.ni.Easytask"
                                                            target="_blank">
                                                            <img src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png"
                                                                alt="Get it on Google Play" style="max-width: 200px;">
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p style="line-height: 24px">
                            Regards,<br />
                            <a href="https://www.eazytask.au">www.eazytask.au</a>
                        </p>

                        <br />

                        <p class="small" style="line-height: 24px; margin-bottom:20px;">
                            If you're having trouble accessing your account, please communicate with Admin.
                        </p>

                        @include('emails.layouts.footer')
                    </td>
                </tr>
            </table>
        </td>
    </div>
@endsection

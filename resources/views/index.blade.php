@extends('layouts.index')

@section('content')
    <section class="site-section site-section-light site-section-top parallax-image">
        <video class="bg-video"
                src="/img/index.mp4"
                preload="auto"
                loop
                autoplay
                width="100%"
                height="100%"
                controls
        >
        </video>

        <div class="container">
            <div class="col-sm-12 text-center">
                <img class="img-responsive center-block animation-fadeIn pew-pew"
                        src="/images/client/logo-index.png"
                        alt="pew pew"/>
            </div>

            <div class="col-md-10 col-md-offset-1 site-block bigger-text">
                <p class="bigger-text">
                    Welcome to Axanar Donors - where you can be a part of making the dream of Axanar
                    a reality! This website will allow you to track your previous donations to the project,
                    check the status of your perks, and update your contact and shipping information. 
                    <Br/>
                    <Br/>
                </p>
                <table>
                    <tbody>
                    <tr>
                        <td> If you’ve previously accessed your Axanar Donors account, then simply click here to log in:
                            <br/>
                            <br/>

                        </td>
                        <td style="text-align:center;"><a href="/login" class="btn btn-primary btn-md">LOG IN</a></td>
                    </tr>

                    <tr>
                        <td>
                            <strong>If you have donated to Axanar in the past</strong> and this is your first time logging into Axanar Donors,
                             please activate your account by creating a new password here: <br/>
                            <br/>

                        </td>
                        <td style="text-align:center;"><a href="/password/activate"
                                class="btn btn-primary btn-md">I AM A PREVIOUS DONOR</a></td>
                    </tr>
                    <tr>
                        <td>
                            <strong>If you have never donated to Axanar before</strong>, please create a new account by clicking here:
                            <br/>
                            <br/>

                        </td>
                        <td style="text-align:center;"><a
                        href="/registration"
                                class="btn btn-primary btn-md">I HAVE NEVER DONATED TO AXANAR BEFORE</a></td>
                    </tr>
                    </tbody>
                </table>
                @include('partials.email-support')
            </div>
        </div>

        <div class="spacer-div" style=""></div>

    </section>
@endsection

@push('body-scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="/js/plugins.js"></script>
    <script src="/js/app.js"></script>
    <script>
      (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
          (i[r].q = i[r].q || []).push(arguments);
        }, i[r].l = 1 * new Date();
        a = s.createElement(o), m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m);
      })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
      ga('create', 'UA-2146868-46', 'auto');
      ga('send', 'pageview');
    </script>
    <script type="text/JavaScript">
      window.setTimeout(function() {
        location.href = '/logout';
      }, 3540000);
      console.log('Admin', '[<?= $_SESSION['admin'] ?? 'N/A' ?>]');
    </script>
@endpush

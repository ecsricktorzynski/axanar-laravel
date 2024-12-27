<section id="axanar-email-support">
    @if(!empty($activating))
    <p>
        IMPORTANT! If you no longer have access to the e-mail address you used previously,
        you will need to contact our Customer Service at
        <a href="mailto:support@axanarproductions.com">{{ config('app.support-email') }}</a>
    </p>
    @else
        <p>
            Having a problem with Axanar Donors?  Contact our Customer Service at
            <a href="mailto:support@axanarproductions.com">{{ config('app.support-email') }}</a>
    </p>
    @endif
</section>

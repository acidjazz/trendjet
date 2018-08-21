@component('mail::message')

# Login Attempt
Someone (hopefully you) has attempted to login to trendjet

@component('mail::button', ['url' => config('app.web').'login/'.$id])
Login
@endcomponent

@endcomponent

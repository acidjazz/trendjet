
@component('mail::message')

# You have been invited 
you have been invited to join {{ $org }}

@component('mail::button', ['url' => config('app.web').'login/'.$login['id']])
login to pub
@endcomponent

@endcomponent

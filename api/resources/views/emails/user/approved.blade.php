
@component('mail::message')

# You have been Approved
main body copy here

@component('mail::button', ['url' => config('app.web').'login/'.$login['id']])
login to pub
@endcomponent

@endcomponent

@component('mail::message')
# Reset Account
Welcome {{$data['data']->name}} <br>
The body of your message.

@component('mail::button', ['url' => aurl('forget/password/'.$data['token'])])
Click here to reset your Password
@endcomponent
Or <br>
Copy this link
<a href="{{aurl('reset/password/'.$data['token'])}}">{{aurl('reset/password/'.$data['token'])}}</a>
Thanks,<br>
{{ config('app.name') }}
@endcomponent

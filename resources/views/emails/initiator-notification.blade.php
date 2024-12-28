<x-mail::message>

Dear {{ $initiator }}, <br/>
Greetings for the day!! <br/>

{{ $emailContent }} <br/><br/>

@component('mail::button', ['url' => url('/')])
    Visit The Site
@endcomponent
Sincerely, <br/>
Tashi InfoComm Private Limited.

</x-mail::message>
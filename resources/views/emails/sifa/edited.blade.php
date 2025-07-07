<x-mail::message>

Dear {{ $approverName }},  
Greetings of the day!

{{ $emailContent }}

@component('mail::button', ['url' => url('/')])
Review SIFA Application
@endcomponent

Sincerely,  
**Tashi InfoComm Private Limited**

</x-mail::message>

<x-mail::message>

Dear {{ $registration->employee->name }},  
Greetings of the day!

This is a kind reminder to **review and update your SIFA application**.

We request that you verify and complete the following information as applicable:
- SIFA NOMINATIONS
- SIFA DEPENDENTS  
- Upload of supporting documents

@component('mail::button', ['url' => url('/')])
Update My SIFA Application
@endcomponent

If you have recently updated your application, kindly ignore this message.

Sincerely,  
**Tashi InfoComm Private Limited**

</x-mail::message>

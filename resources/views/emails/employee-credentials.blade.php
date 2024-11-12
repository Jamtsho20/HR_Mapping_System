<x-mail::message>

    Dear {{ $employee->name }}, <br />
    Greetings for the day!! <br />

    You have been provided access to the HRMS.
    You can use the credentials given below to log into the system. Please consider changing your password after logging in. 


    Username: {{ $employee->username  }} 
    Password: {{ $password }}         

   
    Thanks,
    TIPL HRMS.

</x-mail::message>
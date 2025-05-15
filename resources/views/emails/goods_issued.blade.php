<!DOCTYPE html>
<html>
<head>
    <title>Goods Issued Notification</title>
</head>
<body>
    <p>Dear {{ $employee }},</p>
    <p>Greetings for the day!!</p>

    <p>The goods for your requisition application have been successfully issued.</p>
    <p><strong>Requisition No:</strong> {{ $requisitionNo->transaction_no ?? '-'}}</p>
    <p><strong>Issued Date:</strong> {{ now()->format('Y-m-d H:i') }}</p>

    <p>Please check your system for further details.</p>

    <p>Sincerely,</p>
    <p>Tashi InfoComm Private Limited.</p>
</body>
</html>

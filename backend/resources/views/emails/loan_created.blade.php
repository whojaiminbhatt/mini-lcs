<!DOCTYPE html>
<html>
<head>
    <title>New Loan Created</title>
</head>
<body>
    <h2>Loan Registration Successful</h2>
    <p>Dear {{ $loan->customer_name }},</p>
    <p>Your loan (<b>{{ $loan->loan_no }}</b>) has been successfully created.</p>
    <ul>
        <li>Total Amount: ₹{{ $loan->total_amount }}</li>
        <li>EMI Amount: ₹{{ $loan->emi_amount }}</li>
    </ul>
    <p>Thank you for choosing us.</p>
</body>
</html>

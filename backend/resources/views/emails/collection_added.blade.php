<!DOCTYPE html>
<html>
<head>
    <title>Payment Received</title>
</head>
<body>
    <h2>Payment Confirmation</h2>
    <p>Dear {{ $collection->loan->customer_name }},</p>
    <p>We have received a payment of ₹{{ $collection->amount_paid }} for your loan (<b>{{ $collection->loan->loan_no }}</b>).</p>
    <ul>
        <li>Payment Mode: {{ ucfirst($collection->payment_mode) }}</li>
        <li>Date: {{ $collection->collected_at->format('d M Y, h:i A') }}</li>
    </ul>
    <p>Thank you for your payment.</p>
</body>
</html>

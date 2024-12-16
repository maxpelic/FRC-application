# Stripe Transactions API

This endpoint connects to the Stripe API and retrieves a list of charges over the past 30 days.

Example request:

curl http://localhost:8888/stripe.php

Example response:

    {
        "total_amount": 100.00,
        "total_charges": 10,
        "charges": [
            {
                "amount":20,
                "currency":"usd",
                "description":"Example transaction",
                "status": "succeeded"
            },
            ...
        ]
    } 
SELECT * 
FROM atm_transactions JOIN People 
WHERE atm_location LIKE "Hum%"
AND day = 28
;

SELECT *
FROM airports JOIN flights 
WHERE full_name LIKE "Fift%" and day = 28
;

SELECT * 
FROM atm_transactions
;

SELECT *
FROM interviews
WHERE month = 7 and day = 28 and transcript LIKE "%bakery%"
;

SELECT *
FROM atm_transactions atm
JOIN bank_accounts act ON act.account_number = atm.account_number
WHERE atm_location LIKE "Legg%"
AND transaction_type = 'withdraw'
AND day = 28
;

SELECT *
FROM phone_calls
WHERE day = 28 
AND duration <= 60
;

SELECT * 
FROM airports 
JOIN flights JOIN passengers ON flights.id = passengers.flight_id JOIN people on passengers.passport_number = people.passport_number
WHERE city = "Fiftyville" and day = 29
AND flights.id = 36
ORDER BY hour, minute 
;

SELECT *
FROM phone_calls JOIN people ON people.phone_number = phone_calls.receiver
WHERE duration < 60
AND caller = '(367) 555-5533'
AND day = 28
;
SELECT *
FROM airports 
WHERE id = 4
;
SELECT *
FROM bakery_security_logs bank JOIN people p ON bank.license_plate = p.license_plate
JOIN bank_accounts act ON act.person_id = p.id
JOIN atm_transactions atm ON atm.account_number = act.account_number
JOIN phone_calls ON phone_calls.caller = p.phone_number
WHERE bank.day = 28
AND duration <= 60
AND bank.hour = 10
AND bank.minute BETWEEN 15 and 25
AND atm.atm_location LIKE "Legg%"
AND transaction_type = 'withdraw';

SELECT *
FROM flights JOIN airports 
WHERE full_name LIKE "Fifty%" and day = 28
;

SELECT person.id, people.name, people.phone_number
FROM bank_accounts JOIN people ON person.id = people.name
;

SELECT * 
from people

-- The thief is Bruce
-- The helper is Robin
-- He's flying to New York City




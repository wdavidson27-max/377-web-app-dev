email_user_name = 'davidsonwill14@gmail.com'
email_app_password = 'xyit uyrm muwf cckv'

import smtplib

from email.message import EmailMessage

with smtplib.SMTP_SSL('smtp.gmail.com', 465) as server:
    print('Authenticating...')
    server.login(email_user_name, email_app_password)
    print('Sending...')

    lines = open('send-email-data.csv', 'r').readlines()
    for line in lines:
        pieces = line.strip().split(',')
    
        to = pieces[0]
        first_name = pieces[1]
        last_name = pieces[2]
        order_number = pieces[3]

        msg = EmailMessage()
        
        msg.set_content('Dear ' + first_name + ',\nOrder' + order_number + ' is on its way')
        msg['Subject'] = 'Order #' + order_number + ' Shipped'
        msg['From'] = email_user_name
        msg['To'] = 'nquagliariello27@hanoverstudents.org'


    
        server.send_message(msg)

    server.quit()
    print('Message sent!')
import qrcode

qr = qrcode.QRCode(
    version=1,
    box_size=5,
    border=10,
)

data = 'https://www.linkedin.com/in/scottquagliariello/'

qr.add_data(data)
qr.make(fit=True)

img = qr.make_image()
img.save('qr-scott.png')



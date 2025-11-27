import click
from pyzbar.pyzbar import decode
from PIL import Image


@click.command()
@click.option('--filename', help='Name of the barcode image to process')
def process(filename):
    try:
        decoded_image = (decode(Image.open('/home/shoumik/Documents/panacealive/public/images/qr/' + filename))[0].data)
        print(decoded_image)
    except:
        print("cannot scan")


if __name__ == '__main__':
    process()

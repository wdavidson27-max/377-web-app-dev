import os

folder = '/Users/wdavidson09/Documents/377-web-app-dev/Python/Photos'
prefix = input('What is the file prefix? ')
file_type = 'jpg'
count = 1

for filename in os.listdir(folder):
    extension = filename.split('.')[-1]
    if extension == file_type:
        new_filename = prefix + '-' + str(count) + '.' + file_type
        
        source = folder + '/' + filename
        destination = folder + '/' + new_filename

        print('Renaming ' + filename + ' to ' + new_filename)
        os.rename(source, destination)

        count +=1
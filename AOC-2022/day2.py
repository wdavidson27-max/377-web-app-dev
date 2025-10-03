file = open('day2.txt', 'r')
lines = file.readlines()

points = 0
myThrow = 0
oppThrow = 0
for line in lines:
    throws = line.strip().split(' ')

    print('My throw: ' + throws[1])
    print('Opponents throw: ' + throws[0])
    print('Results: ')

    if myThrow == "A" and oppThrow == "Z" or myThrow == "B" and oppThrow == "X" or myThrow == "C" and oppThrow == "Y" :
        points + 1
    else:
        myThrow ==    
   

    


print(points)




    

    
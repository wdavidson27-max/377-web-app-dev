file = open('day1.txt', 'r')
lines = file.readlines()

startpoint = 50
count1 = 0
count2 = 0

for line in lines:
    line = line.strip()
    
    rotations = int(line[1:])

    direction = line[0]

    for i in range(rotations):
        if direction == "L":
            startpoint = (startpoint - 1 + 100) % 100
        else:
            startpoint = (startpoint + 1) % 100
        if startpoint == 0:
            count2 += 1
    if startpoint == 0:
        count1 += 1

print(count1)    
print(count2)



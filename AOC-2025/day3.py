file = open("day3.txt", "r")
lines = file.readlines()



for line in lines:
    line = line.strip()

    numbers = [int(x) for(x) in line]
    
    max = 0
    max2 = 0
    pos1 = 0
    for i in range(len(numbers)):
        if max < numbers[i]:
            max = numbers[i]
            pos1 = i
    

    for j in range(pos1+ 1):
         if max2 < numbers[j]:
             max2 = numbers[j]
             pos2 = j
             
    
    
    
    print(max)  
    print(max2)
             
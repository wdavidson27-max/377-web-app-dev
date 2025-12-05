file = open("day5.txt", "r")
lines = file.readlines()

ranges = []
ids = []

total = 0
first = True
for line in lines:
    line = line.strip()

    if line == "":
        first = False
        continue
    if first:
        values = line.split('-')
        start = int(values[0])
        end = int(values[1])
        ranges.append([start, end])
    else:
        ids.append(int(line))

for id in ids:
    for range in ranges:
        if range[0] <= id and range[1] >= id:
            total += 1
            break
        
print(total)             
    
       
            

  

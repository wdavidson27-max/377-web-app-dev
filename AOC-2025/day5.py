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

#Part 2 code

ranges.sort()
merge = []
current_start = ranges[0][0]
current_end = ranges[0][1]

for r in ranges[1:]:
    start = r[0]
    end = r[1]
    if start <= current_end + 1:
        if end > current_end:
            current_end = end
    else:
        merge.append([current_start, current_end])
        current_start = start
        current_end = end

merge.append([current_start, current_end])

total = 0
for r in merge:
    total += r[1] - r[0] + 1
print(total)

    
       
            

  

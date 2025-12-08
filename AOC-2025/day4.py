file = open("day4.txt", "r")
lines = file.readlines()

grid = []
for line in lines:
    line = line.strip()

    grid.append([x for x in line])

for row in line:
    for column in line:


print(grid)   
print(grid[0]) 
print(grid[0][2])
    


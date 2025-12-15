file = open("day6.txt", "r")
lines = file.readlines()

grid = []

for line in lines:
    line = line.strip()
    grid.append([x for x in line.strip().split()])

for row in grid:
    print(row)

total = 0
for c in range(len(grid[0])):
    if grid[len(grid)- 1][c] == "*":
        product = 1
        for r in range(len(grid)-1):
            product *= int(grid[r][c])
        total += product
    else:
        sum = 0
        for r in range(len(grid)-1):
            sum += int(grid[r][c])
        total += sum
        
print(total)

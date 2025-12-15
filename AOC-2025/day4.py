file = open("day4.txt", "r")
lines = file.readlines()

grid = []

for line in lines:
    line = line.strip()

    grid.append([x for x in line])

        

print(grid)   
print(grid[0]) 
print(grid[0][2])

total = 0
rows = len(grid)
cols = len(grid[0])

for r in range(rows):
    for c in range(cols):

        if grid[r][c] != "@":
            continue

        neighbors = 0

        if r > 0 and c > 0 and grid[r-1][c-1] == "@":
            neighbors += 1
        if r > 0 and grid[r-1][c] == "@":
            neighbors += 1
        if r > 0 and c < cols-1 and grid[r-1][c+1] == "@":
            neighbors += 1
        if c > 0 and grid[r][c-1] == "@":
            neighbors += 1
        if c < cols-1 and grid[r][c+1] == "@":
            neighbors += 1
        if r < rows-1 and c > 0 and grid[r+1][c-1] == "@":
            neighbors += 1
        if r < rows-1 and grid[r+1][c] == "@":
            neighbors += 1
        if r < rows-1 and c < cols-1 and grid[r+1][c+1] == "@":
            neighbors += 1
        if neighbors < 4:
            total += 1

print(total)





   
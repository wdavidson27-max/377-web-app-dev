file = open("day7.txt", "r")
lines = file.readlines()

grid = []
for line in lines:
    line = line.strip()
    grid.append(list(line))

h = len(grid)
w = len(grid[0])

start_x = 0
start_y = 0
for y in range(h):
    for x in range(w):
        if grid[y][x] == "S":
            start_x = x
            start_y = y

beams = []
beams.append([start_x, start_y + 1])

split_count = 0
new_beams = []
            
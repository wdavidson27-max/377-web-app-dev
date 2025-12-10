file = open("day4.txt", "r")
lines = file.readlines()

grid = []

for line in lines:
    line = line.strip()

    grid.append([x for x in line])

        

print(grid)   
print(grid[0]) 
print(grid[0][2])


# We have to make a nested loop to go through the rows and 
# columns in the grid so we can see if a roll of paper can
# be accessed. Then we can keep all of the rolls that can be
# accessed in a variable called total and then at the end print
# out the total 

total = 0

for r in range(len(grid)):
    print(r)
    print(grid[r])
    for c in range(len(grid[0])):
        print(c)
        print(grid[r][c])

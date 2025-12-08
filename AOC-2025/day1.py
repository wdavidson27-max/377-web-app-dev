file = open("day1.txt", "r")
lines = file.readlines()

totalzero = 0
current = 50
for line in lines:
    line = line.strip()

    distance = int(line[1:])

    if line[0] == 'R':
        current += distance
    elif line[0] == 'L':
        current -= distance

    current %= 100

    if current == 0:
        totalzero += 1

print(current)
print(totalzero)


